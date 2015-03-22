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

namespace RedKiteCms\Bridge\Routing;

use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Finder\Finder;

/**
 * This object is deputed to generate dynamically the website page routes
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Bridge\Routing
 */
class RoutingGenerator
{
    /**
     * @type ConfigurationHandler
     */
    private $configurationHandler;
    /**
     * @type string
     */
    private $pattern = null;
    /**
     * @type string
     */
    private $frontController = null;
    /**
     * @type string
     */
    private $bindPrefix = "";
    /**
     * @type string
     */
    private $contributor = null;
    /**
     * @type string
     */
    private $explicitHomepageRoute = false;

    /**
     * Constructor
     *
     * @param ConfigurationHandler $configurationHandler
     */
    public function __construct(ConfigurationHandler $configurationHandler)
    {
        $this->configurationHandler = $configurationHandler;
    }

    /**
     * Sets the route pattern
     *
     * @param $pattern
     * @return $this
     */
    public function pattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * Sets the frontcontroller that handles the route
     *
     * @param $frontController
     * @return $this
     */
    public function frontController($frontController)
    {
        $this->frontController = $frontController;

        return $this;
    }

    /**
     * Sets a prefix added to route bind name
     *
     * @param $bindPrefix
     * @return $this
     */
    public function bindPrefix($bindPrefix)
    {
        $this->bindPrefix = $bindPrefix;

        return $this;
    }

    /**
     * Sets the contributer user
     *
     * @param $contributor
     * @return $this
     */
    public function contributor($contributor)
    {
        $this->contributor = $contributor;

        return $this;
    }

    /**
     * Generates the homepage route when true
     *
     * @param $value
     * @return $this
     */
    public function explicitHomepageRoute($value)
    {
        $this->explicitHomepageRoute = (bool)$value;

        return $this;
    }

    /**
     * Generates the routes
     *
     * @return array
     */
    public function generate()
    {
        $routes = array();
        $pagesDir = $this->configurationHandler->pagesDir();
        $homepageValues = array(
            '_locale' => $this->configurationHandler->language(),
            'country' => $this->configurationHandler->country(),
            'page' => $this->configurationHandler->homepage(),
        );

        if (!$this->explicitHomepageRoute) {
            $routeName = '_' . $homepageValues["_locale"] . '_' . $homepageValues["country"] . '_' . $homepageValues["page"];
            $routes[] = array(
                'pattern' => $this->pattern,
                'controller' => $this->frontController,
                'method' => array('get'),
                'value' => $homepageValues,
                'bind' => $this->bindPrefix . $routeName,
            );
        }

        $finder = new Finder();
        $seoFileName = 'seo.json';
        if (null !== $this->contributor) {
            $seoFileName = $this->contributor . '.json';
        }

        $pages = $finder->directories()->depth(0)->in($pagesDir);
        foreach ($pages as $page) {
            $page = (string)$page;
            $pageName = basename($page);

            $languagesFinder = new Finder();
            $languages = $languagesFinder->directories()->depth(0)->in($page);
            foreach ($languages as $language) {
                $language = (string)$language;
                $seoFile = $language . '/' . $seoFileName;
                if (!file_exists($seoFile)) {
                    continue;
                }

                $languageName = basename($language);
                $languageTokens = explode('_', $languageName);
                $routeName = '_' . $languageName . '_' . $pageName;
                $values = array(
                    '_locale' => $languageTokens[0],
                    'country' => $languageTokens[1],
                    'page' => $pageName,
                );

                $pattern = $this->pattern;
                if (substr($pattern, -1) != '/') {
                    $pattern .= '/';
                }
                $pageValues = json_decode(FilesystemTools::readFile($seoFile), true);
                $changedPermalinkRoutes = $this->addChangedPermalinks($routeName, $pattern, $pageValues);
                if (null !== $changedPermalinkRoutes) {
                    $routes = array_merge($routes, $changedPermalinkRoutes);
                };

                if (!$this->explicitHomepageRoute && $homepageValues == $values) {
                    continue;
                }

                $routes[] = array(
                    'pattern' => $pattern . $pageValues["permalink"],
                    'controller' => $this->frontController,
                    'method' => array('get'),
                    'value' => $values,
                    'bind' => $this->bindPrefix . $routeName,
                );
            }
        }

        return $routes;
    }

    private function addChangedPermalinks($routeName, $pattern, $values)
    {
        if (!array_key_exists("changed_permalinks", $values)) {
            return null;
        }

        $routes = array();
        foreach ($values["changed_permalinks"] as $permalink) {
            $routes[] = array(
                'pattern' => $pattern . $permalink,
                'controller' => 'Controller\Cms\MissingPermalinkController::redirectAction',
                'method' => array('get'),
                'value' => array(
                    'route_name' => $routeName,
                ),
            );
        }

        return $routes;
    }
}