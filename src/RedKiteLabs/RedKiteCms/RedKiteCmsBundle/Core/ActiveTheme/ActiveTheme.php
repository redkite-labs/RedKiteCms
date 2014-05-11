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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ActiveTheme;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * ActiveTheme is the object deputated to manage the website active theme
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ActiveTheme implements ActiveThemeInterface
{
    private $container = null;
    private $activeThemes = array();
    private $bootstrapVersion = null;
    private $themes;
    private $yaml;
    private $activeThemeFileName;
    private $kernel = null;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->yaml = new Yaml();
        $this->themes = $this->container->get('red_kite_labs_theme_engine.themes');
        $this->activeThemeFileName = $this->container->getParameter('red_kite_cms.active_theme_file');
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveThemeBackend()
    {
        $this->parseThemesFile();

        return $this->themes->getTheme($this->activeThemes["backend"]);
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveThemeFrontend()
    {
        $this->parseThemesFile();

        return $this->themes->getTheme($this->activeThemes["frontend"]);
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveThemeBackendBundle()
    {
        $this->parseThemesFile();

        return $this->getKernel()->getBundle($this->activeThemes["backend"]);
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveThemeFrontendBundle()
    {
        $this->parseThemesFile();

        return $this->getKernel()->getBundle($this->activeThemes["frontend"]);
    }

    /**
     * {@inheritdoc}
     */
    public function writeActiveTheme($backendThemeName = null, $frontendThemeName = null)
    {
        if (null !== $backendThemeName) {
            $this->activeThemes["backend"] = $backendThemeName;
        }

        if (null !== $frontendThemeName ) {
            $this->activeThemes["frontend"] = $frontendThemeName;
        }

        file_put_contents($this->activeThemeFileName, $this->yaml->dump($this->activeThemes));
    }

    /**
     * Returns the Twitter Bootstrap version for the theme requested as argument
     * @param  string $themeName
     * @return string
     */
    public function getThemeBootstrapVersion($themeName = null)
    {
        if (null === $themeName) {
            if (null !== $this->bootstrapVersion) {
                return $this->bootstrapVersion;
            }

            $themeName = $this->getActiveThemeBackend()->getThemeName();
        }

        $this->bootstrapVersion = $this->container->getParameter('red_kite_cms.bootstrap_version');

        if ( ! $this->container->hasParameter('red_kite_labs_theme_engine.bootstrap_themes')) {
            return $this->bootstrapVersion;
        }

        $themes = $this->container->getParameter('red_kite_labs_theme_engine.bootstrap_themes');
        if (array_key_exists($themeName, $themes)) {
            $this->bootstrapVersion = $themes[$themeName];
        }

        return $this->bootstrapVersion;
    }

    private function getKernel()
    {
        if (null === $this->kernel) {
            $this->kernel = $this->container->get('kernel');
        }

        return $this->kernel;
    }

    private function parseThemesFile()
    {
        if (!empty($this->activeThemes)) {
            return;
        }

        if ( ! file_exists($this->activeThemeFileName)) {
            $themes = is_array($this->themes) ? $this->themes : iterator_to_array($this->themes);
            $activeTheme = end($themes);
            $backendThemeName = $activeTheme->getThemeName();

            $this->writeActiveTheme($backendThemeName, $backendThemeName);

            return;
        }

        $contents = file_get_contents($this->activeThemeFileName);
        $this->activeThemes = $this->yaml->parse($contents);

        // Backward compatibility
        if ( ! is_array($this->activeThemes)) {
            $themeName = $this->activeThemes;
            $this->activeThemes = array();
            $this->writeActiveTheme($themeName, $themeName);
        }
    }
}