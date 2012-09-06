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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page;

use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeEditPageCommitEvent;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\Changer\AlTemplateChanger;
use Symfony\Component\HttpKernel\KernelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;

/**
 * Listen to the onBeforeAddPageCommit event to add the page attributes when a new page is added
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class ChangeTemplateListener
{
    private $templateChanger;
    private $themesCollectionWrapper;
    private $kernel;

    public function __construct(KernelInterface $kernel, AlTemplateChanger $templateChanger, AlThemesCollectionWrapper $themesCollectionWrapper, AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->kernel = $kernel;
        $this->templateChanger = $templateChanger;
        $this->themesCollectionWrapper = $themesCollectionWrapper;

        $this->factoryRepository = $factoryRepository;
        $this->blockRepository = $this->factoryRepository->createRepository('Block');
    }

    /**
     * Changes the page's template
     *
     * @param  BeforeAddPageCommitEvent $event
     * @throws \Exception
     */
    public function onBeforeEditPageCommit(BeforeEditPageCommitEvent $event)
    {
        if ($event->isAborted()) {
            return;
        }

        $pageManager = $event->getContentManager();
        $values = $event->getValues();

        if (!is_array($values)) {
            throw new \InvalidArgumentException('The "values" parameter is expected to be an array');
        }

        if (array_key_exists("oldTemplateName", $values)) {
            $result = true;
            $currentTemplateManager = $pageManager->getTemplateManager();
            $this->blockRepository = $currentTemplateManager->getBlockRepository();
            $this->blockRepository->setConnection($pageManager->getPageRepository()->getConnection());
            $this->blockRepository->startTransaction();
            try {
                $themeName = $currentTemplateManager->getTemplate()->getThemeName();

                $template = $this->themesCollectionWrapper->getTemplate($themeName, $values["TemplateName"]);
                $newTemplateManager = new AlTemplateManager($currentTemplateManager->getDispatcher(), $this->factoryRepository, $template, $currentTemplateManager->getPageBlocks());

                $result = $this->templateChanger->setCurrentTemplateManager($currentTemplateManager)
                            ->setNewTemplateManager($newTemplateManager)
                            ->change();

                if (false !== $result) {
                    $this->blockRepository->commit();
                } else {
                    $this->blockRepository->rollBack();

                    $event->abort();
                }
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
