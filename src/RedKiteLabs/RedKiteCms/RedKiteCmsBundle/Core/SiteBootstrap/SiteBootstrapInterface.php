<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\SiteBootstrap;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Language\LanguageManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Page\PageManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Template\TemplateManager;

/**
 * SiteBootstrapInterface defines the methos to boostrap a RedKiteCms website from
 * the scratch for a given theme
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
interface SiteBootstrapInterface
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
     * @param  LanguageManager $value
     * @return self
     *
     * @api
     */
    public function setLanguageManager(LanguageManager $value);

    /**
     * Sets the page manager
     *
     * @param  PageManager $value
     * @return self
     *
     * @api
     */
    public function setPageManager(PageManager $value);

    /**
     * Sets the template manager
     *
     * @param  TemplateManager $value
     * @return self
     *
     * @api
     */
    public function setTemplateManager(TemplateManager $value);

    /**
     * Sets the default language's values used to add the new language
     *
     * @param  array $value
     * @return self
     *
     * @api
     */
    public function setDefaultLanguageValues(array $value);

    /**
     * Sets the default page's values used to add the new language
     *
     * @param  array $value
     * @return self
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
