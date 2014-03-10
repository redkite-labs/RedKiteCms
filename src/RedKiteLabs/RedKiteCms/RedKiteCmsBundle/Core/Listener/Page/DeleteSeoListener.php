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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Listener\Page;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Page\BeforeDeletePageCommitEvent;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Seo\AlSeoManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;

/**
 * Listen to the onBeforeDeletePageCommit event to delete the page's seo attributes, when
 * a page is removed
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class DeleteSeoListener
{
    /** @var null|AlFactoryRepositoryInterface */
    protected $factoryRepository = null;
    /** @var null|AlSeoManager */
    private $seoManager = null;
    /** @var null|\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface */
    private $languageRepository = null;

    /**
     * Constructor
     *
     * @param AlSeoManager                 $seoManager
     * @param AlFactoryRepositoryInterface $factoryRepository
     *
     * @api
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
     * @param  BeforeDeletePageCommitEvent $event
     * @return boolean
     * @throws \Exception
     *
     * api
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
                } else {
                    $pageRepository->rollBack();

                    $event->abort();
                }
            }
        } catch (\Exception $e) {
            $event->abort();
            if (isset($pageRepository) && $pageRepository !== null) {
                $pageRepository->rollBack();
            }

            throw $e;
        }
    }
}
