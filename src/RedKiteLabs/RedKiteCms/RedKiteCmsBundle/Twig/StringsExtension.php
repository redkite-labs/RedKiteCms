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

namespace AlphaLemon\AlphaLemonCmsBundle\Twig;

/**
 * Adds some functions to Twig engine to manupulate strings from twig
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class StringsExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'left' => new \Twig_Function_Method($this, 'left', array('is_safe' => array('html'),)),
            'right' => new \Twig_Function_Method($this, 'right', array('is_safe' => array('html'),)),
            'truncate' => new \Twig_Function_Method($this, 'truncate', array('is_safe' => array('html'),)),
        );
    }

    public function left($text, $length)
    {
        if($length <= 0)
        {
            return $text;
        }
        
        return \substr($text, 0, $length);
    }

    public function right($text, $length)
    {
        $textLength = strlen($text);

        if($length > $textLength)
        {
            return $text;
        }

        return \substr($text, $textLength - $length, $textLength);
    }

    public function truncate($text, $length = 15)
    {
        return (\strlen($text) > $length) ? $this->left($text, $length) . '...' : $text;
    }

    public function getName()
    {
        return 'strings';
    }
}
