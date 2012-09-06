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

use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeDeletePageCommitEvent;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Seo\AlSeoManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;

/**
 * Listen to the onBeforeDeletePageCommit event to delete the page's seo attributes, when a page is removed
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class DeleteSeoListener
{
    protected $factoryRepository = null;
    private $seoManager = null;
    private $languageRepository = null;

    /**
     * Constructor
     *
     * @param AlSeoManager $seoManager
     * @param AlFactoryRepositoryInterface $factoryRepository
     */
    public function __construct(AlSeoManager $seoManager, AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->seoManager = $seoManager;
        $this->factoryRepository = $factoryRepository;
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
    }

    /**
     * Deletes the page's seo attributes, for all the languages of the site
     *
     * @param BeforeDeletePageCommitEvent $event
     * @throws Exception
     */
    public function onBeforeDeletePageCommit(BeforeDeletePageCommitEvent $event)
    {
        if ($event->isAborted()) {
            return;
        }

        $pageManager = $event->getContentManager();
        $pageRepository = $pageManager->getPageRepository();

        try {
            $languages = $this->languageRepository->activeLanguages();
            if (count($languages) > 0) {
                $result = null;
                $pageRepository->startTransaction();
                $idPage = $pageManager->get()->getId();
                foreach ($languages as $alLanguage) {
                    $result = $this->seoManager->deleteSeoAttributesFromLanguage($alLanguage->getId(), $idPage);
                    if (false === $result) break;
                }

                if (false !== $result) {
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

