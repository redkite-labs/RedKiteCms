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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Finder;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;;


/**
 * TinyMCEController
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class TinyMCEController extends Controller
{
    public function createImagesListAction()
    {
        $cmsBundleAsset = new AlAsset($this->container->get('kernel'), 'AlphaLemonCmsBundle');
        $cmsAssetsFolder = $this->container->getParameter('kernel.root_dir') . '/../' . $this->container->getParameter('alphalemon_cms.web_folder') . $cmsBundleAsset->getAbsolutePath() . '/' . $this->container->getParameter('alphalemon_cms.upload_assets_dir');

        $mceImages = array();
        $mediaFileTypes = array('*.jpg', '*.jpeg', '*.png', '*.gif', '*.tif');
        $finder = new Finder();
        $finder = $finder->directories()->files()->exclude('.tmb')->exclude('.thumbnails')->sortByName();
        foreach($mediaFileTypes as $mediaFileType)
        {
            $finder = $finder->name(trim($mediaFileType));
        }
        $imagesFiles = $finder->in($cmsAssetsFolder . '/' . $this->container->getParameter('alphalemon_cms.deploy_bundle.media_folder'));

        foreach($imagesFiles as $imagesFile)
        {
            $absoluteFolderPath = '/' . $this->container->getParameter('alphalemon_cms.upload_assets_dir') . \str_replace($cmsAssetsFolder, '', dirname($imagesFile));
            $mceImages[] = sprintf("[\"%1\$s\", \"%2\$s/%1\$s\"]", basename($imagesFile), $cmsBundleAsset->getAbsolutePath() . $absoluteFolderPath);
        }
        $list = 'var tinyMCEImageList = new Array(' . implode(",", $mceImages) . ');';

        return $this->setResponse($list);
    }

    public function createLinksListAction()
    {
        $seoRepository = $this->container->get('seo_model');
        $seoAttributes = $seoRepository->fromLanguageName($this->getRequest()->get('language'));

        $mcsLinks = array();
        foreach($seoAttributes as $seoAttribute)
        {
            $permalink = $seoAttribute->getPermalink();
            $mcsLinks[] = sprintf("[\"%1\$s\", \"%1\$s\"]",$permalink, $permalink); //%2\$s/ , 'en'
        }
        $list = 'var tinyMCELinkList = new Array(' . implode(",", $mcsLinks) . ');';

        return $this->setResponse($list);
    }

    private function setResponse($content)
    {
        $response = new Response();
        $response->setContent($content);

        return $response;
    }
}

