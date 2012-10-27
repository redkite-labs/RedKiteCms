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
 * Defines the BlockEditorRenderedEvent event
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class BlockEditorRenderingEvent extends Event
{
    private $container = null;
    private $request = null;
    private $blockManager = null;
    private $editor = null;

    public function __construct(ContainerInterface $container, Request $request, AlBlockManagerInterface $blockManager)
    {
        $this->container = $container;
        $this->request = $request;
        $this->blockManager = $blockManager;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(Request $v)
    {
        $this->request = $v;
    }

    public function getBlockManager()
    {
        return $this->blockManager;
    }
    
    public function setBlockManager(AlBlockManagerInterface $v)
    {
        $this->blockManager = $v;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer(ContainerInterface $v)
    {
        $this->container = $v;
    }

    public function getEditor()
    {
        return $this->editor;
    }

    public function setEditor($v)
    {
        $this->editor = $v;
    }
}
