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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter;

use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks\AlPageBlocksInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;

/**
 * AlSlotConverterBase is the base object deputated to align the blocks placed on a slot
 * which is changing its repeated status
 *
 *
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlSlotConverterBase implements AlSlotConverterInterface
{
    protected $pageContentsContainer;
    protected $factoryRepository = null;
    protected $languageRepository = null;
    protected $pageRepository = null;
    protected $blockRepository = null;
    protected $slot;
    protected $arrayBlocks = array();

    /**
     * Constructor
     *
     * @param AlSlot                       $slot
     * @param AlPageBlocksInterface        $pageContentsContainer
     * @param AlFactoryRepositoryInterface $factoryRepository
     */
    public function __construct(AlSlot $slot, AlPageBlocksInterface $pageContentsContainer, AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->slot = $slot;
        $this->pageContentsContainer = $pageContentsContainer;
        $this->factoryRepository = $factoryRepository;
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
        $this->pageRepository = $this->factoryRepository->createRepository('Page');
        $this->blockRepository = $this->factoryRepository->createRepository('Block');
        $slotBlocks =  $this->pageContentsContainer->getSlotBlocks($this->slot->getSlotName());
        $this->blocksToArray($slotBlocks);
    }

    /**
     * Removes the blocks placed on the current slot from the database
     *
     * @return null|boolean
     * @throws Exception
     */
    protected function deleteBlocks()
    {
        $blocks = $this->blockRepository->retrieveContentsBySlotName($this->slot->getSlotName());
        if (count($blocks) > 0) {
            try {
                $result = null;

                $this->blockRepository->startTransaction();
                foreach ($blocks as $block) {
                    $result = $this->blockRepository
                                ->setRepositoryObject($block)
                                ->delete();

                    if(!$result) break;
                }

                if ($result) {
                    $this->blockRepository->commit();
                } else {
                    $this->blockRepository->rollBack();
                }

                return $result;
            } catch (\Exception $e) {
                if (isset($this->blockRepository) && $this->blockRepository !== null) {
                    $this->blockRepository->rollBack();
                }

                throw $e;
            }
        }
    }

    /**
     * Updates the block, according the page and language with the new repeated status
     *
     * @param array $block
     * @param int   $idLanguage
     * @param int   $idPage
     *
     * @return boolean
     */
    protected function updateBlock(array $block, $idLanguage, $idPage)
    {
        $block["LanguageId"] = $idLanguage;
        $block["PageId"] = $idPage;

        $className = $this->blockRepository->getRepositoryObjectClassName();
        $modelObject = new $className();

        $result = $this->blockRepository
                    ->setRepositoryObject($modelObject)
                    ->save($block);

        return $result;
    }

    /**
     * Converts to array the blocks placed on the current slot
     *
     * @param array $slotBlocks
     */
    private function blocksToArray(array $slotBlocks)
    {
        foreach ($slotBlocks as $block) {
            $aBlock = $block->toArray();
            unset($aBlock["Id"]);

            $this->arrayBlocks[] = $aBlock;
        }
    }
}
