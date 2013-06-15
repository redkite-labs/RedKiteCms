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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks;

/**
 * Extends the AlPageBlocks class to load blocks of previous theme
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
class AlPageBlocksTemplateChanger extends AlPageBlocks
{
    protected function fetchBlocks()
    {
        return $this->blockRepository->retrieveContents(array(1, $this->idLanguage), array(1, $this->idPage), null, array(2, 3));
    }
}
