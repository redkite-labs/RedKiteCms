<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\Block\FileBundle\Core\Block;

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
                "opened" : false
            }
        }';

        return array(
            'Content' => $value,
        );
    }
/*
    public function getHideInEditMode()
    {
        return true;
    }*/

    public function getHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock);
        $item = $items[0];
        $file = $item['file'];

        $deployBundle = $this->container->getParameter('alpha_lemon_theme_engine.deploy_bundle');
        $deployBundleAsset = new AlAsset($this->container->get('kernel'), $deployBundle);

        return "AlBlockManagerFile->FIX ME!";
        
        /*
        return ($item['opened'])
            ? sprintf("{%% set file = kernel_root_dir ~ '/../web/%s/%s' %%} {{ file_open(file) }}", $deployBundleAsset->getAbsolutePath(), $file)
            : $this->formatLink($file);*/
    }

    protected function replaceHtmlCmsActive()
    {
        $items = $this->decodeJsonContent($this->alBlock);
        $item = $items[0];
        $file = $item['file'];
        
        $item['opened'] = array_key_exists('opened', $item) ? filter_var($item['opened'], FILTER_VALIDATE_BOOLEAN) : false; 
        
        $options = ($item['opened'])
            ? 
                array(
                    'folder' => $this->container->getParameter('alpha_lemon_cms.upload_assets_dir'),
                    'filename' => $file,
                )
            :
                array(
                    'folder' => $this->container->getParameter('alpha_lemon_cms.upload_assets_dir'),
                    'filename' => $file,
                    'filepath' => basename($file),
                )
        ;
        
        $formClass = $this->container->get('file.form');
        $buttonForm = $this->container->get('form.factory')->create($formClass, $item);        
        $options = array_merge($options, array('form' => $buttonForm->createView()));
        
        return array('RenderView' => array(
            'view' => 'FileBundle:Editor:fileblock_editor.html.twig',
            'options' => $options,
        ));
    }
}