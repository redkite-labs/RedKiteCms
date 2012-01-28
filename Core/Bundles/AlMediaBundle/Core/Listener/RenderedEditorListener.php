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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\AlMediaBundle\Core\Listener; 

use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\Block\BlockEditorRenderedEvent;
use Symfony\Component\HttpFoundation\Response;


/**
 * Manipulates the block's editor response when the editor has been rendered 
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class RenderedEditorListener 
{
    public function onBlockEditorRendered(BlockEditorRenderedEvent $event)
    {
        try
        {
            // The response editor is returned as a json response, ElFinder file manager must be rendered 
            // as a text response
            $alContent = $event->getAlContent();            
            if($alContent->getClassName() == 'Media')
            {
                $content = json_decode($event->getResponse()->getContent());
                $event->setResponse(new Response($content[0]->{'value'}));
            }
        }
        catch(\Exception $ex)
        {
            throw $ex;
        }
    }
}
