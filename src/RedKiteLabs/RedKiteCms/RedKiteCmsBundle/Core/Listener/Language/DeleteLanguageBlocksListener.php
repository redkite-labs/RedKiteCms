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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Language;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;

/**
 * Listen to the onBeforeDeleteLanguageCommit event to delete the blocks which
 * belongs the language to remove
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class DeleteLanguageBlocksListener extends Base\DeleteLanguageBaseListener
{
    private $blockManager;

    /**
     * Constructor
     *
     * @param AlBlockManager $blockManager
     */
    public function __construct(AlBlockManager $blockManager)
    {
        $this->blockManager = $blockManager;
    }

    /**
     * {@inheritdoc}
     *
     * @return null|A model collection instance depending on the used ORM (i.e PropelCollection)
     */
    protected function setUpSourceObjects()
    {
        $language = $this->languageManager->get();
        if (null === $language) {
            return null;
        }

        return $this->blockManager
                        ->getBlockRepository()
                        ->fromLanguageId($language->getId());
    }

    /**
     * {@inheritdoc}
     *
     * @param AlBlock
     * @return boolean
     */
    protected function delete($object)
    {
        $result = $this->blockManager
                    ->set($object)
                    ->delete();

        return $result;
    }
}
