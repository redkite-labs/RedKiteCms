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

use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface;

/**
 * Listen to the onBeforeAddPageCommit event to add the page's contents, when a new page is added
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AddPageBlocksListener
{
    private $languageRepository;

    /**
     * Constructor
     *
     * @param LanguageRepositoryInterface $languageRepository
     */
    public function __construct(LanguageRepositoryInterface $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * Adds the contents for the page when a new page is added, for each language of the site
     *
     * @param BeforeAddPageCommitEvent $event
     * @throws Exception
     */
    public function onBeforeAddPageCommit(BeforeAddPageCommitEvent $event)
    {
        if ($event->isAborted()) {
            return;
        }
        
        $pageManager = $event->getContentManager();
        $templateManager = $pageManager->getTemplateManager(); 
        $pageRepository = $pageManager->getPageModel();
        try {
            $languages = $this->languageRepository->activeLanguages();
            if (count($languages) > 0) {
                $result = true;
                $pageRepository->startTransaction();
                // The min number of pages is setted to 1 because we are adding a page which has been saved but not
                // committed so it counts as one
                $ignoreRepeatedSlots = $pageManager->getValidator()->hasPages(1);
                $idPage = $pageManager->get()->getId();
                foreach ($languages as $alLanguage) {
                    $result = $templateManager->populate($alLanguage->getId(), $idPage, $ignoreRepeatedSlots);

                    if (!$result) break;
                }

                if ($result) {
                    $pageRepository->commit();
                }
                else {
                    $pageRepository->rollBack();

                    $event->abort();
                }
            }
        }
        catch(\Exception $e) {
            $event->abort();
            if (isset($pageRepository) && $pageRepository !== null) {
                $pageRepository->rollBack();
            }

            throw $e;
        }
    }
}

