<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Configuration;

/**
 * Defines the interface to write/read to/from a parameter -> value entity
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface AlConfigurationInterface
{
    /**
     * Reads a configuration parameter
     * 
     * @param string $parameter
     * @return string
     */
    public function read($parameter);
    
    /**
     * Writes the new value for the given parameter
     * 
     * @param string $parameter
     * @param string $value
     * @return int Affected records
     */
    public function write($parameter, $value);
}
