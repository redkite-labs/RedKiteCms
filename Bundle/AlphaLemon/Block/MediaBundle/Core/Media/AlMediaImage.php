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

namespace AlphaLemon\Block\MediaBundle\Core\Media;

/**
 * AlMediaImage
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlMediaImage extends AlMediaExt
{
    protected $skeleton='<img src="%s" width="%s" height="%s" alt="%s" title="%s" />';

    public function render()
    {
        $info = pathinfo($this->realSrcPath);
        $title = ucfirst(str_replace('.' . $info['extension'], '', $info['basename']));
        
        return sprintf($this->skeleton, $this->absoluteSrcPath, $this->width, $this->height, $this->src, $title);
    }
}