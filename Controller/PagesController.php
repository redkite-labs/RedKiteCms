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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\Page\PagesForm;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\Seo\SeoForm;
use Symfony\Component\HttpFoundation\Response;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class PagesController extends Controller
{
    public function indexAction()
    {
        $pagesForm = $this->get('form.factory')->create(new PagesForm($this->container->get('alphalemon_theme_engine.active_theme'), $this->container->get('alphalemon_theme_engine.themes')));
        $seoForm = $this->get('form.factory')->create(new SeoForm($this->createRepository('Language')));

        $params = array('base_template' => $this->container->getParameter('althemes.base_template'),
                        'pages' => $this->getPages(),
                        'pagesForm' => $pagesForm->createView(),
                        'pageAttributesForm' => $seoForm->createView());

        return $this->render('AlphaLemonCmsBundle:Pages:index.html.twig', $params);
    }

    public function loadSeoAttributesAction()
    {
        $values = array();
        $request = $this->get('request');
        $pageId = $request->get('pageId');
        $languageId = $request->get('languageId');
        if ($pageId != 'none' && $languageId != 'none') {
            $pageRepository = $this->createRepository('Page');
            $alPage = $pageRepository->fromPK($pageId);
            $values[] = array("name" => "#pages_pageName", "value" => $alPage->getPageName());
            $values[] = array("name" => "#pages_template", "value" => $alPage->getTemplateName());
            $values[] = array("name" => "#pages_isHome", "value" => $alPage->getIsHome());

            $seoRepository = $this->createRepository('Seo');
            $alSeo = $seoRepository->fromPageAndLanguage($languageId, $pageId);
            $values[] = array("name" => "#seo_attributes_permalink", "value" => ($alSeo != null) ? $alSeo->getPermalink() : '');
            $values[] = array("name" => "#seo_attributes_title", "value" => ($alSeo != null) ? $alSeo->getMetaTitle() : '');
            $values[] = array("name" => "#seo_attributes_description", "value" => ($alSeo != null) ? $alSeo->getMetaDescription() : '');
            $values[] = array("name" => "#seo_attributes_keywords", "value" => ($alSeo != null) ? $alSeo->getMetaKeywords() : '');
        }

        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function savePageAction()
    {
        try {
            $request = $this->get('request');
            if ('al_' === substr($request->get('pageName'), 0, 3)) {
                throw new \InvalidArgumentException("The prefix [ al_ ] is not permitted to avoid conflicts with the application internal routes");
            }

            $pageManager = $this->container->get('al_page_manager');
            if ($request->get('pageId') != 'none') {
                $pageRepository = $this->createRepository('Page');
                $alPage = $pageRepository->fromPk($request->get('pageId'));

                // Refreshes the page manager using the given page to update
                $pageContentsContainer = $pageManager->getTemplateManager()->getPageBlocks();
                if ($request->get('pageId') != "" && $request->get('pageId') != $pageContentsContainer->getIdPage()) {
                    $this->container->get('al_page_tree')->refresh($request->get('languageId'), $request->get('pageId'));
                    $pageManager->setTemplateManager($this->container->get('al_page_tree')->getTemplateManager());
                }
            } else {
                $alPage = null;
            }

            $pageManager->set($alPage);
            $template = ($request->get('templateName') != "none") ? $request->get('templateName') : '';
            $permalink = ($request->get('permalink') == "") ? $request->get('pageName') : $request->get('permalink');

            $values = array('PageName' => $request->get('pageName'),
                            'TemplateName' => $template,
                            'IsHome' => $request->get('isHome'),
                            'Permalink' => $permalink,
                            'MetaTitle' => $request->get('title'),
                            'MetaDescription' => $request->get('description'),
                            'MetaKeywords' => $request->get('keywords'));

            if ($pageManager->save($values)) {
                return $this->buildJSonHeader('The page has been successfully saved');
            } else {
                throw new \RuntimeException('The page has not been saved');
            }
        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode('404');

            return $this->render('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    public function deletePageAction()
    {
        try {
            $request = $this->get('request');
            $pageManager = $this->container->get('al_page_manager');
            $alPage = ($request->get('pageId') != 'none') ? $pageManager->getPageRepository()->fromPK($request->get('pageId')) : null;
            if ($alPage != null) {
                $pageManager->set($alPage);
                if ($request->get('pageId') != "none" && $request->get('languageId') != "none") {
                    $pageManager->getPageRepository()->startTransaction();
                    try {
                        $result = $this->container->get('al_seo_manager')->deleteSeoAttributesFromLanguage($request->get('languageId'), $request->get('pageId'));
                        if ($result) {
                            $result = $pageManager->getTemplateManager()->clearPageBlocks($request->get('languageId'), $request->get('pageId'));
                        }
                        if (false !== $result) {
                            $pageManager->getPageRepository()->commit();
                            $message = $this->get('translator')->trans('The page\'s attributes for the selected language has been successfully removed');
                        } else {
                            $pageManager->getPageRepository()->rollBack();
                            throw new \RuntimeException($this->container->get('translator')->trans('Nothig to delete with the given parameters'));
                        }
                    } catch (\Exception $ex) {
                        throw $ex;
                        $pageManager->getPageRepository()->rollBack();
                    }

                    /*
                    if ($result) {
                        $message = $this->get('translator')->trans('The page\'s attributes for the selected language has been successfully removed');
                    } else {
                        throw new \RuntimeException($this->container->get('translator')->trans('Nothig to delete with the given parameters'));
                    }*/
                } elseif ($request->get('pageId')) {
                    $result = $pageManager->delete();
                    if ($result) {
                        $message = $this->get('translator')->trans('The page has been successfully removed');
                    } else {
                        throw new \RuntimeException($this->container->get('translator')->trans('Nothing to delete with the given parameters'));
                    }
                } else {
                    throw new \RuntimeException($this->container->get('translator')->trans('To delete a page you must choose it'));
                }
            } else {
                throw new \RuntimeException($this->container->get('translator')->trans('Any page has been choosen for removing'));
            }

            return $this->buildJSonHeader($message);
        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode('404');

            return $this->render('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    protected function buildJSonHeader($message)
    {
        $pages = $this->getPages();

        $request = $this->getRequest();
        $values = array();
        $values[] = array("key" => "message", "value" => $message);
        $values[] = array("key" => "pages", "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Pages:pages_list.html.twig', array('pages' => $pages)));
        $values[] = array("key" => "pages_menu", "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Cms:menu_combo.html.twig', array('id' => 'al_pages_navigator', 'selected' => $request->get('page'), 'items' => $pages)));

        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    protected function getPages()
    {
        return ChoiceValues::getPages($this->createRepository('Page'));
    }

    private function createRepository($repository)
    {
        $factoryRepository = $this->container->get('alphalemon_cms.factory_repository');

        return $factoryRepository->createRepository($repository);
    }
}
