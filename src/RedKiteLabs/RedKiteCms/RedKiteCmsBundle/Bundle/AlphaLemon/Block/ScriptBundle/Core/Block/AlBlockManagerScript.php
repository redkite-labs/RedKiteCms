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

namespace AlphaLemon\Block\ScriptBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;

/**
 * ScriptExtension
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerScript extends AlBlockManager
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {
        return array('Content' => '',
                     'InternalJavascript' => '',
                     'ExternalJavascript' => '');
    }

    /**
     * {@inheritdoc}
     */
    protected function formatHtmlCmsActive()
    {
        $content = $this->alBlock->getContent();
        if (strpos($content, '<script') !== false) return "A script content is not rendered in editor mode";
        
        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getHideInEditMode()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getReloadSuggested()
    {
        return true;
    }
}
