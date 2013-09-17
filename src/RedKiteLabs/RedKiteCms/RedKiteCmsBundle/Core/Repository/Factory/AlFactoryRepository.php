<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory;

use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\Exception\RepositoryNotFoundException;

/**
 * AlFactoryRepository object instantiates repository objects according with the orm
 * and the repository type
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlFactoryRepository implements AlFactoryRepositoryInterface
{
    private $orm = null;
    private $namespace = 'RedKiteLabs\RedKiteCmsBundle\Core\Repository';

    /**
     * Constructor
     *
     * @param string $orm
     */
    public function __construct($orm)
    {
        $this->orm = ucfirst($orm);
    }

    /**
     * {@inheritdoc}
     */
    public function createRepository($blockType, $namespace = null)
    {
        $namespace = (null === $namespace) ?  $this->namespace : $namespace;
        $blockType = ucfirst($blockType);
        $class = sprintf('%s\%s\%sRepository%s', $namespace, $this->orm, $blockType, $this->orm);
        if ( ! class_exists($class)) {
            $class = sprintf('%s\%s\Al%sRepository%s', $namespace, $this->orm, $blockType, $this->orm);
            if ( ! class_exists($class)) {
                $exception = array(
                    'message' => 'exception_invalid_namespace',
                    'parameters' => array(
                        '%blockType%' => $blockType, 
                        '%namespace%' => $namespace,
                    ),
                );
                throw new RepositoryNotFoundException(json_encode($exception));
            }
        }

        return new $class();
    }
}
