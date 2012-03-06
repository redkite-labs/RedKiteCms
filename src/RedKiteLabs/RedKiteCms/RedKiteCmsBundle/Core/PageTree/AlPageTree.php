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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\PageTree;

use AlphaLemon\PageTreeBundle\Core\PageTree\AlPageTree as BaseAlPageTree;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageAttributeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;
use AlphaLemon\ThemeEngineBundle\Core\Model\AlThemeQuery;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\AlRepeatedSlotsManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;

/**
 * Extends the bas AlPageTree object to work with the database
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlPageTree extends BaseAlPageTree
{
    protected $alPage = null;
    protected $alLanguage = null;
    protected $alTheme = null;
    //protected $bridge = null;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }
    
    public function getAlPage()
    {
        return $this->alPage;
    }

    public function getAlLanguage()
    {
        return $this->alLanguage;
    }

    public function getAlTheme()
    {
        return $this->alTheme;
    }
    
    public function getBridge()
    {
        return $this->bridge;
    }

    public function isCmsMode()
    {
        return true;
    }

    /**
     * Sets up the page tree object from the language and page objects passed as parameters. When one or both those parameters misses, 
     * the PageTree is setted up from the current request and session
     * 
     * @param   AlLanguage  $alLanguage The AlLanguage object to use or none
     * @param   AlPage      $alPage     The AlPage object to use or none
     * @return  null        Returns null when something goes wrong 
     */
    public function setup(AlLanguage $alLanguage = null, AlPage $alPage = null)
    {
        try
        {
            $alTheme = AlThemeQuery::create()->activeBackend()->findOne();
            if(!$alTheme)
            {
                return null;
            }

            $this->alTheme = $alTheme;
            $this->setThemeName($this->alTheme->getThemeName());
            
            $this->alLanguage = (null === $alLanguage) ? $this->setupLanguageFromSession() : $alLanguage;
            $this->alPage = (null === $alPage) ? $this->setupPageFromRequest() : $alPage;

            if(null === $this->alLanguage || null === $this->alPage) return null;
            $this->setTemplateName($this->alPage->getTemplateName());
            
            $templateManager = new AlTemplateManager($this->container, $this->alPage, $this->alLanguage, $this->themeName, $this->templateName);
            $this->setContents($templateManager->slotsToArray(), true);

            $rs = new AlRepeatedSlotsManager($this->container, $alTheme->getThemeName());
            $rs->compareSlots($this->templateName, $this->getSlots(), true);
        }
        catch(\Exception $ex)
        {
            throw $ex;
        }
    }
    
    /**
     * Sets up the AlLanguage object from the current session
     * 
     * @return AlLanguage or null
     */
    protected function setupLanguageFromSession()
    {
        $language = $this->container->get('request')->get('language');        
        if(null === $language) $language = $this->container->get('session')->getLocale();
        
        $check = (int)$language;
        $alLanguage = ($check > 0) ? AlLanguageQuery::create()->findPk($language) : AlLanguageQuery::create()->fromLanguageName($language)->findOne();
        
        return $alLanguage;
    }
    
    /**
     * Sets up the AlLanguage object from the current session and language
     * 
     * @param AlLanguage    $alLanguage The AlLanguage object
     * 
     * @return AlPage or null 
     */
    protected function setupPageFromRequest(AlLanguage $alLanguage = null)
    {
        if(null === $alLanguage && null === $this->alLanguage)
        {
            return null;
        }
        
        if(null === $alLanguage)
        {
            $alLanguage = $this->alLanguage;
        }
        
        $pageName = $this->container->get('request')->get('page');
        if(!$pageName || $pageName == "" || !is_string($pageName) || $pageName == "backend")
        {
            return null;
        }

        $alPageAttribute = AlPageAttributeQuery::create()->setContainer($this->container)->fromPermalink($pageName, $alLanguage->getId())->findOne();
        if(!$alPageAttribute)
        {
            $alPageAttribute = AlPageAttributeQuery::create()->setContainer($this->container)->fromPageAndLanguage($pageName, $alLanguage->getId())->findOne();
        }

        if(null === $alPageAttribute)
        {
            $alPage = AlPageQuery::create()->setContainer($this->container)->fromPageName($pageName)->findOne();
            if(!$alPage)
            {
                $alPage = AlPageQuery::create()->findPk($pageName);
                if(!$alPage)
                {
                    return null;
                }
            }
        }
        else
        {
            $alPage = $alPageAttribute->getAlPage();
        }
        
        return $alPage;
    }
    
    /**
     * Overrides the base method to add extra functionalities
     * 
     * @see   AlphaLemon\PageTreeBundle\Core\PageTree\AlPageTree->addContent()
     */
    public function addContent($slotName, array $content, $key = null)
    {
        parent::addContent($slotName, $content, $key);

        if(array_key_exists("ExternalJavascript", $content))
        {
            $javascripts = (!is_array($content["ExternalJavascript"])) ? ($content["ExternalJavascript"] != "") ? \explode(',', $content["ExternalJavascript"]) : array(): $content["ExternalJavascript"];
            
            foreach($javascripts as $javascript)
            {
                $this->addJavascript($javascript);
            }
        }
        
        if(array_key_exists("InternalJavascript", $content) && $content["InternalJavascript"] != "")
        {
            $this->appendInternalJavascript($content["InternalJavascript"]);
        }

        if(array_key_exists("ExternalStylesheet", $content))
        {
            $stylesheets = (!is_array($content["ExternalStylesheet"])) ? \explode(',', $content["ExternalStylesheet"]) : $content["ExternalStylesheet"];
            foreach($stylesheets as $stylesheet)
            {
                $this->addStylesheet($stylesheet);
            }
        }

        if(array_key_exists("InternalStylesheet", $content) && $content["InternalStylesheet"] != "")
        {
            $this->appendInternalStylesheet($content["InternalStylesheet"]);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function addStylesheet($value)
    {
        if($value != "" && !in_array($value, $this->externalStylesheets))
        {
            $this->externalStylesheets[] = $value;
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function addJavascript($value)
    {
        if($value != "" && !in_array($value, $this->externalJavascripts))
        {
            $this->externalJavascripts[] = $value;
        }
    }
}
