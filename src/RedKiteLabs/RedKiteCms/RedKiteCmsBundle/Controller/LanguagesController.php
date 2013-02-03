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

namespace AlphaLemon\AlphaLemonCmsBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\Language\LanguagesForm;

class LanguagesController extends Base\BaseController
{
    public function indexAction()
    {
        // @codeCoverageIgnoreStart
        if (!extension_loaded('intl')) {
            return $this->renderDialogMessage('To manage languages you must enable the intl extension in your php.ini file. Operation aborted');
        }
        // @codeCoverageIgnoreEnd

        $languagesForm = new LanguagesForm();
        $form = $this->container->get('form.factory')->create($languagesForm);

        $params = array('base_template' => $this->container->getParameter('alpha_lemon_theme_engine.base_template'),
                        'languages' => ChoiceValues::getLanguages($this->createLanguageRepository()),
                        'form' => $form->createView());

        return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Languages:index.html.twig', $params);
    }

    public function saveLanguageAction()
    {
        try {
            $request = $this->container->get('request');
            $languageManager = $this->container->get('alpha_lemon_cms.language_manager');
            $languageManager->setTranslator($this->container->get('translator'));
            $alLanguage = $this->fetchLanguage($request->get('languageId'), $languageManager);
            $languageManager->set($alLanguage);

            $parameters = array('MainLanguage' => $request->get('isMain'),
                                'LanguageName' => $request->get('newLanguage'));
            if ($languageManager->save($parameters)) {
                $language = (null === $alLanguage) ? $languageManager->get() : $alLanguage;
                
                return $this->buildJSonHeader('The language has been successfully saved', $language);
            }

            // @codeCoverageIgnoreStart
            throw new \RuntimeException('An error has been occoured, so the language has not been saved');
            // @codeCoverageIgnoreEnd
        } catch (\Exception $e) {
            return $this->renderDialogMessage($e->getMessage());
        }
    }

    public function deleteLanguageAction()
    {
        try {
            $request = $this->container->get('request');
            $languageManager = $this->container->get('alpha_lemon_cms.language_manager');
            $alLanguage = $this->fetchLanguage($request->get('languageId'), $languageManager);
            if ($alLanguage != null) {
                $result = $languageManager
                            ->set($alLanguage)
                            ->delete();
                if ($result) {
                    $message = $this->container->get('translator')->trans('The language has been successfully removed');

                    return $this->buildJSonHeader($message, $alLanguage);
                }

                // @codeCoverageIgnoreStart
                throw new \RuntimeException($this->container->get('translator')->trans('The language has not been deleted'));
                // @codeCoverageIgnoreEnd
            }

            throw new \RuntimeException($this->container->get('translator')->trans('Any language has been choosen for removing'));
        } catch (\Exception $e) {
            return $this->renderDialogMessage($e->getMessage());
        }
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
        $languages = ChoiceValues::getLanguages($this->createLanguageRepository());

        $values = array();
        $values[] = array("key" => "message", "value" => $message);
        $values[] = array("key" => "languages", "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Languages:languages_list.html.twig', array('languages' => $languages)));
        $values[] = array("key" => "languages_menu", "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Cms:menu_combo.html.twig', array('id' => 'al_languages_navigator', 'type' => 'al_language_item', 'value' => (null !== $language) ? $language->getId() : 0, 'text' => $request->get('language'), 'items' => $languages)));

        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function createLanguageRepository()
    {
        $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');

        return $factoryRepository->createRepository('Language');
    }

    private function fetchLanguage($id, $languageManager = null)
    {
        $languageManager = (null === $languageManager) ? $this->container->get('alpha_lemon_cms.language_manager') : $languageManager;
        $languageRepository = $languageManager->getLanguageRepository();

        return ($id != 'none') ? $languageRepository->fromPk($id) : null;
    }
}