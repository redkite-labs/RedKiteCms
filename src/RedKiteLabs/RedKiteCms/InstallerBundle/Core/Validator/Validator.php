<?php
/*
 * This file is part of the RedKite CMS InstallerBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteCms\InstallerBundle\Core\Validator;

/**
 * Validates input params
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class Validator
{   
    /**
     * Validates bundle name
     * 
     * @param string $input
     * @return string
     */
    public static function validateBundleName($input)
    {
        self::validateString($input, 'bundle');
        
        if (!preg_match('/Bundle$/', $input)) {
            throw new \InvalidArgumentException('The bundle name must end with Bundle');
        }

        return $input;
    }
    
    /**
     * Validates the deploy bundle
     * 
     * @param string $input
     * @return string
     */
    public static function validateDeployBundle($kernelDir, $bundleName)
    {
        $contents = file_get_contents($kernelDir . '/AppKernel.php');
        //if( ! preg_match("/[\s|\t]+new " . $companyName . "\\\\" . $bundleName . "/s", $contents))
        if( ! preg_match("/" . $bundleName . "/s", $contents))
        {
            $message = "\nRedKite CMS requires an existing bundle to work with. You enter as working bundle the following: $companyName\\$bundleName but, the bundle is not enable in AppKernel.php file. Please add the bundle or enable it ther run the script again.\n";

            throw new \RuntimeException($message);
        }
    }
    
    /**
     * Validates the database driver
     * 
     * @param string $input
     * @return string
     */
    public static function validateDriver($input)
    {
        self::validateString($input, 'driver');
        
        if( ! in_array($input, array('mysql', 'pgsql', 'sqlite'))) {
            throw new \InvalidArgumentException('Driver value must be one of the following: [mysql, pgsql, sqlite]');
        }

        return $input;
    }
    
    /**
     * Validates the database host
     * 
     * @param string $input
     * @return string
     */
    public static function validateHost($input)
    {
        /*
        $regex = '/^([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])(\.([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]{0,61}[a-zA-Z0-9]))*$/';
        if (!preg_match($regex, $input)) {
            throw new \InvalidArgumentException('The host value contains invalid characters');
        }
         */
        
        $valid1 = true;
        $regex = '/(?<!\S)(?:(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\b|.\b){7}(?!\S)/';
        if (!preg_match($regex, $input)) {
            $valid1 = false;
        }
        
        $valid2 = true;
        $regex = '/(?!-)[a-z\d-]{1,63}(?<!-)$/';
        if (!preg_match($regex, $input)) {
            $valid2 = false;
        }
        
        if (!$valid1 && !$valid2) {
            throw new \InvalidArgumentException('The host value contains invalid characters');
        }

        return $input;
    }
    
    /**
     * Validates the database name
     * 
     * @param string $input
     * @return string
     */
    public static function validateDatabaseName($input)
    {
        $regex = '/^(?:[a-zA-Z_\-$\&\x7f-\xff][a-zA-Z0-9_\-$\&\x7f-\xff]*\\\?)+$/';
        if (!preg_match($regex, $input)) {
            throw new \InvalidArgumentException('The given database name contains invalid characters: allowed characters are letters, numbers and one of the following _ - $ £');
        }

        return $input;
    }
    
    /**
     * Validates the database port
     * 
     * @param string $input
     * @return string
     */
    public static function validatePort($input)
    {
        $regex = '/[0-9]+/';
        if (!preg_match($regex, $input, $matches)) {
            throw new \InvalidArgumentException('The port value contains invalid characters');
        }

        return $input;
    }
    
    /**
     * Validates the database user
     * 
     * @param string $input
     * @return string
     */
    public static function validateUser($input)
    {
        return self::validateString($input, 'user');
    }
    
    /**
     * Validates the database password
     * 
     * @param string $input
     * @return string
     */
    public static function validatePassword($input)
    {
        return $input;
    }
    
    /**
     * Validates the website url
     * 
     * @param string $input
     * @return string
     */
    public static function validateUrl($input)
    {
        $regex = '/^http:\/\/?[^\/]+\/$/i';
        if (!preg_match($regex, $input)) {
            throw new \InvalidArgumentException('Website url must start with "http://" and must end with "/"');
        }

        return $input;
    }
    
    /**
     * Validates a string
     * 
     * @param string $input
     * @return string
     */
    protected static function validateString($input, $type)
    {
        $regex = '/^(?:[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\\\?)+$/';
        if (!preg_match($regex, $input)) {
            throw new \InvalidArgumentException("The $type value contains invalid characters");
        }

        return $input;
    }
}