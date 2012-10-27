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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\SiteBootstrap;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;

/**
 * AlSiteBootstrap is responsibile to boostrap a web site from the scratch for a 
 * given theme
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface AlSiteBootstrapInterface
{
    
    /**
     * Returns the error message
     * 
     * @return string
     */
    public function getErrorMessage();
    
    /**
     * Sets the language manager
     * 
     * @param AlLanguageManager $value
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\SiteBootstrap\AlSiteBootstrap 
     */
    public function setLanguageManager(AlLanguageManager $value);
    
    /**
     * Sets the page manager
     * 
     * @param AlPageManager $value
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\SiteBootstrap\AlSiteBootstrap 
     */
    public function setPageManager(AlPageManager $value);
    
    /**
     * Sets the template manager
     * 
     * @param AlTemplateManager $value
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\SiteBootstrap\AlSiteBootstrap 
     */
    public function setTemplateManager(AlTemplateManager $value);
    
    /**
     * Sets the default language's values used to add the new language
     * 
     * @param array $value
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\SiteBootstrap\AlSiteBootstrap 
     */
    public function setDefaultLanguageValues(array $value);
    
    /**
     * Sets the default page's values used to add the new language
     * 
     * @param array $value
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\SiteBootstrap\AlSiteBootstrap 
     */
    public function setDefaultPageValues(array $value);
    
    /**
     * Bootstraps the website
     * 
     * @return boolean 
     */
    public function bootstrap();
}