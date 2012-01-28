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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\AlMenuBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;

/**
 * AlBlockManagerMenu
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlBlockManagerMenu extends AlBlockManager
{
 
    /**
     * @see AlBlockManager::getDefaultValue()
     *
     */
    public function getDefaultValue()
    {
        return array("HtmlContent" => "<ul><li>Link 1</li><li>Link 2</li><li>Link 3</li></ul>");
    }
}
