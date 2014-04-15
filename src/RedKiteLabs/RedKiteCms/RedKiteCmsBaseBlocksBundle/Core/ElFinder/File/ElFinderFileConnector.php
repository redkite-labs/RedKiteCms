<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBaseBlocksBundle\Core\ElFinder\File;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ElFinder\Base\ElFinderBaseConnector;

/**
 * ElFinderMarkdownConnector implements the ElFinder connector to handle files
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class ElFinderFileConnector extends ElFinderBaseConnector
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $filesFolder = $this->container->getParameter('file.base_folder') ;

        return $this->generateOptions($filesFolder, 'Files');
    }
}
