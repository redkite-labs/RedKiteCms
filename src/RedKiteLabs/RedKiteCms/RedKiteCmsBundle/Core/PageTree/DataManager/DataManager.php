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

namespace RedKiteLabs\RedKiteCmsBundle\Core\PageTree\DataManager;

use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Model\AlPage;
use RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage;
use Symfony\Component\HttpFoundation\Request;

/**
 * DataManager is the objected deputated to handle the information related to a website 
 * page, retrieved from a database
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class DataManager
{
    private $factoryRepository = null;
    private $seoRepository = null;
    private $language = null;
    private $page = null;
    private $seo = null;

    /**
     * Constructor
     * 
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $factoryRepository
     */
    public function __construct(AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->factoryRepository = $factoryRepository;
    }
    
    /**
     * Returns the current AlPage object
     *
     * @return AlPage instance
     *
     * @api
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Returns the current AlLanguage object
     *
     * @return AlLanguage instance
     *
     * @api
     */
    public function getLanguage()
    {
        return $this->language;
    }
    
    /**
     * Returns the current AlSeo object
     *
     * @return AlSeo instance
     *
     * @api
     */
    public function getSeo()
    {
        return $this->seo;
    }
    
    /**
     * Initializes the DataManager object from a request
     * 
     * @param type $request
     */
    public function fromRequest(Request $request)
    {
        $pageName = $request->get('page');
        $language = $request->get('_locale');
        $options = array(
            "pageName" => $pageName,            
            "languageName" => $language,        
            "pageId" => (int)$request->get('pageId'),            
            "languageId" => (int)$request->get('languageId'),    
        );
        
        $this->fromOptions($options);
    }
    
    /**
     * Initializes the DataManager object from the database entities
     * 
     * @param \RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage $language
     * @param \RedKiteLabs\RedKiteCmsBundle\Model\AlPage $page
     */
    public function fromEntities(AlLanguage $language = null, AlPage $page = null)
    {
        $this->language = $language;
        $this->page = $page;
        if (null !== $this->language && null !== $this->page) {
            $options = array(           
                "languageId" => $this->language->getId(), 
                "pageId" => $this->page->getId(),    
            );
            $this->seo = $this->setupSeo($options);
        }
    }
    
    /**
     * Initializes the DataManager object from and array of options
     * 
     * @param array $options
     */
    public function fromOptions(array $options)
    {  
        $this->seo = $this->setupSeo($options);
        if (null !== $this->seo) {
            $this->language = $this->seo->getAlLanguage();
            $this->page = $this->seo->getAlPage();
        }
    }
    
    private function setupSeo(array $options)
    {
        $seo = null;
        if ($options["languageId"] != 0 && $options["pageId"] != 0) {
            $seo = $this->seoRepository()->fromPageAndLanguage($options["languageId"], $options["pageId"]);
            
            if (null !== $seo) {
                return $seo;
            }
        }
        
        $seo = $this->seoRepository()->fromLanguageAndPageNames($options["languageName"], $options["pageName"]);
        if (null !== $seo) {
            return $seo;
        }
        
        $seo = $this->seoRepository()->fromPermalink($options["languageName"]);
        if (null !== $seo) {
            return $seo;
        }
        
        return $this->seoRepository()->fromPermalink($options["pageName"]);
    }
    
    private function seoRepository()
    {
        if (null === $this->seoRepository) {
            $this->seoRepository = $this->factoryRepository->createRepository('Seo');
        }
        
        return $this->seoRepository;
    }
}