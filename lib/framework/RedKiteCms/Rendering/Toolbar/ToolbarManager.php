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

namespace RedKiteCms\Rendering\Toolbar;


use RedKiteCms\Plugin\PluginManager;

/**
 * Class ToolbarManager is the object deputed to render the block's editor toolbar
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Toolbar
 */
class ToolbarManager
{
    /**
     * @type \RedKiteCms\Plugin\PluginManager
     */
    private $pluginManager;
    /**
     * @type \Twig_Environment
     */
    private $twig;

    /**
     * @param \RedKiteCms\Plugin\PluginManager $pluginManager
     * @param \Twig_Environment $twig
     */
    public function __construct(PluginManager $pluginManager, \Twig_Environment $twig)
    {
        $this->pluginManager = $pluginManager;
        $this->twig = $twig;
    }

    /**
     * Renders the toolbar
     *
     * @return string
     */
    public function render()
    {
        $toolbar = $this->doRender($this->pluginManager->getCorePlugins());
        $toolbar .= $this->doRender($this->pluginManager->getBlockPlugins());

        return $toolbar;
    }

    private function doRender($plugins)
    {
        $toolbar = array();
        foreach ($plugins as $plugin) {
            if (!$plugin->hasToolbar()) {
                continue;
            }

            $toolbar[] = $this->twig->render("RedKiteCms/Resources/views/Editor/Toolbar/_toolbar.html.twig");
        }

        return implode("\n", $toolbar);
    }
}