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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\LinksFixer;

/**
 * Description of AlLinksFixer
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlLinksFixer {
    
    private $container;
    
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function transform($content, $prefix = '', $suffix = '')
    {
        return $this->transformLinks($content, $prefix, $suffix);
    }
    
    public function replace($contents, $search, $replace)
    {
        return $this->replaceLinks($content, $prefix, $suffix);
    }
    
    protected function transformLinks($content, $search, $replace)
    {
        $container = $this->container;
        $content = preg_replace_callback('/(\<a[\s+\w+]href=[\"\'])(.*?)([\"\'])/s', function ($matches) use($container, $prefix, $suffix) {
            
            $url = $matches[2];            
            try
            {
                $tmpUrl = (empty($match) && substr($url, 0, 1) != '/') ? '/' . $url : $url;
                $params = $container->get('router')->match($tmpUrl); 
                
                $url = (!empty($params)) ? $prefix . $url . $suffix : $url;
            }
            catch(\Symfony\Component\Routing\Exception\ResourceNotFoundException $ex)
            {
                // Not internal route the link remains the same
            }
            
            return $matches[1] . $url . $matches[3];
        }, $content);
        
        return $content;
    }
    
    protected function replaceLinks($content, $search, $replace)
    {
        return preg_replace('/(' . $search . ')/s', $replace, $content);
    }
}