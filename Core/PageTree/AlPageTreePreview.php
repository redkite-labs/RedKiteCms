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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\PageTree;

use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;

/**
 * Extends the bas AlPageTree object to fetch page information from the database
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageTreePreview extends AlPageTree
{
    protected $blockManagers = array();


    public function addBlockManager($slotName, $blockManager)
    {
        return $this->blockManagers[$slotName][] = $blockManager;
    }

    /**
     * Returns the page's block managers
     *
     * @return array
     */
    public function getBlockManagers($slotName)
    {
        return $this->blockManagers[$slotName];
    }
}