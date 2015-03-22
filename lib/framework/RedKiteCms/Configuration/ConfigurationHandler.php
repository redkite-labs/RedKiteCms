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

namespace RedKiteCms\Configuration;

use RedKiteCms\Exception\General\RuntimeException;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This object is deputed to handle the RedKite CMS configuration
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Configuration
 *
 * @method ConfigurationHandler rootDir() Returns the application root dir
 * @method ConfigurationHandler appDir() Returns the application app dir
 * @method ConfigurationHandler logDir() Returns the application logs dir
 * @method ConfigurationHandler dataDir() Returns the application data dir
 * @method ConfigurationHandler siteDir() Returns the application site dir
 * @method ConfigurationHandler usersDir() Returns the application users dir
 * @method ConfigurationHandler webDir() Returns the application web dir
 * @method ConfigurationHandler cacheDir() Returns the application cache dir
 * @method ConfigurationHandler corePluginsDir() Returns the application core plugins dir
 * @method ConfigurationHandler customPluginsDir() Returns the custom plugins root dir
 * @method ConfigurationHandler pagesDir() Returns the pages dir
 * @method ConfigurationHandler pagesRootDir() Returns the pages root dir
 * @method ConfigurationHandler pagesRemovedDir() Returns the pages removed dir
 * @method ConfigurationHandler uploadAssetsDir() Returns the backend uploads dir
 * @method ConfigurationHandler uploadAssetsDirProduction() Returns the production uploads dir
 * @method ConfigurationHandler absoluteUploadAssetsDir() Returns the assets absolute dir
 * @method ConfigurationHandler coreConfigDir() Returns the core configuration dir
 * @method ConfigurationHandler customConfigDir() Returns the custom configuration dir
 * @method ConfigurationHandler webDirname() Returns the web folder name
 * @method ConfigurationHandler siteName() Returns the current site name
 * @method ConfigurationHandler isProduction() Returns when the CMS is used in production
 * @method ConfigurationHandler isTheme() Returns when handling a theme
 * @method ConfigurationHandler siteInfo() Returns the site information
 * @method ConfigurationHandler configuration() Returns the information about current configuration
 * @method ConfigurationHandler language() Returns the current language
 * @method ConfigurationHandler country() Returns the current country
 */


class ConfigurationHandler
{
    const VERSION = "2.0.0-alpha";

    /**
     * @type string
     */
    private $rootDir;
    /**
     * @type string
     */
    private $appDir;
    /**
     * @type string
     */
    private $logDir;
    /**
     * @type string
     */
    private $dataDir;
    /**
     * @type string
     */
    private $siteDir;
    /**
     * @type string
     */
    private $usersDir;
    /**
     * @type string
     */
    private $webDir;
    /**
     * @type string
     */
    private $cacheDir;
    /**
     * @type string
     */
    private $corePluginsDir;
    /**
     * @type string
     */
    private $customPluginsDir;
    /**
     * @type string
     */
    private $pagesDir;
    /**
     * @type string
     */
    private $pagesRootDir;
    /**
     * @type string
     */
    private $pagesRemovedDir;
    /**
     * @type string
     */
    private $siteName;
    /**
     * @type bool
     */
    private $isProduction;
    /**
     * @type bool
     */
    private $isTheme = false;
    /**
     * @type array
     */
    private $siteInfo;
    /**
     * @type string
     */
    private $coreConfigDir;
    /**
     * @type string
     */
    private $customConfigDir;
    /**
     * @type array
     */
    private $configuration;
    /**
     * @type string
     */
    private $language;
    /**
     * @type string
     */
    private $country;
    /**
     * @type string
     */
    private $webDirname = 'web';
    /**
     * @type string
     */
    private $absoluteUploadAssetsDir = '/upload/assets';
    /**
     * @type string
     */
    private $uploadAssetsDir;
    /**
     *
     * @type string
     */
    private $uploadAssetsDirProduction;
    /**
     * @type null|string
     */
    private $homepageTemplate = null;

    /**
     * Constructor
     *
     * @param string $rootDir
     * @param string $siteName
     */
    public function __construct($rootDir, $siteName)
    {
        $this->rootDir = $rootDir;
        $this->siteName = $siteName;
        $this->checkWhenInProduction();
        $this->checkWhenIsTheme();
    }

    /**
     * Returns the application version number
     * @return string
     */
    public static function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Boots RedKite CMS configuration
     */
    public function boot()
    {
        $this->initPaths();
        $this->readConfiguration();
        $this->fetchSiteInfo();
    }

    private function checkWhenInProduction()
    {
        $this->isProduction = !(strpos($_SERVER["REQUEST_URI"], '/backend') !== false);
    }

    private function checkWhenIsTheme()
    {
        if (preg_match('/(.*?)\.theme/', $this->siteName, $match)) {
            $this->isTheme = true;
        }
    }

    private function initPaths()
    {
        $this->appDir = $this->rootDir . '/app';
        $this->logDir = $this->appDir . '/logs';
        $this->webDir = $this->rootDir . '/' . $this->webDirname;
        $this->cacheDir = $this->appDir . '/cache';
        $this->dataDir = $this->appDir . '/data';
        $this->siteDir = $this->dataDir . '/' . $this->siteName;
        $this->usersDir = $this->siteDir . '/users';
        $this->absoluteUploadAssetsDir .= '/' . $this->siteName;
        $this->uploadAssetsDirProduction = $this->webDir . $this->absoluteUploadAssetsDir . '/production';
        $this->absoluteUploadAssetsDir .= '/backend';
        $this->uploadAssetsDir = $this->webDir . $this->absoluteUploadAssetsDir;
        $this->corePluginsDir = $this->rootDir . '/lib/plugins/RedKiteCms';
        $this->customPluginsDir = $this->appDir . '/plugins/RedKiteCms';
        $this->coreConfigDir = $this->rootDir . '/lib/config';
        $this->customConfigDir = $this->appDir . '/config';
        $this->pagesRootDir = $this->siteDir . '/pages';
        $this->pagesDir = $this->pagesRootDir . '/pages';
        $this->pagesRemovedDir = $this->pagesRootDir . '/removed';

        $this->createImagesDir($this->uploadAssetsDir);
        $this->createImagesDir($this->uploadAssetsDirProduction);
    }

    private function createImagesDir($imagesDir)
    {
        if (is_dir($imagesDir)) {
            return;
        }

        $folders = array(
            $imagesDir,
            $imagesDir . '/media',
            $imagesDir . '/files',
        );

        $fs = new Filesystem();
        $fs->mkdir($folders);
    }

    private function readConfiguration()
    {
        $coreConfiguration = $this->parse($this->coreConfigDir);
        $customConfiguration = $this->parse($this->customConfigDir);

        $this->configuration = array_merge_recursive($coreConfiguration, $customConfiguration);
    }

    private function parse($dir)
    {
        $configuration = array();
        $finder = new Finder();
        $files = $finder->files()->name('*.json')->in($dir);
        foreach ($files as $file) {
            $file = (string)$file;
            $fileName = basename($file, '.json');
            $jsonAssets = str_replace('%web_dir%', $this->webDir, file_get_contents($file));
            $assets = json_decode($jsonAssets, true);
            if (null === $assets) {
                $assets = array();
            }
            $configuration[$fileName] = $assets;
        }

        return $configuration;
    }

    private function fetchSiteInfo()
    {
        $this->siteInfo = json_decode(FilesystemTools::readFile($this->siteDir . '/site.json'), true);
        $fullLanguage = explode('_', $this->siteInfo["locale_default"]);
        $this->language = $fullLanguage[0];
        $this->country = $fullLanguage[1];
    }

    /**
     * Magic method that returns configuration values
     *
     * @param string $name
     * @param string $params
     */
    public function __call($name, $params)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        if (array_key_exists($name, $this->configuration)) {
            return $this->configuration[$name];
        }

        if (array_key_exists($name, $this->configuration["general"])) {
            return $this->configuration["general"][$name];
        }

        throw new RuntimeException(sprintf('Method "%s" does not exist for ConfigurationHandler object', $name));
    }

    /**
     * Configure the configuration options
     *
     * @param array $options
     */
    public function setConfigurationOptions(array $options = array())
    {
        $resolver = new OptionsResolver();
        $resolver->setOptional(
            array(
                'web_dir',
                'uploads_dir',
            )
        );
        $resolver->resolve($options);

        if (array_key_exists('web_dir', $options)) {
            $this->webDirname = $options['web_dir'];
        }

        if (array_key_exists('uploads_dir', $options)) {
            $this->absoluteUploadAssetsDir = $options['uploads_dir'];
        }
    }

    /**
     * Returns true when the website is a theme
     *
     * @return bool

    public function isTheme()
    {
        return $this->isTheme;
    }*/

    /**
     * Returns the assets for the requested type
     *
     * @param $type
     * @return array
     */
    public function getAssetsByType($type)
    {
        if (!array_key_exists($type, $this->configuration["assets"])) {
            return array();
        }

        return $this->configuration["assets"][$type];
    }

    /**
     * Returns the plugin folders
     *
     * @return array
     */
    public function pluginFolders()
    {
        return array(
            $this->corePluginsDir,
            $this->customPluginsDir,
        );
    }

    public function handledTheme()
    {
        return $this->siteInfo["handled_theme"];
    }

    public function theme()
    {
        return $this->siteInfo["theme"];
    }

    public function homepagePermalink()
    {
        return $this->siteInfo["homepage_permalink"];
    }

    public function defaultLanguage()
    {
        return $this->siteInfo["locale_default"];
    }

    public function languages()
    {
        return $this->siteInfo["languages"];
    }

    /**
     * Returns the homepage template
     *
     * @return string
     */
    public function homepageTemplate()
    {
        if (null === $this->homepageTemplate) {
            $homepageFile = $this->pagesDir . "/" . $this->homepage() . '/page.json';
            $page = json_decode(FilesystemTools::readFile($homepageFile), true);
            $this->homepageTemplate = $page["template"];
        }

        return $this->homepageTemplate;
    }

    public function homepage()
    {
        return $this->siteInfo["homepage"];
    }
}