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

namespace AlphaLemon\ThemeEngineBundle\Twig;

/**
 * Adds some functions to Twig engine to manupulate strings from twig
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class FileExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'file_open' => new \Twig_Function_Method($this, 'openFile', array('is_safe' => array('html'),)),
        );
    }

    public function openFile($file)
    {
        return file_get_contents($file);
    }

    public function getName()
    {
        return 'file';
    }
}
