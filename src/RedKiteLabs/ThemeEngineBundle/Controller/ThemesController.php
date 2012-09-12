<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 *
 */

namespace AlphaLemon\ThemeEngineBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use AlphaLemon\CmsBundle\Controller\CmsController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Response;

use AlphaLemon\ThemeEngineBundle\Core\Model\AlThemeQuery;
use AlphaLemon\ThemeEngineBundle\Core\ThemeManager\AlThemeManager;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\Config\FileLocator;

use AlphaLemon\AlValumUploaderBundle\Core\Options\AlValumUploaderOptionsBuilder;
use AlphaLemon\ThemeEngineBundle\Core\Autoloader\Base\BundlesAutoloaderComposer;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;
use Symfony\Component\DependencyInjection\ContainerAware;

class ThemesController extends ContainerAware
{
    public function showAction()
    {
        return $this->renderThemesPanel();
    }

    public function activateThemeAction($themeName)
    {
        try
        {
            $this->getActiveTheme()->writeActiveTheme($themeName);

            return $this->renderThemesPanel();
        }
        catch(Exception $e)
        {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    protected function retrieveThemeInfo($theme, $buttons = true)
    {
        $themeName = $theme->getThemeName();
        $asset = new AlAsset($this->container->get('kernel'), $themeName);

        $info = array('theme_title' => $themeName);
        $fileName = \sprintf('%s/Resources/data/info.yml', $asset->getRealPath());
        if(file_exists($fileName))
        {
            $t = Yaml::parse($fileName);
            $info["info"] = \array_intersect_key($t["info"], \array_flip($this->container->getParameter('althemes.info_valid_entries')));
        }

        $screenshotPath = 'images/screenshot.png';
        $fileName = \sprintf('%s/Resources/public/%s', $asset->getRealPath(), $screenshotPath);
        $info["screenshot"] = (file_exists($fileName)) ? $asset->getAbsolutePath() . $screenshotPath : $this->retriveDefaultScreenshot();

        if ($buttons) $info['buttons'] = $buttons;

        return $info;
    }

    protected function retriveDefaultScreenshot()
    {
        $fileName = '@AlphaLemonThemeEngineBundle/Resources/public/images/screenshot.png';
        $screenShotAsset = new AlAsset($this->container->get('kernel'), $fileName);

        return '/' . $screenShotAsset->getAbsolutePath();
    }

    protected function renderThemesPanel()
    {
        $values = array();

        $activeTheme = $this->getActiveTheme()->getActiveTheme();
        $themes = $this->container->get('alphalemon_theme_engine.themes');
        foreach($themes as $theme)
        {
            if ($activeTheme !== null && $activeTheme == $theme->getThemeName()) {
                $values['active_theme'] = $this->retrieveThemeInfo($theme, false);

                continue;
            }

            $values['available_themes']["themes"][] = $this->retrieveThemeInfo($theme);
        }

        $responseContent = $this->container->get('templating')->renderResponse($this->container->getParameter('althemes.base_theme_manager_template'), array(
            'base_template' => $this->container->getParameter('althemes.base_template'),
            'panel_sections' => $this->container->getParameter('althemes.panel_sections_template'),
            'theme_skeleton' => $this->container->getParameter('althemes.theme_skeleton_template'),
            'stylesheets' => array(),
            'values' => $values,
            'valum' => array()
        ));

        return $responseContent;
    }

    protected function getActiveTheme()
    {
        return $this->container->get('alphalemon_theme_engine.active_theme');
    }
}

