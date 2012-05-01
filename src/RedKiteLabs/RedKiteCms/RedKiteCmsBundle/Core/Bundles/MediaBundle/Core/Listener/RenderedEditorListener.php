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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MediaBundle\Core\Listener; 

use Symfony\Component\HttpFoundation\Response;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\Block\BlockEditorRenderedEvent;
use AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MediaBundle\Core\Block\AlBlockManagerMedia;


/**
 * Converts the standard json response into an http response due to ElFinder library requirements
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class RenderedEditorListener 
{
    public function onBlockEditorRendered(BlockEditorRenderedEvent $event)
    {
        try
        {
            $alBlockManager = $event->getAlBlockManager();        
            if($alBlockManager instanceof AlBlockManagerMedia)
            {
                $content = json_decode($event->getResponse()->getContent(), true);
                $event->setResponse(new Response($content[0]["value"]));
            }
        }
        catch(\Exception $ex)
        {
            throw $ex;
        }
    }
}
