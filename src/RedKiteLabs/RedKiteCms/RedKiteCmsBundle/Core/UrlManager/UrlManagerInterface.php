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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\UrlManager;

/**
 * Defines the methods to manages an url to be used when in CMS mode and for production mode
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface UrlManagerInterface
{
    /**
     * Returns the permalink
     *
     * @return string
     */
    public function getPermalink();

    /**
     * Returns the internal url
     *
     * @return string
     */
    public function getInternalUrl();

    /**
     * Returns the production route that will be generated for the url
     *
     * @return string
     */
    public function getProductionRoute();

    /**
     * Returns the error when something goes wrong
     *
     * @return string
     */
    public function getError();

    /**
     * Builds and internal url to be used when in CMS mode and fetches the information about the
     * url itself
     *
     * @param  int|string|\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Language $language
     * @param  int|string|\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page     $page
     * @return self
     */
    public function buildInternalUrl($language, $page);

    /**
     * Fetches information from the given url
     *
     * @param  string $url
     * @return self
     */
    public function fromUrl($url);
}
