<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\ThemesCollectionWrapper;

use AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager;
use RedKiteLabs\RedKiteCmsBundle\Core\ThemesCollectionWrapper\Exception\NonExistentTemplateException;

/**
 * Handles the themes collection object to provide an easy way to deal with themes
 * and templates
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlThemesCollectionWrapper
{
    private $themes;
    private $templateManager;

    /**
     * Constructor
     *
     * @param \AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection  $themes
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager $templateManager
     */
    public function __construct(AlThemesCollection $themes, AlTemplateManager $templateManager)
    {
        $this->themes = $themes;
        $this->templateManager = $templateManager;
    }

    /**
     * Returns the managed themes collection
     *
     * @return \AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection
     */
    public function getThemesCollection()
    {
        return $this->themes;
    }

    /**
     * Returns the managed template manager
     *
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager
     */
    public function getTemplateManager()
    {
        return $this->templateManager;
    }

    /**
     * Returns the theme from its name
     *
     * @param  string                                           $themeName
     * @return \AlphaLemon\ThemeEngineBundle\Core\Theme\AlTheme
     */
    public function getTheme($themeName)
    {
        return $this->themes->getTheme($themeName);
    }

    /**
     * Returns the template from theme name and the template name
     *
     * @param  string                                                 $themeName
     * @param  string                                                 $templateName
     * @return \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate
     */
    public function getTemplate($themeName, $templateName)
    {
        $theme = $this->getTheme($themeName);

        return $theme->getTemplate($templateName);
    }

    /**
     * Assigns the template retrieved from theme name and the template name to the template manager
     *
     * @param  string                                                                  $themeName
     * @param  string                                                                  $templateName
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager
     * @throws NonExistentTemplateException
     */
    public function assignTemplate($themeName, $templateName)
    {
        $template = $this->getTemplate($themeName, $templateName);
        if (null === $template) {
            $exception = array(
                'message' => 'The template "%templateName%" does not seem to belong the "%themeName%" theme. Please check your template\'s configuration',
                'parameters' => array(
                    '%templateName%' => $templateName,
                    '%themeName%' => $themeName,
                ),
            );
            throw new NonExistentTemplateException(json_encode($exception));
        }

        $this->templateManager->setTemplate($template);

        return $this->templateManager;
    }
}
