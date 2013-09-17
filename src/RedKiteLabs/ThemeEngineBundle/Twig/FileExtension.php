<?php
/**
 * This file is part of the RedKiteLabsThemeEngineBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\ThemeEngineBundle\Twig;

/**
 * Adds some functions to Twig engine to manupulate strings from twig
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class FileExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'file_open' => new \Twig_Function_Method($this, 'openFile', array('is_safe' => array('html'),)),
        );
    }

    public function openFile($file, $maxLength = null)
    {
        if ( !file_exists($file)) {
            return sprintf('The file %s has not been found', $file);
        }
        
        $contents = file_get_contents($file);
        if (null !== $maxLength) {
            $contents = substr($contents, 0, $maxLength) . ' ...<br /><span class="label label-info">RedKite CMS: file content truncated</span>';
        }
        
        return $contents;
    }

    public function getName()
    {
        return 'file';
    }
}
