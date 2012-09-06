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
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;

/**
 * Defines the BlockEditorRenderedEvent event
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class BlockEditorRenderedEvent extends Event
{
    private $response = null;
    private $alBlockManager = null;

    public function __construct(Response $response, AlContentManagerBase $alBlockManager)
    {
        $this->response = $response;
        $this->alBlockManager = $alBlockManager;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(Response $v)
    {
        $this->response = $v;
    }

    public function getAlBlockManager()
    {
        return $this->alBlockManager;
    }
}
