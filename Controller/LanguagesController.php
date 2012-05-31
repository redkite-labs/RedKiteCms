<?php
/*
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

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AlphaLemon\AlphaLemonCmsBundle\Controller\CmsController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Finder;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\Language\LanguagesForm;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Language\AlLanguageManager;

class LanguagesController extends Controller
{
    public function indexAction()
    {
        if (!extension_loaded('intl')) {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => 'To manage languages you must enable the intl extension in your php.ini file. Operation aborted.'), $response);
        }

        $languagesForm = new LanguagesForm($this->container);
        $form = $this->get('form.factory')->create($languagesForm);

        $params = array('base_template' => $this->container->getParameter('althemes.base_template'),
                        'languages' => ChoiceValues::getLanguages($this->container->get('language_model')),
                        'form' => $form->createView());
        return $this->render('AlphaLemonCmsBundle:Languages:index.html.twig', $params);
    }

    public function saveLanguageAction()
    {
        try
        {
            $request = $this->get('request');
            $languageManager = $this->container->get('al_language_manager');
            $languageModel = $languageManager->getLanguageModel();
            $alLanguage = ($request->get('idLanguage') != 'none') ? $languageModel->fromPk($request->get('idLanguage')) : null;
            if(null !== $alLanguage)
            {
                $languageManager->set($alLanguage);
            }
            $parameters = array('isMain' => $request->get('isMain'),
                                'language' => $request->get('newLanguage'));
            $message = ($languageManager->save($parameters)) ? 'The language has been successfully saved' : 'The language has not been saved';

            return $this->buildJSonHeader($message);
        }
        catch(\Exception $e)
        {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    public function deleteLanguageAction()
    {
        try
        {
            $request = $this->get('request');
            $alLanguage = ($request->get('languageId') != 'none') ? AlLanguageQuery::create()->findPk($request->get('languageId')) : null;

            if($alLanguage != null)
            {
                $languageManager = $this->container->get('al_language_manager');
                $languageManager->set($alLanguage);
                $result = $languageManager->delete();
                if($result)
                {
                    $message = $this->get('translator')->trans('The language has been successfully removed');
                }
                else if(null === $result)
                {
                    throw new \RuntimeException($this->container->get('translator')->trans('The main language could not be deleted'));
                }
                else
                {
                    throw new \RuntimeException($this->container->get('translator')->trans('Nothing to delete with the given parameters'));
                }
            }
            else
            {
                throw new \RuntimeException($this->container->get('translator')->trans('Any language has been choosen for removing'));
            }

            return $this->buildJSonHeader($message);
        }
        catch(\Exception $e)
        {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    public function loadLanguageAttributesAction()
    {
        $values = array();
        $request = $this->get('request');
        $language = $request->get('language');
        if($language != 'none')
        {
            $alLanguage = AlLanguageQuery::create()
                            ->filterByToDelete(0)
                            ->findPK($language);
            $values[] = array("name" => "#languages_language", "value" => $alLanguage->getLanguage());
            $values[] = array("name" => "#languages_isMain", "value" => $alLanguage->getMainLanguage());
        }

        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    protected function buildJSonHeader($message)
    {
        $languages = ChoiceValues::getLanguages($this->container);

        $values = array();
        $values[] = array("key" => "message", "value" => $message);
        $values[] = array("key" => "languages", "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Languages:languages_list.html.twig', array('languages' => $languages)));
        $values[] = array("key" => "languages_menu", "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Cms:menu_combo.html.twig', array('id' => 'al_languages_navigator', 'selected' => $this->getRequest()->get('language'), 'items' => $languages)));

        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}

