<?php
/*
 * This file is part of the RedKiteLabsBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\BootstrapBundle\Core\Json;

use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\BootstrapBundle\Core\Script\ScriptInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManagerGenerator;
use RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManagerInterface;

/**
 * Implemens som ebasic functions to manage the json format
 * 
 * @author RedKite Labs <info@redkite-labs.com>
 */
class JsonToolkit
{
    /**
     * Returns the retrieved contents from the given filename
     * 
     * @param string  $file
     * @return string 
     */
    public function getFileContents($file)
    {
        return (file_exists($file)) ? file_get_contents($file) : "";
    }

    /**
     * Decodes the json contents readed from the given file, into an array
     * 
     * @param string $file
     * @return array 
     */
    public function decode($file)
    {
        $contents = $this->getFileContents($file);

        return ($contents != "") ? json_decode($contents, true) : array();
    }

    /**
     * Encodes the given values into a json file
     * 
     * @param string $file
     * @param array $values 
     */
    public function encode($file, array $values)
    {
        if (!empty($values)) {
            file_put_contents($file, json_encode($values));
        }
    }
}