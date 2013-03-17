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

namespace AlphaLemon\Block\ScriptBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerContainer;

/**
 * ScriptExtension
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerScript extends AlBlockManagerContainer
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {
        return array('Content' => '<p>This is a default script content</p>',
                     'InternalJavascript' => '',
                     'ExternalJavascript' => '');
    }

    protected function replaceHtmlCmsActive()
    {
        return array('RenderView' => array(
            'view' => 'ScriptBundle:Editor:scriptblock_editor.html.twig',            
            'options' => array(
                'blockManager' => $this,
                "jsFiles" => explode(",", $this->alBlock->getExternalJavascript()),
                "cssFiles" => explode(",", $this->alBlock->getExternalStylesheet()),
                'editor_settings' => $this->container->getParameter('script.editor_settings'),
            ),
        ));
        
        /*
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
        ));*/
    }

    /**
     * {@inheritdoc}
     *
    public function getHideInEditMode()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
    public function getReloadSuggested()
    {
        return true;
    }*/
}
