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

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Core\DsnBuilder\Base;

use RedKiteLabs\RedKiteCms\InstallerBundle\Core\DsnBuilder\DsnBuilderInterface;

/**
 * Implements the base object to generate the dsn string to connect to a database
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BaseDsnBuilder implements DsnBuilderInterface
{
    private $dsn = null;
    private $baseDsn = null;
    private $abstractDsn = null;
    protected $options = null;
    
    /**
     * Constructor
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
        $this->baseDsn = $this->configureBaseDsn();
        $this->dsn = $this->configureDsn();
        $this->abstractDsn = $this->configureParametrizedDsn();
    }
    
    /**
     * Gets the base dsn string
     * 
     * @return string
     */
    public function getBaseDsn()
    {
        return $this->baseDsn;
    }
    
    /**
     * Gets the base dsn string
     * 
     * @return string
     */
    public function getDsn()
    {
        return $this->dsn;
    }
    
    /**
     * Gets the base dsn string with parameters
     * 
     * @return string
     */
    public function getParametrizedDsn()
    {
        return $this->abstractDsn;
    }
}