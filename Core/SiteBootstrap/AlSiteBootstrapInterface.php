<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\SiteBootstrap;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Language\AlLanguageManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Page\AlPageManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager;

/**
 * AlSiteBootstrapInterface defines the methos to boostrap an AlphaLemon website from
 * the scratch for a given theme
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
interface AlSiteBootstrapInterface
{
    /**
     * Returns the error message
     *
     * @return string
     *
     * @api
     */
    public function getErrorMessage();

    /**
     * Sets the language manager
     *
     * @param  AlLanguageManager                                                  $value
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\SiteBootstrap\AlSiteBootstrap
     *
     * @api
     */
    public function setLanguageManager(AlLanguageManager $value);

    /**
     * Sets the page manager
     *
     * @param  AlPageManager                                                      $value
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\SiteBootstrap\AlSiteBootstrap
     *
     * @api
     */
    public function setPageManager(AlPageManager $value);

    /**
     * Sets the template manager
     *
     * @param  AlTemplateManager                                                  $value
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\SiteBootstrap\AlSiteBootstrap
     *
     * @api
     */
    public function setTemplateManager(AlTemplateManager $value);

    /**
     * Sets the default language's values used to add the new language
     *
     * @param  array                                                              $value
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\SiteBootstrap\AlSiteBootstrap
     *
     * @api
     */
    public function setDefaultLanguageValues(array $value);

    /**
     * Sets the default page's values used to add the new language
     *
     * @param  array                                                              $value
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\SiteBootstrap\AlSiteBootstrap
     *
     * @api
     */
    public function setDefaultPageValues(array $value);

    /**
     * Bootstraps the website
     *
     * @return boolean
     *
     * @api
     */
    public function bootstrap();
}
