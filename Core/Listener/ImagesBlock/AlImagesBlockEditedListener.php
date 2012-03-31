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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\ImagesBlock;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\Block\BlockEditedEvent;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\ImagesBlock\AlBlockManagerImages;


/**
 * Sets up AlphaLemon CMS
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlImagesBlockEditedListener
{
    protected $container;
    
    /**
     * Contructor
     * 
     * @param ContainerInterface $container 
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
    * Renders the 
    *
    * @param BlockEditedEvent $event
    */
    public function onBlockEdited(BlockEditedEvent $event)
    {
        if($event->getBlockManager() instanceof AlBlockManagerImages)
        {   
            
            //$response = $event->getResponse();
            
            //$json = json_decode($response->getContent(), true);
            
            $template = $this->container->get('templating')->render('AlphaLemonCmsBundle:Block:images_list.html.twig', array("alContent" => $event->getBlockManager()));
            $json = array("key" => "editorContents", "value" => $template);

            $response = new Response(json_encode($json));
            $response->headers->set('Content-Type', 'application/json');
            $event->setResponse($response);
            
            return $response;
        }
    }
}

