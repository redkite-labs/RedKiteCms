<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Listener\Language;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface;

/**
 * Listen to the onBeforeDeleteLanguageCommit event to delete the blocks which
 * belongs the language to remove
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class DeleteLanguageBlocksListener extends Base\DeleteLanguageBaseListener
{
    private $blockManager;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerInterface $blockManager
     */
    public function __construct(AlBlockManagerInterface $blockManager)
    {
        $this->blockManager = $blockManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpSourceObjects()
    {
        $language = $this->languageManager->get();
        if (null === $language) {
            return null;
        }

        return $this->blockManager
                    ->getBlockRepository()
                    ->fromLanguageId($language->getId())
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function delete($object)
    {
        $result = $this->blockManager
                    ->set($object)
                    ->delete();

        return $result;
    }
}
