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
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageAttributes\AlPageAttributesManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\OrmInterface;

/**
 * Listen to the onBeforeDeletePageCommit event to delete page contents when a page is removed
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class DeletePageAttributesListener
{
    private $pageAttributesManager;
    
    public function __construct(AlPageAttributesManager $pageAttributesManager, OrmInterface $pageAttribute)
    {
        $this->pageAttributesManager = $pageAttributesManager;
        $this->pageAttribute = $pageAttribute;
    }

    /**
     * Deletes the page contents for all the site languages
     * 
     * @param BeforeDeletePageCommitEvent $event
     * @return type
     * @throws Exception 
     */
    public function onBeforeDeletePageCommit(BeforeDeletePageCommitEvent $event)
    {
        if ($event->isAborted()) {
            return;
        }
        
        $pageManager = $event->getContentManager(); 
        $connection = $pageManager->getConnection();
        
        try {
            $rollBack = false;
            $connection->beginTransaction();
            
            $languages= $pageManager->getValidator()->getSiteLanguages();
            foreach ($languages as $alLanguage) {
                $pageAttribute = $this->pageAttribute->fromPageAndLanguage($alLanguage->getId(), $pageManager->get()->getId()); //$this->retrievePageAttribute($alLanguage->getId(), $pageManager->get()->getId());
                $this->pageAttributesManager->set($pageAttribute);
                $rollBack = !$this->pageAttributesManager->delete();

                if ($rollBack) {
                    break;
                }
            }
            
            if (!$rollBack) {
                $connection->commit();
            }
            else {
                $connection->rollback();
                $event->abort();
            }
        }
        catch(\Exception $e) {
            $event->abort();
            if (isset($connection) && $connection !== null) {
                $connection->rollback();
            }
            
            throw $e;
        }
    }
}

