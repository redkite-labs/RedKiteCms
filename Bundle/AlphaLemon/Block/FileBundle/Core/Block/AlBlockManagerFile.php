<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\Block\FileBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerContainer;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;

/**
 * Description of AlBlockManagerFile
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerFile extends AlBlockManagerJsonBlockContainer
{
    public function getDefaultValue()
    {
        $value =
        '{
            "0" : {
                "file" : "Click to load a file",
                "opened" : "0"
            }
        }';

        return array(
            'Content' => $value,
        );
    }

    public function getHideInEditMode()
    {
        return true;
    }

    public function getHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock);
        $item = $items[0];
        $file = $item['file'];

        $deployBundle = $this->container->getParameter('alpha_lemon_theme_engine.deploy_bundle');
        $deployBundleAsset = new AlAsset($this->container->get('kernel'), $deployBundle);

        return ($item['opened'])
            ? sprintf("{%% set file = kernel_root_dir ~ '/../web/%s/%s' %%} {{ file_open(file) }}", $deployBundleAsset->getAbsolutePath(), $file)
            : $this->formatLink($file);
    }

    protected function formatHtmlCmsActive()
    {
        $items = $this->decodeJsonContent($this->alBlock);
        $item = $items[0];
        $file = $item['file'];

        return ($item['opened'])
            ? @file_get_contents($this->container->getParameter('alpha_lemon_cms.upload_assets_full_path') . '/' . $file)
            : $this->formatLink($file);
    }

    protected function getEditorWidth()
    {
        return 250;
    }

    private function formatLink($file)
    {
        $uploadsPath = $this->container->getParameter('alpha_lemon_cms.upload_assets_dir');

        return sprintf('<a href="/%s/%s" />%s</a>', $uploadsPath, $file, basename($file));
    }
}