<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\Block\NavigationMenuBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBase;
use AlphaLemon\Block\NavigationMenuBundle\Core\Form\LanguagesForm;
use Symfony\Component\Finder\Finder;

/**
 * Defines the Block Manager to render a navigation menu for the website's languages.
 * 
 * Menu is renderd as an unordered list
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerNavigationMenu extends AlBlockManagerContainer
{
    private $urlManager = null;
    private $kernel = null;
    private $flagsAsset;
    private $page = null;

    /**
     * Constructor
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     */
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($container, $validator);

        $this->languageRepository = $this->factoryRepository->createRepository('Language');
        $this->urlManager = $this->container->get('alpha_lemon_cms.url_manager');
        $this->kernel = $this->container->get('kernel');
        $flagsFolder = $this->container->getParameter('alpha_lemon_cms.flags_folder');
        $this->flagsAsset = new AlAsset($this->kernel, $flagsFolder); 
    }

    /**
     *  {@inheritdoc}
     */
    public function getDefaultValue()
    {
        return array("Content" => json_encode($this->generateValues()));
    }
    
    /**
     *  {@inheritdoc}
     */
    public function editorParameters()
    {
        $value = AlBlockManagerJsonBase::decodeJsonContent($this->alBlock->getContent());
   
        $flagsDirectories = array();
        $finder = new Finder();
        $folders = $finder->directories()->depth(0)->in($this->flagsAsset->getRealPath());
        foreach ($folders as $folder) {
            $flagDirectory = basename($folder->getFileName());
            $flagsDirectories[$flagDirectory] = $flagDirectory;
        }
        
        $formClass = new LanguagesForm($flagsDirectories, $value["languages"], $value["imagesFolder"]);
        $form = $this->container->get('form.factory')->create($formClass);

        return array(
            "template" => 'NavigationMenuBundle:Editor:editor.html.twig',
            "title" => "Navigation languages menu editor",
            "form" => $form->createView(),
            'configuration' => $this->container->get('alpha_lemon_cms.configuration'),
        );
    }

    /**
     *  {@inheritdoc}
     */
    protected function renderHtml()
    {
        $contents = $this->generateValues();
        
        return array('RenderView' => array(
            'view' => 'NavigationMenuBundle:Content:navigation_menu.html.twig',
            'options' => array(
                'languages' => $contents["languages"],
            ),
        ));
    }
    
    /**
     *  {@inheritdoc}
     */
    protected function edit(array $values)
    {
        $values = $this->updateSavedLanguages($values);      

        return parent::edit($values);
    }
    
    protected function updateSavedLanguages(array $values)
    {
        if (array_key_exists('Content', $values)) {           
            $languages = array();            
            $unserializedData = array();
            $serializedData = $values['Content'];
            parse_str($serializedData, $unserializedData);
            
            $imagesFolder = $unserializedData["al_json_block"]["flags_directories"];
            unset($unserializedData["al_json_block"]["flags_directories"]);
            
            foreach ($unserializedData["al_json_block"] as $languageName => $country) {
                $language = $this->languageRepository->fromLanguageName($languageName);
                $url = $this->generateUrl($language);

                $countryName = strtolower($country);
                $country = $this->generateCountryPath($imagesFolder, $countryName);
                
                $languages[$languageName] = array(
                    "country" => $country,
                    "url" => $url,
                );
            }
            
            $newValues = array(
                "imagesFolder" => $imagesFolder,
                "languages" => $languages,
            );
            
            $values['Content'] = json_encode($newValues);
        }
        
        return $values;
    }
    
    /**
     * Generates the block's value
     * 
     * @return array
     */
    protected function generateValues()
    {
        $items = null;
        $imagesFolder = "20x15";
        if (null !== $this->alBlock) {
            $values = json_decode($this->alBlock->getContent(), true);
            $items = $values["languages"];
            $imagesFolder = $values["imagesFolder"];
        }
                
        $languages = array();
        $activeLanguages = $this->languageRepository->activeLanguages();
        foreach ($activeLanguages as $language) {
            $languageName = $language->getLanguageName();            
            $url = $this->generateUrl($language);
            
            $country = "";
            if (null !== $items && array_key_exists($languageName, $items)) {
                $country = $items[$languageName]["country"];
            }
            
            if (empty($country) ) {
                $country = $this->generateCountryPath($imagesFolder, $languageName);
            }
            
            $languages[$languageName] = array(
                "country" => $country,
                "url" => $url,
            );
        }
        
        $newValues = array(
            "imagesFolder" => $imagesFolder,  
            "languages" => $languages,
        );
        
        if ($items !== null && $newValues != $values) {
            $this->edit(array("Content" => json_encode($newValues)));
        }
        
        return $newValues;
    }
    
    private function generateUrl($language)
    {
        if (null === $this->page) {
            $this->page = $this->container->get('alpha_lemon_cms.page_tree')->getAlPage();  
        }
        
        $url = $this->urlManager
                    ->buildInternalUrl($language, $this->page)
                    ->getInternalUrl();
        if (null === $url)  {
            $url = '#';
        }
        
        return $url;
    }
    
    private function generateCountryPath($imagesFolder, $countryName)
    {
        $country = "";
        $countryImage = $this->flagsAsset->getRealPath() . '/' . $imagesFolder . '/' . $countryName . '.png';
        if (file_exists($countryImage)) {
            $country = "/" . $this->flagsAsset->getAbsolutePath() . '/' . $imagesFolder . '/' . $countryName . '.png';
        }
        
        return $country;
    }   
}
