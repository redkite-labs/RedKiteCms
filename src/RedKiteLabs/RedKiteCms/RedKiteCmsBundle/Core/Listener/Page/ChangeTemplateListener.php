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
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlotsFactory;
use AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateAssets;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\Changer\AlTemplateChanger;
use Symfony\Component\HttpKernel\KernelInterface;
use AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection;
use AlphaLemon\AlphaLemonCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper;

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

    public function __construct(KernelInterface $kernel, AlTemplateChanger $templateChanger, AlThemesCollectionWrapper $themesCollectionWrapper)
    {
        $this->kernel = $kernel;
        $this->templateChanger = $templateChanger;
        $this->themesCollectionWrapper = $themesCollectionWrapper;
    }

    /**
     * Changes the page's template 
     *
     * @param BeforeAddPageCommitEvent $event
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
            $blockRepository = $currentTemplateManager->getBlockModel();
            try {
                $themeName = $currentTemplateManager->getTemplate()->getThemeName();
                $blockRepository->startTransaction();
                
                $template = $this->themesCollectionWrapper->getTemplate($themeName, $values["TemplateName"]);                
                $newTemplateManager = new AlTemplateManager($currentTemplateManager->getDispatcher(), $template, $currentTemplateManager->getPageBlocks(), $blockRepository);
                
                $result = $this->templateChanger->setCurrentTemplateManager($currentTemplateManager)
                            ->setNewTemplateManager($newTemplateManager)
                            ->change();

                if ($result) {
                    $blockRepository->commit();
                }
                else {
                    $blockRepository->rollBack();

                    $event->abort();
                }
            }
            catch(\Exception $e) {
                $event->abort();

                if (isset($blockRepository) && $blockRepository !== null) {
                    $blockRepository->rollBack();
                }

                throw $e;
            }
        }
    }
}

