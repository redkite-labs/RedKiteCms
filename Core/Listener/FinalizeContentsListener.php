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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener;

use Symfony\Component\HttpFoundation\Request;
use AlRequestCore\Listener\AlRequestListener;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Sets up AlphaLemon CMS
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class FinalizeContentsListener
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

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $kernel = $this->container->get('kernel');
        if(strpos($kernel->getEnvironment(), 'alcms') === false) {
            return;
        }
        
        $response = $event->getResponse();
        
        if ($response->isRedirection()
            || ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html'))
            || 'html' !== $event->getRequest()->getRequestFormat()
        ) {
            return;
        }
		
        if('WINNT' === PHP_OS) {
            $content = $response->getContent();
            $content = preg_replace_callback('/(\<link.*?href=["|\'])\/alcms.php/s', function($matches){ return $matches[1];}, $content);
            $content = preg_replace_callback('/(\<script.*?src=["|\'])\/alcms.php/s', function($matches){ return $matches[1];}, $content);
            
            $response->setContent($content);
        }
    }
}