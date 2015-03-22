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

use Symfony\Component\Filesystem\Filesystem;

/**
 * This object is deputed to generate an empty website from a given theme
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Configuration
 */
class SiteBuilder
{
    /**
     * @type string
     */
    private $rootDir = "";
    /**
     * @type string
     */
    private $appDir = "";
    /**
     * @type string
     */
    private $siteName = "";
    /**
     * @type string
     */
    private $themeName = "SimpleTheme";
    /**
     * @type string
     */
    private $pageName = "homepage";
    /**
     * @type bool
     */
    private $handleTheme = false;

    /**
     * Constructor
     *
     * @param $rootDir
     * @param $siteName
     */
    public function __construct($rootDir, $siteName)
    {
        $this->rootDir = $rootDir;
        $this->siteName = $siteName;
        $this->filesystem = new Filesystem();
    }

    /**
     * Sets the theme name
     *
     * @param string $theme
     */
    public function theme($themeName)
    {
        $this->themeName = $themeName;

        return $this;
    }

    /**
     * Build the website
     *
     * @return $this
     */
    public function build()
    {
        $this->appDir = $this->rootDir . '/app';
        $siteDir = $this->appDir . '/data/' . $this->siteName;
        $pagesDir = $siteDir . '/pages/pages';
        $rolesDir = $siteDir . '/roles';
        $slotsDir = $siteDir . '/slots';
        $usersDir = $siteDir . '/users';

        $folders = array(
            $pagesDir,
            $rolesDir,
            $slotsDir,
            $usersDir,
        );

        $this->filesystem->mkdir($folders);

        $this->createConfiguration($siteDir);
        $this->createSite($siteDir);
        $this->createRoles($rolesDir);
        $this->createUsers($usersDir);

        $this->filesystem->touch($siteDir . '/incomplete.json');

        return $this;
    }

    /**
     * @param $siteDir
     */
    private function createConfiguration($siteDir)
    {
        $this->filesystem->copy($this->appDir . '/RedKiteCms.php', $siteDir . '/RedKiteCms.php');
    }

    /**
     * @param $siteDir
     */
    private function createSite($siteDir)
    {
        $site = array(
            "theme" => $this->themeName,
            "homepage" => "$this->pageName",
            "locale_default" => "en_GB",
            "homepage_permalink" => "en-gb-homepage",
            "languages" => array(
                "en_GB",
            ),
            "handled_theme" => $this->handleTheme ? $this->themeName : "",
        );

        $this->filesystem->dumpFile($siteDir . '/site.json', json_encode($site));
    }

    /**
     * @param $rolesDir
     */
    private function createRoles($rolesDir)
    {
        $roles = array(
            "ROLE_ADMIN",
        );

        $this->filesystem->dumpFile($rolesDir . '/roles.json', json_encode($roles));
    }

    /**
     * @param $usersDir
     */
    private function createUsers($usersDir)
    {
        $users = array(
            "admin" => array(
                "roles" => array(
                    "ROLE_ADMIN"
                ),
                "password" => "RVxE/NkQGEhSimsAzsmSIwDv1p+lhP5SDT6Gfnh8QS32yk7W6A+pW5GXUBxJ3ud9La5khARoH2uQ5VRYkPG/Fw==",
                "salt" => "q4mfgrnsn2occ4kw4k008cskkwkg800",
            ),
        );

        $this->filesystem->dumpFile($usersDir . '/users.json', json_encode($users));
    }

    /**
     * Sets to true the handle theme configuration parameter
     *
     * @return $this
     */
    public function handleTheme()
    {
        $this->handleTheme = true;

        return $this;
    }
}