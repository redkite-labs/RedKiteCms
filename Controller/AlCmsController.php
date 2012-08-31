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
use Symfony\Component\Finder\Finder;

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlPageQuery;

use AlphaLemon\ThemeEngineBundle\Core\Repository\AlThemeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;

use AlphaLemon\AlphaLemonCmsBundle\Core\Assetic\AlAsseticDynamicFileManager\AlAsseticDynamicFileManagerJs;
use AlphaLemon\AlphaLemonCmsBundle\Core\Assetic\AlAsseticDynamicFileManager\AlAsseticDynamicFileManagerCss;


use AlphaLemon\ThemeEngineBundle\Core\Event\PageRenderer\BeforePageRenderingEvent;
use AlphaLemon\ThemeEngineBundle\Core\Event\PageRendererEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\ThemeEngineBundle\Core\Rendering\Controller\BaseFrontendController;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;

/**
 * Implements the controller to load AlphaLemon CMS
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlCmsController extends BaseFrontendController
{
    private $cmsAssets = array();
    private $kernel = null;

    public function showAction()
    {
        $request = $this->container->get('request');
        $this->kernel = $this->container->get('kernel');
        $pageTree = $this->container->get('al_page_tree');
        $isSecure = (null !== $this->get('security.context')->getToken()) ? true : false;
        $asset = new AlAsset($this->kernel, '@AlphaLemonCmsBundle');
        $skin = $asset->getAbsolutePath() . '/css/skins/' . $this->container->getParameter('alcms.skin');
        //$skin = AlToolkit::retrieveBundleWebFolder($this->kernel, 'AlphaLemonCmsBundle') . '/css/skins/' . $this->container->getParameter('alcms.skin');
        $factoryRepository = $this->container->get('alphalemon_cms.factory_repository');
        $languageRepository = $factoryRepository->createRepository('Language');
        $pageRepository = $factoryRepository->createRepository('Page');

        $params = array('template' => 'AlphaLemonCmsBundle:Cms:welcome.html.twig',
                        'templateStylesheets' => null,
                        'templateJavascripts' => null,
                        'available_blocks' => null,
                        'internal_stylesheets' => null,
                        'internal_javascripts' => null,
                        'skin_path' => $skin,
                        'is_secure' => $isSecure,
                        'pages' => ChoiceValues::getPages($pageRepository),
                        'languages' => ChoiceValues::getLanguages($languageRepository),
                        'page' => 0,
                        'language' => 0,
                        'available_languages' => $this->container->getParameter('alcms.available_languages'),
                        'frontController' => sprintf('/%s.php/', $this->kernel->getEnvironment()),);

        if(null !== $pageTree)
        {
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
                                'available_blocks' => $this->container->get('alphalemon_cms.block_manager_factory')->getBlocks(),
                                'base_template' => $this->container->getParameter('althemes.base_template'),
                                'templateStylesheets' => $this->locateAssets($pageTree->getExternalStylesheets()),
                                'templateJavascripts' => $this->locateAssets($pageTree->getExternalJavascripts()),
                                ));
        }
        else
        {
            $this->get('session')->setFlash('message', 'The requested page has not been loaded.');
        }

        $response = $this->render('AlphaLemonCmsBundle:Cms:index.html.twig', $params);
        $response = $this->dispatchEvents($request, $response);

        return $response;
    }

    private function findTemplate(AlPageTree $pageTree)
    {
        $templateTwig = 'AlphaLemonCmsBundle:Cms:welcome.html.twig';
        if(null !== $template = $pageTree->getTemplate())
        {
            $themeName = $template->getThemeName();
            $templateName = $template->getTemplateName();

            //$themeFolder = AlToolkit::locateResource($this->kernel, $themeName);
            $asset = new AlAsset($this->kernel, $themeName);
            $themeFolder = $asset->getRealPath();
            if(false === $themeFolder || !is_file($themeFolder .'/Resources/views/Theme/' . $templateName . '.html.twig'))
            {
                $this->get('session')->setFlash('message', 'The template assigned to this page does not exist. This appens when you change a theme with a different number of templates from the active one. To fix this issue you shoud activate the previous theme again and change the pages which cannot be rendered by this theme');

                return $templateTwig;
            }

            if($themeName != "" && $templateName != "")
            {
                $this->kernelPath = $this->container->getParameter('kernel.root_dir');
                $templateTwig = (is_file(sprintf('%s/Resources/views/%s/%s.html.twig', $this->kernelPath, $themeName, $templateName))) ? sprintf('::%s/%s.html.twig', $themeName, $templateName) : sprintf('%s:Theme:%s.html.twig', $themeName, $templateName);
            }
        }

        return $templateTwig;
    }

    private function locateAssets(array $assets)
    {
        $located = array();
        foreach($assets as $asset)
        {
            $filename = basename($asset);
            if(in_array($filename, $this->cmsAssets))
            {
                continue;
            }

            $currentAsset = $asset;

            // Checks if the assets is given with a relative path
            if(false !== strpos($currentAsset, 'bundles') || false !== strpos($currentAsset, '@'))
            {
                preg_match('/^@([\w]+Bundle)\/Resources\/public\/([\w\/\.-]+)/', $currentAsset, $match);
                if(!empty($match))
                {
                        //$currentAsset = AlToolkit::retrieveBundleWebFolder($this->kernel, $match[1]) . '/' . $match[2];
                        $asset = new AlAsset($this->kernel, $match[1] . '/' . $match[2]);
                        $currentAsset = $asset->getRealPath();
                }

                //$currentAsset = AlToolkit::normalizePath($currentAsset);
                $located[] =  $currentAsset;
            }
        }

        return $located;
    }
}

