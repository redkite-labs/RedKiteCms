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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ViewRenderer;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * This object renders one or more twig templates.
 *
 * Each template is defined by an array which has two options
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ViewRenderer implements ViewRendererInterface
{
    /** @var EngineInterface */
    protected $templating;

    /**
     * Constructor
     *
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * Renders a set of twig templates.
     *
     * The views are passed as an array argument. Valid arrays are:
     *
     *      array(
     *          "view" => "...",
     *          "options" => array(
     *              "optionName" => "optionValue",
     *          )
     *      )
     *
     * renders the view specified by the homonym key with the given options
     *
     *      array(
     *          "views" => array(
     *              array(
     *                  "view" => "...",
     *                  "options" => array(
     *                      "optionName" => "optionValue",
     *                  )
     *              ),
     *              array(
     *                  "view" => "...",
     *                  "options" => array(
     *                      "optionName" => "optionValue",
     *                  )
     *              ),
     *          ),
     *      )
     *
     * renders the views specified by the views key. The "views" key is mandatory.
     *
     * When the "options" option is not specified an empty array is used as options array.
     *
     * @param  array  $views
     * @return string
     */
    public function render(array $views)
    {
        if (!array_key_exists('views', $views)) {
            $views['views'] = array($views);
        }

        $content = '';
        foreach ($views['views'] as $view) {
            $content .= $this->renderView($view);
        }

        return $content;
    }

    /**
     * Renders a view
     *
     * @param  array  $view
     * @return string
     */
    protected function renderView(array $view)
    {
        if (!array_key_exists('view', $view)) {
            return "";
        }
        $options = (array_key_exists('options', $view)) ? $view['options'] : array();

        return $this->templating->render($view['view'], $options);
    }
}
