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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\SiteBootstrap;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\PageRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Orm\OrmInterface;

/**
 * AlSiteBootstrap is responsibile to boostrap a web site from the scratch, for a 
 * given theme
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
class AlSiteBootstrap
{
    protected $languageManager;
    protected $pageManager;
    protected $blockManager;
    protected $templateManager;
    protected $errorMessage = '';
    
    private $defaultLanguage = array(
        'LanguageName' => 'en',
    );
    private $defaultPage = array(
        'PageName' => 'index',
        'Permalink' => 'homepage',
        'IsPublished' => '1',
        'MetaTitle' => 'A website made with AlphaLemon CMS',
        'MetaDescription' => 'Website homepage',
        'MetaKeywords' => '',
    );
    
    /**
     * Constructor
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager $languageManager
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager $pageManager
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager $templateManager
     * 
     * @api
     */
    public function __construct(AlLanguageManager $languageManager,
                                AlPageManager $pageManager,
                                \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager $blockManager,
                                AlTemplateManager $templateManager = null)
    {
        $this->languageManager = $languageManager;
        $this->pageManager = $pageManager;        
        $this->blockManager = $blockManager;
        $this->templateManager = $templateManager;
    }
    
    /**
     * @inheritdoc
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
    
    /**
     * @inheritdoc
     */
    public function setLanguageManager(AlLanguageManager $value)
    {
        $this->languageManager = $value;
        
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function setPageManager(AlPageManager $value)
    {
        $this->pageManager = $value;
        
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function setTemplateManager(AlTemplateManager $value)
    {
        $this->templateManager = $value;
        
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function setDefaultLanguageValues(array $value)
    {
        $this->defaultLanguage = $value;
        
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function setDefaultPageValues(array $value)
    {
        $this->defaultPage = $value;
        
        return $this;
    }
    
    /**
     * Bootstraps the website
     * 
     * @return boolean
     * 
     * @api
     */
    public function bootstrap()
    {
        $languageRepository = $this->languageManager->getLanguageRepository();
        $pageRepository = $this->pageManager->getPageRepository();
        $blockRepository = $this->blockManager->getBlockRepository();
        
        $languageRepository->startTransaction();
        if ( ! $this->removeActiveLanguages($languageRepository)) {
            return $this->fails($languageRepository);
        }
        
        if ( ! $this->removeActivePages($pageRepository)) {
            return $this->fails($languageRepository);
        }
        
        $blockRepository->deleteBlocks(1, 1, true);
        
        if ( ! $this->addLanguage()) {
            return $this->fails($languageRepository);
        }
        
        if ( ! $this->addPage()) {
            return $this->fails($languageRepository);
        }
        
        $languageRepository->commit();
        
        return true;
    }

    /**
     * Removes the active languages
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface $languageRepository
     * @return boolean
     */
    protected function removeActiveLanguages(LanguageRepositoryInterface $languageRepository)
    {
        try {
            $languages = $languageRepository->activeLanguages();
            foreach($languages as $language) {
                $language->delete();
            }

            return true;
        }
        catch(\Exception $ex) {
            $this->errorMessage = "An error occoured during the removing of existing languages. The reported error is: " . $ex->getMessage();
            
            return false;
        }
    }
    
    /**
     * Removes the active pages
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\PageRepositoryInterface $pageRepository
     * @return boolean
     */
    protected function removeActivePages(PageRepositoryInterface $pageRepository)
    {
        try {
            $pages = $pageRepository->activePages();
            foreach($pages as $page) {
                $page->delete();
            }

            return true;
        }
        catch(\Exception $ex) {
            $this->errorMessage = "An error occoured during the removing of existing pages. The reported error is: " . $ex->getMessage();
            
            return false;
        }
    }
    
    /**
     * Adds a new language
     * 
     * @return boolean 
     */
    protected function addLanguage()
    {
        try {
            $result = $this->languageManager
                ->set(null)
                ->save($this->defaultLanguage)
            ;

            if ( ! $result) {
                $this->errorMessage = "An error occoured during the saving of the new language";
            }
            
            return $result;
        }
        catch(\Exception $ex) {
            $this->errorMessage = "An error occoured during the saving of the new language. The reported error is: " . $ex->getMessage();
            
            return false;
        }
    }
    
    /**
     * Adds a new page
     * 
     * @return boolean 
     */
    protected function addPage()
    {
        try {
            $values = $this->defaultPage;
            $values['TemplateName'] = $this->templateManager->getTemplate()->getTemplateName();

            $result = $this->pageManager
                ->set(null)
                ->save($values)
            ;
            
            if ( ! $result) {
                $this->errorMessage = "An error occoured during the saving of the new page";
            }
            
            return $result;
        }
        catch(\Exception $ex) {
            $this->errorMessage = "An error occoured during the saving of the new page. The reported error is: " . $ex->getMessage();
            
            return false;
        }
    }
    
    /**
     * Rollbacks the saving operation
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Orm\OrmInterface $repository
     * @return boolean
     */
    protected function fails(OrmInterface $repository)
    {
        $repository->rollback();
        
        return false;
    }
}