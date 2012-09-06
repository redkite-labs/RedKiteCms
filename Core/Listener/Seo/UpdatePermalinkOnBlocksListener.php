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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Seo;

use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Seo\BeforeEditSeoCommitEvent;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;

/**
 * Listen to the onBeforeAddPageCommit event to add the page attributes when a new page is added
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class UpdatePermalinkOnBlocksListener
{
    protected $factoryRepository = null;
    private $blockRepository = null;
    private $blocksFactory = null;

    public function __construct(AlFactoryRepositoryInterface $factoryRepository, AlBlockManagerFactoryInterface $blocksFactory)
    {
        $this->blocksFactory = $blocksFactory;
        $this->factoryRepository = $factoryRepository;
        $this->blockRepository = $this->factoryRepository->createRepository('Block');
    }

    /**
     * Adds the page attributes when a new page is added, for each language of the site
     *
     * @param BeforeAddPageCommitEvent $event
     * @throws \Exception
     */
    public function onBeforeEditSeoCommit(BeforeEditSeoCommitEvent $event)
    {
        if ($event->isAborted()) {
            return;
        }

        $values = $event->getValues();

        if (!is_array($values)) {
            throw new \InvalidArgumentException('The "values" parameter is expected to be an array');
        }

        if (array_key_exists("oldPermalink", $values)) {
            $result = true;
            $alBlocks = $this->blockRepository->fromHtmlContent($values["oldPermalink"]);
            if (count($alBlocks) > 0) {
                try {
                    $this->blockRepository->startTransaction();
                    foreach($alBlocks as $alBlock) {
                        $htmlContent = preg_replace('/' . $values["oldPermalink"] . '/s', $values["Permalink"], $alBlock->getHtmlContent());
                        $blockManager = $this->blocksFactory->createBlockManager($alBlock);
                        $value = array('HtmlContent' => $htmlContent);
                        $result = $blockManager->save($value);
                        if (!$result) {
                            break;
                        }
                    }

                    if (false !== $result) {
                        $this->blockRepository->commit();
                    }
                    else {
                        $this->blockRepository->rollBack();

                        $event->abort();
                    }
                }
                catch(\Exception $e) {
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

