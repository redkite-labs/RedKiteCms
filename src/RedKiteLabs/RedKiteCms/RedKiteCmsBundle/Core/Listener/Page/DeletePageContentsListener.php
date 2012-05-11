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
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;

/**
 * Listen to the onBeforeDeletePageCommit event to delete page contents when a page is removed
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class DeletePageContentsListener
{
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
            
            $rollBack = !$this->removeBlocks($pageManager->getTemplateManager());
            if (!$rollBack) {
                $languages= $pageManager->getValidator()->getSiteLanguages();
                foreach ($languages as $alLanguage) {
                    $pageContentsContainer = $pageManager->getTemplateManager()->getPageContentsContainer();
                    if ($alLanguage !== $pageContentsContainer->getAlLanguage()) {
                        $pageContentsContainer->setAlLanguage($alLanguage);
                        $templateManager = $pageManager->getTemplateManager()->setPageContentsContainer($pageContentsContainer);
                        $rollBack = !$this->removeBlocks($templateManager);
                    }

                    if ($rollBack) break;    
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
    
    /**
     * Removes the blocks
     * 
     * @param AlTemplateManager $templateManager
     * @return boolean 
     */
    private function removeBlocks($templateManager)
    {
        foreach ($templateManager->getSlotManagers() as $slotManager) {
            $result = (strtolower($slotManager->getRepeated()) == 'page') ? $slotManager->deleteBlocks() : true;
            
            if (!$result) {
                return false;
            }
        }
        
        return true;
    }
}

