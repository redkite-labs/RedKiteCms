<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\Block;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerInterface;

/**
 * Defines the BlockEditorRenderingEvent event
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 * @deprecated since 1.1.0
 * @codeCoverageIgnore
 */
class BlockEditorRenderingEvent extends Event
{
    private $container = null;
    private $request = null;
    private $blockManager = null;
    private $editor = null;

    /**
     * Construct
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerInterface $blockManager
     * 
     * @api
     */
    public function __construct(ContainerInterface $container, Request $request, AlBlockManagerInterface $blockManager)
    {
        $this->container = $container;
        $this->request = $request;
        $this->blockManager = $blockManager;
    }

    /**
     * Returns the handled request object
     * 
     * @return \Symfony\Component\HttpFoundation\Request
     * 
     * @api
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Sets the request
     * 
     * @param \Symfony\Component\HttpFoundation\Request $v
     * 
     * @api
     */
    public function setRequest(Request $v)
    {
        $this->request = $v;
    }

    /**
     * Returns the handled block manager object
     * 
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerInterface
     * 
     * @api
     */
    public function getBlockManager()
    {
        return $this->blockManager;
    }
    
    /**
     * Sets the block manager 
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerInterface $v
     * 
     * @api
     */
    public function setBlockManager(AlBlockManagerInterface $v)
    {
        $this->blockManager = $v;
    }

    /**
     * Returns the handled Container object
     * 
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     * 
     * @api
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Sets the Container object
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $v
     * 
     * @api
     */
    public function setContainer(ContainerInterface $v)
    {
        $this->container = $v;
    }

    /**
     * Returns the handled editor object
     * 
     * @return string
     * 
     * @api
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * Sets the current rendered editor
     * 
     * @param string $v
     * 
     * @api
     */
    public function setEditor($v)
    {
        $this->editor = $v;
    }
}