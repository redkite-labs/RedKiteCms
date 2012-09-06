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
            $assetPath = '@AlphaLemonCmsBundle/Resources/public/' . $container->getParameter('alphalemon_cms.upload_assets_dir');
            $asset = new AlAsset($container->get('kernel'),  $assetPath);

            $content = str_replace('{{', '{ {' , $content);
            $content = str_replace('}}', '} }' , $content);
            $content = str_replace('{%', '{ %' , $content);
            $content = str_replace('%}', '% }' , $content);

            return file_get_contents($asset->getRealPath() . '/' . $content);

            /* TODO
            $assetPath = $asset->getAbsolutePath() . '/' . $content;

            return sprintf('<a href="/%s" />%s</a>', $assetPath, basename($assetPath));
             */
        }

        return $content;
    }
}
