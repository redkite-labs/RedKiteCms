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

use RedKiteLabs\ThemeEngineBundle\Core\PageTree\PageTree;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * Adds the renderSlot function to Twig engine
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 * @codeCoverageIgnore
 */
class SlotRendererExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    // TODO: To be redefined when ThemeEngine will be revisited
    public function render($slotName = null)
    {
        return "";
        
        /*
        $this->checkSlotName($slotName);

        try
        {
            $result = '';

            if (!$this->container->has('red_kite_labs_theme_engine.page_tree')) {
                return $result;
            }
            
            $slotContents = $this
                ->container
                ->get('red_kite_labs_theme_engine.page_tree')
                ->getPageBlocks()
                ->getSlotBlocks($slotName);
            if(count($slotContents) > 0)
            {
                foreach($slotContents as $contents)
                {
                    if(\array_key_exists('RenderView', $contents))
                    {
                        $params = (isset($contents['RenderView']['params'])) ? $contents['RenderView']['params'] : array();
                        $result .= $this->container->get('templating')->render($contents['RenderView']['view'], $params);
                    }
                    else if(\array_key_exists('Content', $contents))
                    {
                        $result .= $contents['Content'];
                    }
                }
            }

            return $result;
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }*/
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

    /**
     * Validates the slot name
     *
     * @param string $slotName
     * @throws \InvalidArgumentException
     */
    protected function checkSlotName($slotName)
    {
        if (null === $slotName) {
            throw new \InvalidArgumentException("renderSlot function requires a valid slot name to render the contents");
        }

        if (!is_string($slotName)) {
            throw new \InvalidArgumentException("renderSlot function requires a string as argument to identify the slot name");
        }
    }
}
