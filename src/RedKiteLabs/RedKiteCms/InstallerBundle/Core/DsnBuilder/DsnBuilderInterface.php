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
 * Defines the base interface to build a dsn string
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface DsnBuilderInterface
{
    /**
     * Configures the base dsn
     * 
     * @return string
     */
    function configureBaseDsn();
    
    /**
     * Configures the dsn
     * 
     * @return string
     */
    function configureDsn();
    
    /**
     * Configures the parametrized dsn
     * 
     * @return string
     */
    function configureParametrizedDsn();
    
    /**
     * Configures the parametrized dsn for test environment
     * 
     * @return string
     */
    function configureParametrizedDsnForTestEnv();
    
    /**
     * Test the database connection with the given parameters
     * 
     * @return boolean
     */
    public function testConnection();
}