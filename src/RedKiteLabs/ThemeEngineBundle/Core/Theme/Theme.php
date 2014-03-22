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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\ThemeEngineBundle\Core\Theme;

use RedKiteLabs\ThemeEngineBundle\Core\Template\Template;
use RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\ThemeCollectionBase;
use RedKiteLabs\ThemeEngineBundle\Core\Exception\InvalidArgumentException;
use RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\ThemeSlotsInterface;

/**
 * Theme represents a theme and it is a collection of Template objects
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class Theme extends ThemeCollectionBase implements ThemeInterface
{
    private $templates = array();
    private $themeName = null;
    private $themeSlots = null;

    /**
     * Constructor
     *
     * @param string $themeName
     * @param \RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\ThemeSlotsInterface $templateSlots
     * @throws InvalidArgumentException
     */
    public function __construct($themeName, ThemeSlotsInterface $templateSlots)
    {
        if (!is_string($themeName)) {
            throw new InvalidArgumentException('The theme name, passed to the Theme object, must be a string');
        }
        
        $this->themeName = (!preg_match('/[\w+]Bundle$/', $themeName)) ? ucfirst($themeName) . 'Bundle' : $themeName;
        $this->themeSlots = $templateSlots;
    }

    /**
     * {@inheritdoc}
     */
    public function getThemeSlots()
    {
        return $this->themeSlots;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getThemeName()
    {
        return $this->themeName;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate($name)
    {
        $key = $this->normalizeKey($name);
        if ( ! $this->hasTemplate($name)) return null;
        
        return $this->templates[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function setTemplate($key, Template $template)
    {
        $this->templates[$key] = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTemplate($name)
    {
        $key = $this->normalizeKey($name);

        return array_key_exists($key, $this->templates);
    }

    /**
     * Returns the home template or the first one when the theme does not contains
     * an home template
     *
     * @return \RedKiteLabs\ThemeEngineBundle\Core\Template\Template
     */
    public function getHomeTemplate()
    {
        $templateName = 'home';
        if ( ! $this->hasTemplate($templateName)) {

            // Returns the first one in alphabetic order
            $templates = array_keys($this->templates);
            sort($templates);
            $templateName = $templates[0];
        }

        return $this->templates[$templateName];
    }

    /**
     * Adds a template object to the themes collection
     *
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Template\Template $template
     */
    public function addTemplate(Template $template)
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
     *
     * @codeCoverageIgnore
     */
    public function next()
    {
        return next($this->templates);
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
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
