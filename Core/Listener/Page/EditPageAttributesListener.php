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
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageAttributes\AlPageAttributesManager;

/**
 * Listen to the onBeforeAddPageCommit event to add the page attributes when a new page is added
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class EditPageAttributesListener
{
    private $pageAttributesManager;
    private $pageAttribute;
    
    public function __construct(AlPageAttributesManager $pageAttributesManager, OrmInterface $pageAttribute)
    {
        $this->pageAttributesManager = $pageAttributesManager;
        $this->pageAttribute = $pageAttribute;
    }

    /**
     * Adds the page attributes when a new page is added, for each language of the site
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
        $connection = $pageManager->getConnection();        
        $values = $event->getValues();
        
        if (!is_array($values)) {
            throw new \InvalidArgumentException("The values param is expected to be an array");
        }
            
        try {
            $rollBack = false;
            $connection->beginTransaction();
            
            
            $idPage = $pageManager->get()->getId();            
            $pageAttribute = $this->pageAttribute->fromPageAndLanguage($alLanguage->getId(), $idPage);
            $this->pageAttributesManager->set($pageAttribute);
            $values = array_merge($values, array('idPage' => $idPage, 'idLanguage' => $idLanguage));
            $rollBack = !$this->pageAttributesManager->save($values); 

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

