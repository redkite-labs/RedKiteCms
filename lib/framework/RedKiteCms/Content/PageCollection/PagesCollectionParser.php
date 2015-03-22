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
use Symfony\Component\Finder\Finder;

/**
 * Class PagesCollectionParser is the object deputed to handle the website pages information.
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Content\Page
 */
class PagesCollectionParser extends PageCollectionBase
{
    /**
     * @type array
     */
    private $pages = array();
    /**
     * @type array
     */
    private $sitemap = array();
    /**
     * @type null|string
     */
    private $currentLanguage = null;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     */
    public function __construct(ConfigurationHandler $configurationHandler)
    {
        parent::__construct($configurationHandler);

        $this->currentLanguage = $this->configurationHandler->language() . '_' . $this->configurationHandler->country();
    }

    /**
     * Return the website pages definitions
     * @return array
     */
    public function pages()
    {
        return array_values($this->pages);
    }

    /**
     * Returns the definition for the requested page
     * @param string $pageName
     *
     * @return null|array
     */
    public function page($pageName)
    {
        if (!array_key_exists($pageName, $this->pages)) {
            return null;
        }

        return $this->pages[$pageName];
    }

    /**
     * Returns the website seo sitemap
     *
     * @return array
     */
    public function sitemap()
    {
        return $this->sitemap;
    }

    /**
     * Sets the page language
     * @param $language
     *
     * @return $this
     */
    public function currentLanguage($language)
    {
        $this->currentLanguage = $language;

        return $this;
    }

    /**
     * Return the permalinks for the given language. By default this method returns the permalinks for the handled
     * language
     *
     * @param null|string $language
     *
     * @return array
     */
    public function permalinksByLanguage($language = null)
    {
        $result = array();

        if (null === $language) {
            $language = $this->currentLanguage;
        }

        foreach ($this->pages as $page) {
            foreach ($page["seo"] as $pageAttribute) {
                if ($pageAttribute["language"] != $language) {
                    continue;
                }

                $result[] = $pageAttribute["permalink"];
            }
        }

        return $result;
    }

    /**
     * Parses the page to retrieve their definitions
     *
     * @return $this
     */
    public function parse()
    {
        $finder = new Finder();
        $pages = $finder->directories()->depth(0)->sortByName()->in($this->pagesDir);
        foreach ($pages as $page) {
            $pageDir = (string)$page;
            $pageName = basename($pageDir);
            $pageDefinitionFile = $pageDir . '/' . $this->pageFile;
            if (!file_exists($pageDefinitionFile)) {
                continue;
            }

            $seoDefinition = $this->fetchSeoDefinition($this->pagesDir . '/' . $pageName, $this->seoFile);
            $pageDefinition = json_decode(file_get_contents($pageDefinitionFile), true);
            $pageDefinition["seo"] = $seoDefinition;
            $pageDefinition["isHome"] = $this->configurationHandler->homepage() == $pageName;

            $this->pages[$pageName] = $pageDefinition;
        }

        return $this;
    }

    private function fetchSeoDefinition($dir, $seoFile)
    {
        $seo = array();
        $languages = $this->configurationHandler->languages();
        foreach ($languages as $language) {
            $languageDir = $dir . '/' . $language;
            $languageName = basename($languageDir);
            $seoDefinitionFile = $languageDir . '/' . $seoFile;

            $value = json_decode(file_get_contents($seoDefinitionFile), true);
            $value["language"] = $languageName;
            $seo[] = $value;

            $this->sitemap[$value["permalink"]] = array(
                "sitemap_frequency" => $value["sitemap_frequency"],
                "sitemap_priority" => $value["sitemap_priority"],
            );
        }

        return $seo;
    }
} 