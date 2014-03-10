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

namespace RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Json;

/**
 * Implemens some basic functions to manage the json format
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BaseJson
{
    /**
     * Returns filename contents when exists or an empty string
     *
     * @param  string $file
     * @return string
     */
    public function getFileContents($file)
    {
        $contents = "";
        if (file_exists($file)) {
            $contents = file_get_contents($file);
        }

        return  $contents;
    }

    /**
     * Decodes the json contents readed from the given file, into an array
     *
     * @param  string $file
     * @return array
     */
    public function decode($file)
    {
        $contents = $this->getFileContents($file);

        $result = array();
        if ($contents != "") {
            $result = json_decode($contents, true);
        }

        return $result;
    }

    /**
     * Encodes the given values into a json file
     *
     * @param string $file
     * @param array  $values
     */
    public function encode($file, array $values)
    {
        if ( ! empty($values)) {
            file_put_contents($file, json_encode($values));
        }
    }
}
