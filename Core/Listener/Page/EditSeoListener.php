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
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Seo\AlSeoManager;

/**
 * Listen to the onBeforeEditPageCommit event to edit the seo attributes when a new page is edited
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class EditSeoListener
{
    private $seoManager;

    public function __construct(AlSeoManager $seoManager)
    {
        $this->seoManager = $seoManager;
    }

    /**
     * Edits the seo attributes when a new page is edited
     *
     * @param BeforeEditPageCommitEvent $event
     * @throws \InvalidArgumentException
     * @throws Exception
     */
    public function onBeforeEditPageCommit(BeforeEditPageCommitEvent $event)
    {
        if ($event->isAborted()) {
            return;
        }

        $pageManager = $event->getContentManager();
        $pageRepository = $pageManager->getPageRepository();
        $values = $event->getValues();

        if (!is_array($values)) {
            throw new \InvalidArgumentException("The values param is expected to be an array");
        }

        try {
            $idPage = $pageManager->get()->getId();
            $idLanguage = $pageManager->getTemplateManager()
                    ->getPageBlocks()
                    ->getIdLanguage();
            $seoRepository = $this->seoManager->getSeoRepository();
            $seo = $seoRepository->fromPageAndLanguage($idLanguage, $idPage);
            if( null !== $seo) {
                $seoRepository->setConnection($pageRepository->getConnection());
                $pageRepository->startTransaction();
                $this->seoManager->set($seo);
                $values = array_merge($values, array('PageId' => $idPage, 'LanguageId' => $idLanguage));
                $result = $this->seoManager->save($values);

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