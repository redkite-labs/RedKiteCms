<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\Language\LanguagesForm;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\RuntimeException;

class LanguagesController extends Base\BaseController
{
    public function indexAction()
    {
        // @codeCoverageIgnoreStart
        if (!extension_loaded('intl')) {
            throw new RuntimeException('To manage languages you must enable the intl extension in your php.ini file. Operation aborted');
        }
        // @codeCoverageIgnoreEnd

        $request = $this->container->get('request');
        
        $languagesForm = new LanguagesForm();
        $form = $this->container->get('form.factory')->create($languagesForm);

        $params = array(
            'base_template' => $this->container->getParameter('red_kite_labs_theme_engine.base_template'),
            'languages' => ChoiceValues::getLanguages($this->createLanguageRepository()),
            'active_language' => $request->get('language'),
            'form' => $form->createView(),
            'configuration' => $this->container->get('red_kite_cms.configuration'),
        );

        return $this->container->get('templating')->renderResponse('RedKiteCmsBundle:Languages:index.html.twig', $params);
    }

    public function saveLanguageAction()
    {
        $request = $this->container->get('request');            
        $languageManager = $this->container->get('red_kite_cms.language_manager');
        $alLanguage = $this->fetchLanguage($request->get('languageId'), $languageManager);
        $languageManager->set($alLanguage);

        $parameters = array(
            'MainLanguage' => $request->get('isMain'),
            'LanguageName' => $request->get('newLanguage'),
        );
        if ($languageManager->save($parameters)) {
            $language = (null === $alLanguage) ? $languageManager->get() : $alLanguage;
            $message = $this->translate('The language has been successfully saved');
                            
            return $this->buildJSonHeader($message, $language);
        }

        // @codeCoverageIgnoreStart
        throw new RuntimeException('The language has not been saved because an unespected error has been occoured when saving');
        // @codeCoverageIgnoreEnd
    }

    public function deleteLanguageAction()
    {
        $request = $this->container->get('request');
        $languageManager = $this->container->get('red_kite_cms.language_manager');
        $alLanguage = $this->fetchLanguage($request->get('languageId'), $languageManager);
        if (null === $alLanguage) {    
            throw new RuntimeException('Any language has been choosen for removing');
        }
        
        $result = $languageManager
            ->set($alLanguage)
            ->delete()
        ;
        if ($result) {      
            $message = $this->translate('The language has been successfully deleted');              
            
            return $this->buildJSonHeader($message, $alLanguage);
        }
        
        // @codeCoverageIgnoreStart
        throw new RuntimeException('The language has not been deleted');
        // @codeCoverageIgnoreEnd
    }
    
    public function loadLanguageAttributesAction()
    {
        $values = array();
        $request = $this->container->get('request');
        $languageId = $request->get('languageId');
        if($languageId != 'none')
        {
            $alLanguage = $this->fetchLanguage($languageId);             
            $values[] = array("name" => "#languages_language", "value" => $alLanguage->getLanguageName());
            $values[] = array("name" => "#languages_isMain", "value" => $alLanguage->getMainLanguage());
        }
        
        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    protected function buildJSonHeader($message, $language = null)
    {
        $request = $this->container->get('request');
        $languages = $languagesList = ChoiceValues::getLanguages($this->createLanguageRepository());
        unset($languagesList['none']);
        
        $values = array();
        $values[] = array("key" => "message", "value" => $message);
        $values[] = array("key" => "languages", "value" => $this->container->get('templating')->render('RedKiteCmsBundle:Languages:languages_list.html.twig', array('languages' => $languagesList, 'active_language' => $request->get('language'),)));
        $values[] = array("key" => "languages_menu", "value" => $this->container->get('templating')->render('RedKiteCmsBundle:Cms:menu_dropdown.html.twig', array('id' => 'al_languages_navigator', 'type' => 'al_language_item', 'value' => (null !== $language) ? $language->getId() : 0, 'text' => $request->get('language'), 'items' => $languages)));
        
        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function createLanguageRepository()
    {
        $factoryRepository = $this->container->get('red_kite_cms.factory_repository');

        return $factoryRepository->createRepository('Language');
    }

    private function fetchLanguage($id, $languageManager = null)
    {
        $languageManager = (null === $languageManager) ? $this->container->get('red_kite_cms.language_manager') : $languageManager;
        $languageRepository = $languageManager->getLanguageRepository();

        return ($id != 'none') ? $languageRepository->fromPk($id) : null;
    }
}
