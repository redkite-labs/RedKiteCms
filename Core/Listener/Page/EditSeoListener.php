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
 * Listen to the onBeforeAddPageCommit event to add the page attributes when a new page is added
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class EditSeoListener
{
    private $seoManager;
    
    public function __construct(AlSeoManager $seoManager)
    {
        $this->seoManager = $seoManager;
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
        $pageModel = $pageManager->getPageModel();
        $values = $event->getValues();
        
        if (!is_array($values)) {
            throw new \InvalidArgumentException("The values param is expected to be an array");
        }
            
        try {
            $idPage = $pageManager->get()->getId();  
            $idLanguage = $pageManager->getTemplateManager()
                    ->getPageContentsContainer()
                    ->getIdLanguage();
            $seo = $this->seoManager->getSeoModel()->fromPageAndLanguage($idLanguage, $idPage);
            if( null !== $seo) {
                $pageModel->startTransaction();
                $this->seoManager->set($seo);
                $values = array_merge($values, array('PageId' => $idPage, 'LanguageId' => $idLanguage));
                $result = $this->seoManager->save($values); 

                if ($result) {
                    $pageModel->commit();
                }
                else {
                    $pageModel->rollBack();
                    
                    $event->abort();
                }
            }
        }
        catch(\Exception $e) {          
            $event->abort();
            
            if (isset($pageModel) && $pageModel !== null) {
                $pageModel->rollBack();
            }
            
            throw $e;
        }
    }
}

