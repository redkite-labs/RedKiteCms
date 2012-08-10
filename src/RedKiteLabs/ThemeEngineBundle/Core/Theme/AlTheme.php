<?php
/*
 * This file is part of the AlphaLemonPageTreeBundle and it is distributed
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
 */

namespace AlphaLemon\ThemeEngineBundle\Core\Theme;

use AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate;
use AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemeCollectionBase;
use AlphaLemon\ThemeEngineBundle\Core\Exception\General\InvalidParameterException;

/**
 * AlTheme represents a theme and it is a collection of AlTemplate objects
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlTheme extends AlThemeCollectionBase implements AlThemeInterface
{
    private $templates = array();
    private $themeName = null;

    /**
     * Constructor
     *
     * @param string $themeName
     */
    public function __construct($themeName)
    {
        if (!is_string($themeName)) {
            throw new InvalidParameterException('The theme name, passed to the AlTheme object, must be a string');
        }

        $this->themeName = (!preg_match('/[\w+]Bundle$/', $themeName)) ? ucfirst($themeName) . 'Bundle' : $themeName;
    }

    /**
     * Returns the current theme name
     *
     * @return string
     */
    public function getThemeName()
    {
        return $this->themeName;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate($name)
    {
        $key = $this->normalizeKey($name);
        if(!array_key_exists($key, $this->templates)) return null;

        return $this->templates[$key];
    }

    /**
     * Adds a template object to the themes collection
     *
     * @param AlTemplate $template
     */
    public function addTemplate(AlTemplate $template)
    {
        $key = $this->normalizeKey($template->getTemplateName());
        $this->templates[$key] = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->templates);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->templates);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->templates);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return reset($this->templates);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return (current($this->templates) !== false);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->templates);
    }
}