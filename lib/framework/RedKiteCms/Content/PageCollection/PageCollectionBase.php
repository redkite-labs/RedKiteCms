<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Content\PageCollection;


use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Exception\General\LogicException;

/**
 * Class PageCollectionBase is a base object deputed to handle common methods for pages management
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Page
 */
abstract class PageCollectionBase
{
    /**
     * @type string
     */
    protected $username;
    /**
     * @type string
     */
    protected $pageFile = 'page.json';
    /**
     * @type string
     */
    protected $seoFile = 'seo.json';
    /**
     * @type string
     */
    protected $baseDir;
    /**
     * @type string
     */
    protected $pagesRootDir;
    /**
     * @type string
     */
    protected $pagesDir;
    /**
     * @type string
     */
    protected $pagesRemovedDir;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     */
    public function __construct(ConfigurationHandler $configurationHandler)
    {
        $this->configurationHandler = $configurationHandler;
        $this->baseDir = $this->configurationHandler->siteDir();
        $this->pagesRootDir = $this->configurationHandler->pagesRootDir();
        $this->pagesDir = $this->configurationHandler->pagesDir();
        $this->pagesRemovedDir = $this->configurationHandler->pagesRemovedDir();
    }

    /**
     * Sets the page contributor
     * @param string $username
     *
     * @return $this
     */
    public function contributor($username)
    {
        $this->username = $username;
        if (null !== $this->username) {
            $this->pageFile = $this->seoFile = $this->username . '.json';
        }

        return $this;
    }

    /**
     * Checks the page contributor has been set
     */
    protected function contributorDefined()
    {
        if (null === $this->username) {
            $exception = array(
                "message" => 'exception_contributor_not_defined',
                "show_exception" => true,
            );
            throw new LogicException(json_encode($exception));
        }
    }
} 