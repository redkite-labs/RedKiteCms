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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\Block;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;

/**
 * Defines the BlockEditorRenderedEvent event
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class BlockEditorRenderedEvent extends Event
{
    private $response = null;
    private $alBlock = null;
    
    public function __construct(Response $response, AlBlock $alBlock)
    {
        $this->response = $response;
        $this->alBlock = $alBlock;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function setResponse(Response $v)
    {
        $this->response = $v;
    }
    
    public function getAlBlock()
    {
        return $this->alBlock;
    }
}