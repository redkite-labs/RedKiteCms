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
 * AlMediaGeneric
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlMediaGeneric extends AlMedia
{
    protected $_skeleton = '<a href="%s">%s</a>';

    public function render()
    {
        return sprintf($this->_skeleton, $this->_absoluteSrcPath, $this->_src);
    }
}