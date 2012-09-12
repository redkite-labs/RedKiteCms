<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\Block\FileBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerContainer;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;

/**
 * Description of AlBlockManagerFile
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerFile extends AlBlockManagerContainer
{
    protected $renderFile = false;

    public function getDefaultValue()
    {
        return array("HtmlContent" => 'Click to load a file');
    }

    public function getHideInEditMode()
    {
        return true;
    }

    public function getHtml()
    {
        $container = $this->container;
        $content = $this->alBlock->getHtmlContent();
        $defaultValue = $this->getDefaultValue();
        if ($content != $defaultValue["HtmlContent"]) {
            $assetPath = '@AlphaLemonCmsBundle/Resources/public/' . $container->getParameter('alpha_lemon_cms.upload_assets_dir');
            $asset = new AlAsset($container->get('kernel'),  $assetPath);

            return sprintf("{%% set file = kernel_root_dir ~ '/../web/bundles/alphalemonwebsite/%s' %%} {{ file_open(file) }}", $content);
            /*
            $assetPath = '@AlphaLemonCmsBundle/Resources/public/' . $container->getParameter('alpha_lemon_cms.upload_assets_dir');
            $asset = new AlAsset($container->get('kernel'),  $assetPath);

            return sprintf("{{ file_open('%s') }}", $asset->getRealPath() . '/' . $content);*/
        }

        return $content;
    }

    protected function formatHtmlCmsActive()
    {
        $container = $this->container;
        $content = $this->alBlock->getHtmlContent();
        $defaultValue = $this->getDefaultValue();
        if ($content != $defaultValue["HtmlContent"]) {
            $assetPath = '@AlphaLemonCmsBundle/Resources/public/' . $container->getParameter('alpha_lemon_cms.upload_assets_dir');
            $asset = new AlAsset($container->get('kernel'),  $assetPath);

            return @file_get_contents($asset->getRealPath() . '/' . $content);

            /* TODO
            $assetPath = $asset->getAbsolutePath() . '/' . $content;

            return sprintf('<a href="/%s" />%s</a>', $assetPath, basename($assetPath));
             */
        }

        return $content;
    }
}
