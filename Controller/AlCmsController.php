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

use AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use AlphaLemon\ThemeEngineBundle\Core\Rendering\Controller\BaseFrontendController;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;
use Symfony\Component\HttpFoundation\Request;

/**
 * Implements the controller to load AlphaLemon CMS
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlCmsController extends BaseFrontendController
{
    protected $kernel = null;
    protected $factoryRepository = null;
    protected $pageRepository = null;
    protected $languageRepository = null;

    public function showAction()
    {
        $request = $this->container->get('request');
        $this->kernel = $this->container->get('kernel');
        $pageTree = $this->container->get('alpha_lemon_cms.page_tree');
        $isSecure = (null !== $this->get('security.context')->getToken()) ? true : false;

        $this->factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
        $this->pageRepository = $this->factoryRepository->createRepository('Page');

        $params = array(
            'template' => 'AlphaLemonCmsBundle:Cms:welcome.html.twig',
            'enable_yui_compressor' => $this->container->getParameter('alpha_lemon_cms.enable_yui_compressor'),
            'templateStylesheets' => null,
            'templateJavascripts' => null,
            'available_blocks' => null,
            'internal_stylesheets' => null,
            'internal_javascripts' => null,
            'skin_path' => $this->getSkin(),
            'is_secure' => $isSecure,
            'pages' => ChoiceValues::getPages($this->pageRepository),
            'languages' => ChoiceValues::getLanguages($this->languageRepository),
            'page' => 0,
            'language' => 0,
            'available_languages' => $this->container->getParameter('alpha_lemon_cms.available_languages'),
            'frontController' => $this->getFrontcontroller($request),
        );

        if (null !== $pageTree) {
            $template = $this->findTemplate($pageTree);
            $params = array_merge($params, array(
                'metatitle' => $pageTree->getMetaTitle(),
                'metadescription' => $pageTree->getMetaDescription(),
                'metakeywords' => $pageTree->getMetaKeywords(),
                'internal_stylesheets' => $pageTree->getInternalStylesheets(),
                'internal_javascripts' => $pageTree->getInternalJavascripts(),
                'template' => $template,
                'page' => (null != $pageTree->getAlPage()) ? $pageTree->getAlPage()->getId() : 0,
                'language' => (null != $pageTree->getAlLanguage()) ? $pageTree->getAlLanguage()->getId() : 0,
                'page_name' => (null != $pageTree->getAlPage()) ? $pageTree->getAlPage()->getPageName() : '',
                'language_name' => (null != $pageTree->getAlLanguage()) ? $pageTree->getAlLanguage()->getLanguageName() : '',
                'available_blocks' => $this->container->get('alpha_lemon_cms.block_manager_factory')->getBlocks(),
                'base_template' => $this->container->getParameter('alpha_lemon_theme_engine.base_template'),
                'templateStylesheets' => $pageTree->getExternalStylesheets(),
                'templateJavascripts' => $this->fixAssets($pageTree->getExternalJavascripts()),
                )
            );
        } else {
            $this->container->get('session')->setFlash('message', 'The requested page has not been loaded');
        }

        $response = $this->render('AlphaLemonCmsBundle:Cms:index.html.twig', $params);

        return $this->dispatchEvents($request, $response);
    }

    /**
     * Overrides the base method to replace the permalink when it is used instad
     * of the page name
     *
     * @param type $request
     */
    protected function dispatchCurrentPageEvent(Request $request)
    {
        $pageName = $request->get('page');
        $seoRepository = $this->factoryRepository->createRepository('Seo');
        $seo = $seoRepository->fromPermalink($pageName);
        if (null !== $seo) {
            $page = $this->pageRepository->fromPk($seo->getPageId());
            $pageName = $page->getPageName();
        }

        $eventName = sprintf('page_renderer.before_%s_rendering', $pageName);
        $this->dispatcher->dispatch($eventName, $this->event);
    }

    protected function findTemplate($pageTree)
    {
        $templateTwig = 'AlphaLemonCmsBundle:Cms:welcome.html.twig';
        if (null !== $template = $pageTree->getTemplate()) {
            $themeName = $template->getThemeName();
            $templateName = $template->getTemplateName();

            $asset = new AlAsset($this->kernel, $themeName);
            $themeFolder = $asset->getRealPath();
            if (false === $themeFolder || !is_file($themeFolder .'/Resources/views/Theme/' . $templateName . '.html.twig')) {
                $this->container->get('session')->setFlash('message', 'The template assigned to this page does not exist. This appens when you change a theme with a different number of templates from the active one. To fix this issue you shoud activate the previous theme again and change the pages which cannot be rendered by this theme');

                return $templateTwig;
            }

            if ($themeName != "" && $templateName != "") {
                $this->kernelPath = $this->container->getParameter('kernel.root_dir');
                $templateTwig = (is_file(sprintf('%s/Resources/views/%s/%s.html.twig', $this->kernelPath, $themeName, $templateName))) ? sprintf('::%s/%s.html.twig', $themeName, $templateName) : sprintf('%s:Theme:%s.html.twig', $themeName, $templateName);
            }
        }

        return $templateTwig;
    }

    /**
     * Workaround due to static assetic javascripts/stylesheets declaration
     */
    protected function fixAssets(array $assets)
    {
        $ignore = array('jquery-last.min.js',
                        'jquery-ui.min.js',
                        'jquery.easing-1.3.js',
                        'jquery.metadata.js',
                        'jquery.ui.position.js',);
        foreach ($assets as $key => $asset) {
            if (in_array(basename($asset), $ignore)) {
                unset($assets[$key]);
            }
        }

        return $assets;
    }

    protected function getSkin()
    {
        $asset = new AlAsset($this->kernel, '@AlphaLemonCmsBundle');

        return $asset->getAbsolutePath() . '/css/skins/' . $this->container->getParameter('alpha_lemon_cms.skin');
    }
    
    protected function getFrontcontroller(Request $request = null)
    {
        if (null === $request) {
            $request = $this->container->get('request');
        }
        
        return $request->getBaseUrl() . '/';
    }
}