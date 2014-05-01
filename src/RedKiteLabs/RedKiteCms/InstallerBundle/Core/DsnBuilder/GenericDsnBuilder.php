<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Core\DsnBuilder;

/**
 * Implements the object to generate a generic dsn
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class GenericDsnBuilder extends Base\BaseDsnBuilder
{
    /**
     * {@inheritdoc}
     */
    public function configureDsn()
    {
        return $this->configureBaseDsn() . ';dbname=' . $this->options["database"];
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureBaseDsn()
    {
        return sprintf("%s:host=%s", $this->options["driver"], $this->options["host"]);        
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureParametrizedDsn()
    {
        return '%rkcms_database_driver%:host=%rkcms_database_host%;dbname=%rkcms_database_name%';        
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureParametrizedDsnForTestEnv()
    {
        return '%rkcms_database_driver%:host=%rkcms_database_host%;dbname=%rkcms_database_name%_test';        
    }
    
    /**
     * {@inheritdoc}
     */
    public function testConnection()
    {
        $user = $this->options["user"];
        $password = $this->options["password"];
        $mysqli = new \mysqli($this->options["host"], $user, $password, null, $this->options["port"]);

        $error = $mysqli->connect_error;
        if (null !== $error) {
            throw new \RuntimeException($error);
        }

        if (empty($password)) {
            $query = sprintf('SELECT * FROM mysql.user WHERE User = "%s"', $user);
            $result = $mysqli->query($query);
            if (false === $result || $result->num_rows == 0) {
                throw new \InvalidArgumentException(sprintf("It seems that user %s with blank password is not configured on this mysql server", $user));
            }
        }
    }
}