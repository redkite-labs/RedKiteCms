<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\ThemesCollectionWrapper;

use AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\ThemesCollectionWrapper\Exception\NonExistentTemplateException;

/**
 * Wraps the themes collection object to provide an easy way to deal with themes
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
     * @param AlThemesCollection $themes
     * @param AlTemplateManager  $templateManager
     */
    public function __construct(AlThemesCollection $themes, AlTemplateManager $templateManager)
    {
        $this->themes = $themes;
        $this->templateManager = $templateManager;
    }

    /**
     * Returns the managed themes collection
     *
     * @return AlThemesCollection
     */
    public function getThemesCollection()
    {
        return $this->themes;
    }

    /**
     * Returns the managed template manager
     *
     * @return AlTemplateManager
     */
    public function getTemplateManager()
    {
        return $this->templateManager;
    }

    /**
     * Returns the theme from its name
     *
     * @param  string  $themeName
     * @return AlTheme
     */
    public function getTheme($themeName)
    {
        return $this->themes->getTheme($themeName);
    }

    /**
     * Returns the template from theme name and the template name
     *
     * @param  string     $themeName
     * @param  string     $templateName
     * @return AlTemplate
     */
    public function getTemplate($themeName, $templateName)
    {
        $theme = $this->getTheme($themeName);

        return $theme->getTemplate($templateName);
    }

    /**
     * Assigns the template retrieved from theme name and the template name to the template manager
     *
     * @param  string            $themeName
     * @param  string            $templateName
     * @return AlTemplateManager
     */
    public function assignTemplate($themeName, $templateName)
    {
        $template = $this->getTemplate($themeName, $templateName);
        if (null === $template) {
            throw new NonExistentTemplateException(sprintf('The template "%s" does not seem to belong the "%s" theme. Please check your template\'s configuration', $templateName, $themeName));
        }

        $this->templateManager->setTemplate($template);

        return $this->templateManager;
    }
}
