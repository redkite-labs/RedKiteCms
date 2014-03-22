<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Listener\Seo;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Seo\BeforeEditSeoCommitEvent;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManagerFactoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException;

/**
 * Listen to the onBeforeEditSeoCommit event and parsers the blocks to look for the old
 * permalink and replaces it with the new one
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class UpdatePermalinkOnBlocksListener
{
    /** @var FactoryRepositoryInterface */
    protected $factoryRepository;
    /** @var \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface */
    private $blockRepository;
    /** @var null|BlockManagerFactoryInterface */
    private $blocksFactory;

    /**
     * Construct
     *
     * @param FactoryRepositoryInterface   $factoryRepository
     * @param BlockManagerFactoryInterface $blocksFactory
     *
     * @api
     */
    public function __construct(FactoryRepositoryInterface $factoryRepository, BlockManagerFactoryInterface $blocksFactory)
    {
        $this->blocksFactory = $blocksFactory;
        $this->factoryRepository = $factoryRepository;
        $this->blockRepository = $this->factoryRepository->createRepository('Block');
    }

    /**
     * Adds the page attributes when a new page is added, for each language of the site
     *
     * @param  BeforeEditSeoCommitEvent $event
     * @return boolean
     * @throws InvalidArgumentException
     * @throws \Exception
     *
     * @api
     */
    public function onBeforeEditSeoCommit(BeforeEditSeoCommitEvent $event)
    {
        if ($event->isAborted()) {
            return;
        }

        $values = $event->getValues();
        if (!is_array($values)) {
            throw new InvalidArgumentException('exception_invalid_value_array_required');
        }

        if (array_key_exists("oldPermalink", $values)) {
            $result = true;
            $alBlocks = $this->blockRepository->fromContent($values["oldPermalink"]);
            if (count($alBlocks) > 0) {
                try {
                    $this->blockRepository->startTransaction();
                    foreach ($alBlocks as $alBlock) {
                        $htmlContent = preg_replace('/' . $values["oldPermalink"] . '/s', $values["Permalink"], $alBlock->getContent());
                        $blockManager = $this->blocksFactory->createBlockManager($alBlock);
                        $value = array('Content' => $htmlContent);
                        $result = $blockManager->save($value);
                        if (!$result) {
                            break;
                        }
                    }

                    if (false !== $result) {
                        $this->blockRepository->commit();

                        return;
                    }

                    $this->blockRepository->rollBack();
                    $event->abort();
                } catch (\Exception $e) {
                    $event->abort();

                    if (isset($this->blockRepository) && $this->blockRepository !== null) {
                        $this->blockRepository->rollBack();
                    }

                    throw $e;
                }
            }
        }
    }
}
