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

namespace RedKiteLabs\ThemeEngineBundle\Twig;

/**
 * Adds the renderSlot function to Twig engine
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 * @codeCoverageIgnore
 */
class SlotRendererExtension extends \Twig_Extension
{
    public function render($slotName = null)
    {
        return "";
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'renderSlot' => new \Twig_Function_Method($this, 'render', array(
                'is_safe' => array('html'),
            )),
        );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'slotRenderer';
    }
}
