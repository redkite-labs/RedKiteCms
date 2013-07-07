<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\Block\FileBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;
use AlphaLemon\AlphaLemonCmsBundle\Core\AssetsPath\AlAssetsPath;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * Description of AlBlockManagerFile
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerFile extends AlBlockManagerJsonBlockContainer
{
    protected $translator;
    protected $cmsLanguage;
    protected $domain = 'messages';
    
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($container, $validator);
        
        $this->translator = $this->container->get('translator');
        $this->cmsLanguage = $this->container->get('alpha_lemon_cms.configuration')->read('language');
    }
    
    public function getDefaultValue()
    {
        $value = sprintf(
        '{
            "0" : {
                "file" : "%s",
                "description" : "",
                "opened" : false
            }
        }', $this->translator->trans("Click to load a file", array(), $this->domain, $this->cmsLanguage));

        return array(
            'Content' => $value,
        );
    }
    
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock);
        $item = $items[0];
        $file = $item['file'];
        $opened = $this->itemOpenedToBool($item);
        $description = (array_key_exists('description', $item)) ? $item['description'] : '';
        
        $kernel = $this->container->get('kernel');
        $deployBundle = $this->container->getParameter('alpha_lemon_theme_engine.deploy_bundle');
        $deployBundleAsset = new AlAsset($kernel, $deployBundle);

        return ($opened)
            ? sprintf("{%% set file = kernel_root_dir ~ '/../" . $this->container->getParameter('alpha_lemon_cms.web_folder') . "/%s/%s' %%} {{ file_open(file) }}", $deployBundleAsset->getAbsolutePath(), $file)
            : sprintf('<a href="/%s/%s" />%s</a>', AlAssetsPath::getUploadFolder($this->container), $file, ( ! empty($description)) ? $description : basename($file));        
    }
        
    public function editorParameters()
    {        
        $items = $this->decodeJsonContent($this->alBlock);
        $item = $items[0];
        $item['opened'] = $this->itemOpenedToBool($item);
             
        $formClass = $this->container->get('file.form');
        $form = $this->container->get('form.factory')->create($formClass, $item); 
        
        return array(
            'template' => 'AlphaLemonCmsBundle:Editor:base_editor_form.html.twig',
            'title' => $this->translator->trans('Files editor', array(), $this->domain, $this->cmsLanguage),
            'form' => $form->createView(),
            'configuration' => $this->container->get('alpha_lemon_cms.configuration'),
        );
    }
    
    public function getHideInEditMode()
    {
        return true;
    }
    
    protected function replaceHtmlCmsActive()
    {
        $options = $this->getOptions(); 
        
        return array('RenderView' => array(
            'view' => 'FileBundle:Content:file.html.twig',
            'options' => $options,
        ));
    }
    
    private function getOptions()
    {
        $items = $this->decodeJsonContent($this->alBlock);
        $item = $items[0];
        $item['opened'] = $this->itemOpenedToBool($item);
        $file = $item['file'];
        
        $options = array(
            'webfolder' => $this->container->getParameter('alpha_lemon_cms.web_folder'),
            'folder' => AlAssetsPath::getUploadFolder($this->container),
            'filename' => $file,
        );
        
        if ( ! $item['opened']) {
            $options['displayValue'] = (array_key_exists('description', $item) && ! empty($item['description'])) ? $item['description'] : $file;
        }
        
        return $options;
    }
    
    private function itemOpenedToBool($item)
    {
        return array_key_exists('opened', $item) ? filter_var($item['opened'], FILTER_VALIDATE_BOOLEAN) : false;
    }
}
