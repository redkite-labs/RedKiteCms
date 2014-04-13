<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\RoutingGenerator;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\PageTreeCollection\PageTreeCollection;

/**
 * RoutingGenerator is a base object deputated to generate the routes required
 * to handle the website routing
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
abstract class RoutingGenerator implements RoutingGeneratorInterface
{
    private $pageTreeCollection = null;
    private $routes = array();
    private $routing = "";
    private $homePage = "";
    private $mainLanguage = "";

    /**
     * Returns a prefix for routes
     *
     * @param  string $deployBundle The name of the deploy bundle
     * @param  string $deployBundle The name of the deploy controller
     * @return string
     */
    abstract protected function defineRouteSchema($deployBundle, $deployController);

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\PageTreeCollection\PageTreeCollection $pageTreeCollection
     */
    public function __construct(PageTreeCollection $pageTreeCollection)
    {
        $this->pageTreeCollection = $pageTreeCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function getRouting()
    {
        return $this->routing;
    }

    /**
     * {@inheritdoc}
     */
    public function generateRouting($deployBundle, $deployController)
    {
        $schema = $this->defineRouteSchema($deployBundle, $deployController);

        foreach ($this->pageTreeCollection->getPages() as $pageTree) {
            $language = $pageTree->getLanguage();
            $page = $pageTree->getPage();

            $pageName = $this->fetchPageName($page);
            $languageName = $this->fetchLanguageName($language);

            $seo = $pageTree->getSeo();
            $permalink = "";
            if ($this->homePage != $pageName || $this->mainLanguage != $languageName) {
                $permalink = $seo->getPermalink();
            }

            $name = str_replace('-', '_', $languageName) . '_' . str_replace('-', '_', $pageName);
            $this->routes[] = $this->writeRoute($schema, $name, $permalink, $languageName, $pageName);
        }
        // Defines the main route
        $this->routes[] = $this->writeRoute($schema, 'home', '', $this->mainLanguage, $this->homePage); //\sprintf($schema, '', $this->mainLanguage, $this->homePage, 'home', $controllerPrefix, $prefix);
        $this->routing = implode("\n\n", $this->routes);

        return $this;
    }

    private function writeRoute($schema, $name, $permalink, $languageName, $pageName)
    {
        return \sprintf($schema, $permalink, $languageName, $pageName, $name);
    }

    private function fetchPageName($page)
    {
        $pageName = $page->getPageName();
        if ($page->getIsHome()) {
            $this->homePage = $pageName;
        }

        return $pageName;
    }

    private function fetchLanguageName($language)
    {
        $languageName = $language->getLanguageName();
        if ($language->getMainLanguage()) {
            $this->mainLanguage = $languageName;
        }

        return $languageName;
    }
}
