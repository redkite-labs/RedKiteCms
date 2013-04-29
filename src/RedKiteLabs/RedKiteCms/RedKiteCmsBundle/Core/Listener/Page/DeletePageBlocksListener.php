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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page;

use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeDeletePageCommitEvent;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;

/**
 * Listen to the onBeforeDeletePageCommit event to delete page's contents, when a page 
 * is removed
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 * 
 * @api
 */
class DeletePageBlocksListener
{
    private $factoryRepository = null;
    private $languageRepository = null;
    
    /**
     * Constructor
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $factoryRepository
     * 
     * @api
     */
    public function __construct(AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->factoryRepository = $factoryRepository;
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
    }
    
    /**
     * Deletes the page's contents, for all the languages of the site
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeDeletePageCommitEvent $event
     * @return boolean
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page\Exception
     * 
     * @api
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
                $result = true;
                $idPage = $pageManager->get()->getId();
                $pageRepository->startTransaction();
                $templateManager = $pageManager->getTemplateManager();
                foreach ($languages as $alLanguage) {
                    $result = $templateManager->clearPageBlocks($alLanguage->getId(), $idPage);
                    if (false === $result) {
                        break;
                    }
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