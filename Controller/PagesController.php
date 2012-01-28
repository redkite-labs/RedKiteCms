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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AlphaLemon\AlphaLemonCmsBundle\Controller\CmsController;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\Page\PagesForm;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\PageAttributes\PageAttributesForm;
use Symfony\Component\HttpFoundation\Response;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageAttributeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class PagesController extends Controller
{
    public function indexAction()
    {
        $pagesForm = $this->get('form.factory')->create(new PagesForm($this->container));
        $pageAttributesForm = $this->get('form.factory')->create(new PageAttributesForm($this->container));
        
        $params = array('base_template' => $this->container->getParameter('althemes.base_template'),
                        'pages' => ChoiceValues::getPages($this->container),
                        'pagesForm' => $pagesForm->createView(),
                        'pageAttributesForm' => $pageAttributesForm->createView());
        
        return $this->render('AlphaLemonCmsBundle:Pages:index.html.twig', $params);
    }

    public function loadPageAttributesAction()
    {
        $values = array();
        $request = $this->get('request');
        $pageId = $request->get('pageId');
        $languageId = $request->get('languageId');
        if($pageId != 'none' && $languageId != 'none')
        {
            $alPage = AlPageQuery::create()
                            ->filterByToDelete(0)
                            ->findPK($pageId);
            $values[] = array("name" => "#pages_pageName", "value" => $alPage->getPageName());
            $values[] = array("name" => "#pages_template", "value" => $alPage->getTemplateName());
            $values[] = array("name" => "#pages_isHome", "value" => $alPage->getIsHome());

            $alPageAttributes = AlPageAttributeQuery::create()
                            ->filterByPageId($pageId)
                            ->filterByLanguageId($languageId)
                            ->filterByToDelete(0)
                            ->findOne();
            $values[] = array("name" => "#page_attributes_permalink", "value" => ($alPageAttributes != null) ? $alPageAttributes->getPermalink() : '');
            $values[] = array("name" => "#page_attributes_title", "value" => ($alPageAttributes != null) ? $alPageAttributes->getMetaTitle() : '');
            $values[] = array("name" => "#page_attributes_description", "value" => ($alPageAttributes != null) ? $alPageAttributes->getMetaDescription() : '');
            $values[] = array("name" => "#page_attributes_keywords", "value" => ($alPageAttributes != null) ? $alPageAttributes->getMetaKeywords() : '');
        }

        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function savePageAction()
    {
        try
        {
            $request = $this->get('request');
            if('al_' === substr($request->get('pageName'), 0, 3))
            {
                throw new InvalidArgumentException("The prefix [ al_ ] is not permitted to avoid conflicts with the application internal routes");
            }
            
            $alPage = ($request->get('pageId') != 'none') ? AlPageQuery::create()->findPk($request->get('pageId')) : null; 
            $template = ($request->get('templateName') != "none") ? $request->get('templateName') : '';
            $permalink = ($request->get('permalink') == "") ? $request->get('pageName') : $request->get('permalink');
            
            $parameters = array('pageName' => $request->get('pageName'),
                                'template' => $template,
                                'isHome' => $request->get('isHome'),
                                'permalink' => $permalink,
                                'title' => $request->get('title'),
                                'description' => $request->get('description'),
                                'keywords' => $request->get('keywords')); 
            $pageManager = $this->container->get('al_page_manager');
            if(null !== $alPage)
            {
                $parameters['languageId'] = $request->get('languageId');
                $pageManager->set($alPage);
            }
            
            if(true === $pageManager->save($parameters))
            {
                return $this->buildJSonHeader('The page has been successfully saved');
            }
            else
            {
                throw new \RuntimeException('The page has not been saved');
            }
        }
        catch(\Exception $e)
        {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    public function deletePageAction()
    {
        try
        {
            $request = $this->get('request');
            $alPage = ($request->get('pageId') != 'none') ? AlPageQuery::create()->findPk($request->get('pageId')) : null;
            if($alPage != null)
            {
                $pageManager = $this->container->get('al_page_manager');
                $pageManager->set($alPage);
                if($request->get('pageId') != "none" && $request->get('languageId') != "none")
                {
                    $result = $pageManager->deleteBlocksAndPageAttributes($request->get('languageId'));
                    if($result)
                    {
                        $message = $this->get('translator')->trans('The page\'s attributes for the selected language has been successfully removed');
                    }
                    else
                    {
                        throw new \RuntimeException($this->container->get('translator')->trans('Nothig to delete with the given parameters'));
                    }
                }
                elseif($request->get('pageId'))
                {
                    $result = $pageManager->delete();
                    if($result)
                    {
                        $message = $this->get('translator')->trans('The page has been successfully removed');
                    }
                    else
                    {
                        throw new \RuntimeException($this->container->get('translator')->trans('Nothig to delete with the given parameters'));
                    }
                }
                else
                {
                    throw new \RuntimeException($this->container->get('translator')->trans('To delete a page you must choose it'));
                }
            }
            else
            {
                throw new \RuntimeException($this->container->get('translator')->trans('Any page has been choosen for removing'));
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

    protected function buildJSonHeader($message)
    {
        $pages = ChoiceValues::getPages($this->container);

        $request = $this->getRequest(); 
        $values = array(); 
        $values[] = array("key" => "message", "value" => $message);
        $values[] = array("key" => "pages", "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Pages:pages_list.html.twig', array('pages' => $pages)));
        $values[] = array("key" => "pages_menu", "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Cms:menu_combo.html.twig', array('id' => 'al_pages_navigator', 'selected' => $request->get('page'), 'items' => $pages)));
        
        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
}

