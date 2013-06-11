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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\InlineTextBlock;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;

/**
 * AlBlockManagerJson is the base object deputated to handle a json content
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
abstract class AlBlockManagerInlineTextBlock extends AlBlockManager
{
    protected function editInline()
    {
        return true;
    }
}
