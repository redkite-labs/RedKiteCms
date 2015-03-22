<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Tools;

use RedKiteCms\Exception\General\RuntimeException;

/**
 * Class FilesystemTools collects several methods related to the filesystem
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Tools
 */
class FilesystemTools
{
    /**
     * Looks for the current slots dir. Slots are sought in cascade in pages folders, languages repeated slots and site
     * repeated slots
     *
     * @param $sourceDir
     * @param array $options
     *
     * @return null|string
     */
    public static function slotDir($sourceDir, array $options)
    {
        $paths = array(
            sprintf(
                '%s/pages/pages/%s/%s_%s/%s',
                $sourceDir,
                $options['page'],
                $options['language'],
                $options['country'],
                $options['slot']
            ),
            sprintf('%s/slots/%s/%s_%s', $sourceDir, $options['slot'], $options['language'], $options['country']),
            sprintf('%s/slots/%s', $sourceDir, $options['slot']),
        );

        return self::cascade($paths);
    }

    /**
     * Parses given folders and returns the first existing one
     *
     * @param array $folder
     *
     * @return null|string
     */
    public static function cascade(array $folders)
    {
        $result = null;
        foreach ($folders as $folder) {
            if (is_dir($folder)) {
                $result = $folder;

                break;
            }
        }

        return $result;
    }

    /**
     * Reads and locks a file from the filesystem
     *
     * @param $file
     *
     * @return string
     */
    public static function readFile($file)
    {
        if (!file_exists($file)) {
            return null;
        }

        $handle = fopen($file, 'r');
        if (!self::lockFile($handle, LOCK_SH | LOCK_NB)) {
            $exception = array(
                "message" => 'exception_file_cannot_be_locked_for_reading',
                "parameters" => array(
                    "%file%" => basename($file),
                )
            );
            throw new RuntimeException(json_encode($exception));
        }

        $contents = file_get_contents($file);
        self::unlockFile($handle);

        return $contents;
    }

    /**
     * Locks and write a file to the filesystem
     *
     * @param string $file
     * @param string $content
     */
    public static function writeFile($file, $content)
    {
        $handle = fopen($file, 'a');
        if (!self::lockFile($handle, LOCK_EX | LOCK_NB)) {
            $exception = array(
                "message" => 'exception_file_cannot_be_locked_for_writing',
                "parameters" => array(
                    "%file%" => basename($file),
                )
            );
            throw new RuntimeException(json_encode($exception));
        }

        file_put_contents($file, $content);
        self::unlockFile($handle);
    }

    private static function lockFile($handle, $flags)
    {
        $locked = false;
        $tries = 5;
        while ($tries > 0) {
            $locked = flock($handle, $flags);
            if (false !== $locked) {
                break;
            }

            sleep(1);
            $tries--;
        }

        return $locked;
    }

    private static function unlockFile($handle)
    {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}