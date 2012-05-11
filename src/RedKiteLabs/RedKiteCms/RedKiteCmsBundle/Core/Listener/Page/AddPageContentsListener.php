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

/**
 * Listen to the onBeforeAddPageCommit event to add contents when a new page is added
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AddPageContentsListener
{
    private $templateManager;
    
    public function __construct(AlTemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }
    
    /**
     * Adds the contents for the page when a new page is added, for each language of the site
     * 
     * @param BeforeAddPageCommitEvent $event
     * @throws \Exception 
     */
    public function onBeforeAddPageCommit(BeforeAddPageCommitEvent $event)
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
                $rollBack = !$this->templateManager->populate($alLanguage->getId(), $pageManager->get()->getId());

                if ($rollBack) break;    
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

