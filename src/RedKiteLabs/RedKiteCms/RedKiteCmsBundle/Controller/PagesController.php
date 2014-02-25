<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

use RedKiteLabs\RedKiteCmsBundle\Core\Content\AlContentManagerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\RuntimeException;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\Page\PagesForm;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\Seo\SeoForm;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PagesController extends Base\BaseController
{
    public function indexAction(Request $request)
    {
        $pagesForm = $this->createForm(new PagesForm($this->container->get('red_kite_cms.active_theme'), $this->container->get('red_kite_labs_theme_engine.themes')));
        $seoForm = $this->createForm(new SeoForm($this->createRepository('Language')));

        $params = array(
            'base_template' => $this->container->getParameter('red_kite_labs_theme_engine.base_template'),
            'pages' => $this->getPages(),
            'languages' => ChoiceValues::getLanguages($this->createRepository('Language'), false),
            'pagesForm' => $pagesForm->createView(),
            'pageAttributesForm' => $seoForm->createView(),
            'active_page' => $request->get('page'),
        );

        return $this->render('RedKiteCmsBundle:Pages:panel.html.twig', $params);
    }

    public function loadSeoAttributesAction(Request $request)
    {
        $values = array();
        $pageId = $request->get('pageId');
        $languageId = $request->get('languageId');
        if ($pageId != 'none' && $languageId != 'none') {
            $pageRepository = $this->createRepository('Page');
            $alPage = $pageRepository->fromPK($pageId);
            $values[] = array("name" => "#pages_pageName", "value" => $alPage->getPageName());
            $values[] = array("name" => "#pages_template", "value" => $alPage->getTemplateName());
            $values[] = array("name" => "#pages_isHome", "value" => $alPage->getIsHome());
            $values[] = array("name" => "#pages_isPublished", "value" => $alPage->getIsPublished());

            $seoRepository = $this->createRepository('Seo');
            $alSeo = $seoRepository->fromPageAndLanguage($languageId, $pageId);
            $values[] = array("name" => "#seo_attributes_permalink", "value" => ($alSeo != null) ? $alSeo->getPermalink() : '');
            $values[] = array("name" => "#seo_attributes_title", "value" => ($alSeo != null) ? $alSeo->getMetaTitle() : '');
            $values[] = array("name" => "#seo_attributes_description", "value" => ($alSeo != null) ? $alSeo->getMetaDescription() : '');
            $values[] = array("name" => "#seo_attributes_keywords", "value" => ($alSeo != null) ? $alSeo->getMetaKeywords() : '');
            $values[] = array("name" => "#seo_attributes_sitemapChangeFreq", "value" => ($alSeo != null) ? $alSeo->getSitemapChangefreq() : '');
            $values[] = array("name" => "#seo_attributes_sitemapPriority", "value" => ($alSeo != null) ? $alSeo->getSitemapPriority() : '');
        }

        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function savePageAction(Request $request)
    {
        if ('al_' === substr($request->get('pageName'), 0, 3)) {
            throw new InvalidArgumentException('pages_controller_al_prefix_not_permitted');
        }

        $alPage = null;
        if ((int) $request->get('pageId') != 0 && (int) $request->get('languageId') != 0) {
            $pageRepository = $this->createRepository('Page');
            $alPage = $pageRepository->fromPk($request->get('pageId'));
        }

        $activeTheme = $this->container->get('red_kite_cms.active_theme');
        $theme = $activeTheme->getActiveTheme();
        $templateManager = $this->container
            ->get('red_kite_cms.template_manager')
            ->refresh($theme->getThemeSlots(), $theme->getTemplate($request->get('templateName')), $this->container->get('red_kite_cms.page_blocks'))
        ;

        $pageManager = $this->container->get('red_kite_cms.page_manager');
        $pageManager->set($alPage)->setTemplateManager($templateManager);
        $templateName = ($request->get('templateName') != "none") ? $request->get('templateName') : '';
        $permalink = ($request->get('permalink') == "") ? $request->get('pageName') : $request->get('permalink');

        $values = array(
            'PageName' => $request->get('pageName'),
            'TemplateName' => $templateName,
            'IsHome' => $request->get('isHome'),
            'IsPublished' => $request->get('isPublished'),
            'Permalink' => $permalink,
            'MetaTitle' => $request->get('title'),
            'MetaDescription' => $request->get('description'),
            'MetaKeywords' => $request->get('keywords'),
            'SitemapChangefreq' => $request->get('sitemapChangeFreq'),
            'SitemapPriority' => $request->get('sitemapPriority'),
        );

        if ( ! $pageManager->save($values)) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('pages_controller_page_not_saved');
            // @codeCoverageIgnoreEnd
        }

        $page = $pageManager->getPageRepository()->fromPageName($request->get('page'));

        return $this->buildJSonHeader($request, $this->translate('pages_controller_page_saved'), $page);
    }

    public function deletePageAction(Request $request)
    {
        $pageManager = $this->container->get('red_kite_cms.page_manager');

        $alPage = null;
        if ($request->get('pageId') != 'none') {
            $alPage = $pageManager->getPageRepository()->fromPK($request->get('pageId'));
        }

        if (null === $alPage) {
            throw new RuntimeException('pages_controller_any_page_selected');
        }

        $pageManager->set($alPage);

        return $this->removePage($request, $pageManager);
    }

    protected function removePage(Request $request, AlContentManagerInterface $pageManager)
    {
        $result = $pageManager->delete();
        if (! $result) {
           // @codeCoverageIgnoreStart
           throw new RuntimeException('pages_controller_nothing_to_delete');
            // @codeCoverageIgnoreEnd
        }

        return $this->buildJSonHeader($request, $this->translate('pages_controller_page_removed'), $pageManager->get());
    }

    protected function buildJSonHeader(Request $request, $message, $page = null)
    {
        $pages = $pagesList = $this->getPages();
        unset($pagesList['none']);

        $permalinks = ChoiceValues::getPermalinks($this->createRepository('Seo'), $request->get('_locale'));

        $values = array();
        $values[] = array("key" => "message", "value" => $message);
        $values[] = array("key" => "pages_list", "value" => $this->renderView('RedKiteCmsBundle:Pages:pages_list.html.twig', array(
            'pages' => $pagesList,
            'active_page' => $request->get('page'),
            'languages' => ChoiceValues::getLanguages($this->createRepository('Language'), false),
        )));
        $values[] = array("key" => "permalinks", "value" => $this->renderView('RedKiteCmsBundle:Partials:_permalink_select.html.twig', array(
            'permalinks' => $permalinks,)
        ));
        $values[] = array(
            "key" => "pages",
            "value" => $this->renderView('RedKiteCmsBundle:Partials:_dropdown_menu.html.twig', array(
                'id' => 'al_pages_navigator',
                'type' => 'al_page_item',
                'value' => (null !== $page) ? $page->getId() : 0,
                'text' => $request->get('page'),
                'items' => $pages,
            )
        ));

        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    protected function getPages()
    {
        return ChoiceValues::getPages($this->createRepository('Page'), false);
    }
}
