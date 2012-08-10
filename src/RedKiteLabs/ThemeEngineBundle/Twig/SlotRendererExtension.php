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
 */

namespace AlphaLemon\ThemeEngineBundle\Twig;

use AlphaLemon\PageTreeBundle\Core\PageTree\AlPageTree;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * Adds the renderSlot function to Twig engine
 *
 * @author Giansimon Diblas
 */
class SlotRendererExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function render($slotName = null)
    {
        if(null === $slotName)
        {
            throw new InvalidArgumentException("renderSlot function requires a valid slot name to render the contents");
        }

        try
        {
            $result = '';
            $slotContents = $this->container->get('al_page_tree')->getBlocks($slotName);
            if(count($slotContents) > 0)
            {
                foreach($slotContents as $contents)
                {
                    if(\array_key_exists('RenderView', $contents))
                    {
                        $params = (isset($contents['RenderView']['params'])) ? $contents['RenderView']['params'] : array();
                        $result .= $this->container->get('templating')->render($contents['RenderView']['view'], $params);
                    }
                    else if(\array_key_exists('HtmlContent', $contents))
                    {
                        $result .= $contents['HtmlContent'];
                    }
                }
            }

            return $result;
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
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
