<?php
/*
 * This file is part of the BusinessDropCapBundle and it is distributed
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

namespace AlphaLemon\Block\FileBundle\Core\Listener;

use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\JsonBlock\RenderingItemEditorListener;

/**
 * Manipulates the block's editor response when the editor has been rendered
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class RenderingEditorListener extends RenderingItemEditorListener
{
    protected function configure()
    {
        return array(
            'blockClass' => '\AlphaLemon\Block\FileBundle\Core\Block\AlBlockManagerFile',
            'formClass' => '\AlphaLemon\Block\FileBundle\Core\Form\AlFileType',
        );
    }
    
    protected function formatContent($content)
    {
        $content['opened'] = (bool)$content['opened'];
        
        return $content;
    }
}
