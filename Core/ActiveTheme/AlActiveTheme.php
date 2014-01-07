<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\ActiveTheme;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * AlActiveTheme is the object deputated to manage the website active theme
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlActiveTheme implements AlActiveThemeInterface
{
    private $container = null;
    private $activeTheme = null;
    private $bootstrapVersion = null;

    /**
     * Constructor
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveTheme()
    {
        if (null !== $this->activeTheme) {
            return $this->activeTheme;
        }

        $themes = $this->container->get('red_kite_labs_theme_engine.themes');
        
        $activeThemeFile = $this->getActiveThemeFile();
        if ( ! file_exists($activeThemeFile)) {
            foreach ($themes as $theme) break;

            $this->activeTheme = $theme;
            $this->writeActiveTheme($this->activeTheme->getThemeName());

            return $this->activeTheme;
        }

        $themeName = trim(file_get_contents($activeThemeFile));
        $this->activeTheme = $themes->getTheme($themeName); 
        
        return $this->activeTheme;
    }

    /**
     * {@inheritdoc}
     */
    public function writeActiveTheme($themeName)
    {
        file_put_contents($this->getActiveThemeFile(), trim($themeName));
    }

    /**
     * Returns the Twitter Bootstrap version for the theme requested as argument
     * @param string $themeName
     * @return string
     */
    public function getThemeBootstrapVersion($themeName = null)
    {
        if (null === $themeName) {
            if (null !== $this->bootstrapVersion) {
                return $this->bootstrapVersion;
            }

            $themeName = $this->getActiveTheme()->getThemeName();
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

    /**
     * Returns the file where the active theme is saved
     *
     * @return string
     */
    protected function getActiveThemeFile()
    {
        return $this->container->getParameter('red_kite_cms.active_theme_file');
    }
}
