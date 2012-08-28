<?php
/*
 * This file is part of the AlphaLemonPageTreeBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 *
 */

namespace AlphaLemon\PageTreeBundle\Core\Tools;

use AlphaLemon\PageTreeBundle\Zip;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * A set of useful methods
 */
class AlToolkit
{
    /**
     * Executes a command
     *
     * @param KernelInterface $kernel
     * @param string/array $commands
     * @return boolean
     */
    public static function executeCommand($appDir, $cmd)
    {
        $phpFinder = new PhpExecutableFinder();
        $php = escapeshellarg($phpFinder->find());
        $console = escapeshellarg($appDir . '/console');

        $process = new Process($php.' '.$console.' '.$cmd);
        $process->run();
    }

    /**
     * Translates a message from the container
     *
     * @deprecated
     *
     * @param ContainerInterface $container
     * @param string $message
     * @param array $tokens
     * @param string $dictionary
     * @return string
     */
    public static function translateMessage(ContainerInterface $container, $message, array $tokens = array(), $dictionary = '')
    {
        if($container->has('translator'))
        {
            $translator = $container->get('translator');
            if(null !== $translator)
            {
                return $translator->trans($message, $tokens, $dictionary);
            }
        }

        return $message;
    }

    /**
     * Retrieves the web folder from the given bundle
     *
     * @param ContainerInterface $container
     * @param string $bundleName
     *
     * @return string
     */
    public static function retrieveBundleWebFolder(KernelInterface $kernel, $bundleName)
    {
        $bundleDir = null;
        foreach ($kernel->getBundles() as $bundle)
        {
            $name = $bundle->getName();
            if(strpos($name, $bundleName) !== false)
            {
                $bundleDir = 'bundles/'.preg_replace('/bundle$/', '', strtolower($name));
                break;
            }
        }

        return $bundleDir;
    }

    /**
     * Locates a resource and returns its absolute path
     *
     * @param ContainerInterface    $container
     * @param string                $path
     *
     * @return string  , $forceRelativePath = false $forceRelativePath &&
     */
    public static function locateResource(KernelInterface $kernel, $path)
    {
        if(\substr($path, 0, 1) != '@') $path = '@' . $path;

        $fullPath = "";
        if('@' === \substr($path, 0, 1))
        {
            try
            {
                $fullPath = $kernel->locateResource($path);
            }
            catch(\InvalidArgumentException $e)
            {
                return false;
            }
        }
        else
        {
            $fullPath = $path;
        }

        return $fullPath;
    }

    /**
     * Normalize a path to a unix path
     *
     * @param   string      $path
     * @return  string
     */
    public static function normalizePath($path)
    {
        return preg_replace('/\\\/', '/', $path);
    }

    /**
     * Set a string which has a leng of a number of characters predefined. If string is lengther
     * than the width we need, it will be reduced to max width and three point will be added.
     *
     * @param      str The string to check
     * @param      int The length desidered
     * @return     str The string modified as needed
     */
    public static function truncateString($string, $length = 20)
    {
        return (strlen($string) > $length) ? substr($string, 0, $length - 3) . '...' : $string;
    }

    /**
     * Extracts a zip file to a given path
     *
     * @param      str The zip file
     * @param      str The destination path
     *
     * @return     int
     */
    public static function extractZipFile($zipFile, $destinationPath)
    {
        $zipLibrary = __DIR__ . "/../../vendors/Zip/zipfile.php";
        if(@require_once($zipLibrary))
        {
            $zip = new \ZipArchive();
            if ($zip->open($zipFile) === true)
            {
                $result = $zip->extractTo($destinationPath);
                $zip->close();

                return $result;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            throw new \Exception(sprintf("The zip library cannot be loaded because %s is not a valid path", $zipLibrary));
        }
    }

    /**
     * Slugifies a path
     *
     * Based on http://php.vrana.cz/vytvoreni-pratelskeho-url.php
     *
     * @param type $text
     * @return type
     */
    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }

    /**
     * Retrieves the mime type for the given resource
     *
     * @param   string $filename
     * @return string
     */
    public static function mimeContentType($filename)
    {
        $result = new \finfo();
        return $result->file($filename, FILEINFO_MIME_TYPE);
    }
}
