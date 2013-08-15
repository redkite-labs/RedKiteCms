<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\ThemeEngineBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAsset;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Yaml\Yaml;

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
            $info["info"] = \array_intersect_key($t["info"], \array_flip($this->container->getParameter('red_kite_labs_theme_engine.info_valid_entries')));
        }

        $screenshotPath = 'images/screenshot.png';
        $fileName = \sprintf('%s/Resources/public/%s', $asset->getRealPath(), $screenshotPath);
        $info["screenshot"] = (file_exists($fileName)) ? "/" . $asset->getAbsolutePath() . "/" . $screenshotPath : $this->retriveDefaultScreenshot();

        if ($buttons) $info['buttons'] = $buttons;

        return $info;
    }

    protected function retriveDefaultScreenshot()
    {
        $fileName = '@RedKiteLabsThemeEngineBundle/Resources/public/images/screenshot.png';
        $screenShotAsset = new AlAsset($this->container->get('kernel'), $fileName);

        return '/' . $screenShotAsset->getAbsolutePath();
    }

    protected function renderThemesPanel()
    {
        $values = array();

        $activeTheme = $this->getActiveTheme()->getActiveTheme();
        $themes = $this->container->get('red_kite_labs_theme_engine.themes');
        foreach($themes as $theme)
        {
            if ($activeTheme !== null && $activeTheme == $theme->getThemeName()) {
                $values['active_theme'] = $this->retrieveThemeInfo($theme, false);

                continue;
            }

            $values['available_themes']["themes"][] = $this->retrieveThemeInfo($theme);
        }

        $responseContent = $this->container->get('templating')->renderResponse($this->container->getParameter('red_kite_labs_theme_engine.themes_panel.base_theme'), array(
            'base_template' => $this->container->getParameter('red_kite_labs_theme_engine.base_template'),
            'panel_sections' => $this->container->getParameter('red_kite_labs_theme_engine.themes_panel.theme_section'),
            'theme_skeleton' => $this->container->getParameter('red_kite_labs_theme_engine.themes_panel.theme_skeleton'),
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

