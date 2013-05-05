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
    
    protected function renderHtml()
    {
        return array('RenderView' => array(
            'view' => 'ScriptBundle:Content:script.html.twig',
        ));
    }
    
    public function editorParameters()
    {
        return array(
            "template" => "ScriptBundle:Editor:_editor.html.twig",
            "title" => "Script editor",
            "blockManager" => $this,
            "jsFiles" => explode(",", $this->alBlock->getExternalJavascript()),
            "cssFiles" => explode(",", $this->alBlock->getExternalStylesheet())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getHideInEditMode()
    {
        return true;
    }
}
