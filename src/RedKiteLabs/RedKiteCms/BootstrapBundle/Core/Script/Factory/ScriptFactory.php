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

namespace RedKiteLabs\BootstrapBundle\Core\Script\Factory;

use RedKiteLabs\BootstrapBundle\Core\Exception\CreateScriptException;
use RedKiteLabs\BootstrapBundle\Core\Script\ScriptInterface;

/**
 * Generates a ScriptInterface object
 * 
 * @author RedKite Labs <info@redkite-labs.com>
 */
class ScriptFactory implements ScriptFactoryInterface
{
    private $configFolderPath;

    /**
     * Contructor
     * 
     * @param string $configFolderPath 
     */
    public function __construct($configFolderPath)
    {
        $this->configFolderPath = $configFolderPath;
    }

    /**
     * {@inheritdoc}
     */
    public function createScript($script)
    {
        if (null !== $script && is_string($script)) {
            $scriptClass = '\RedKiteLabs\BootstrapBundle\Core\Script\\' . $script . 'Script';
            if (class_exists($scriptClass)) {
                $script = new $scriptClass($this->configFolderPath);
                if ($script instanceof ScriptInterface) {
                    return $script;
                }

                throw new CreateScriptException($scriptClass . ' class must implement the ScriptInterface interface');
            }

            throw new CreateScriptException($scriptClass . ' class has not been found');
        }

        throw new CreateScriptException('ScriptFactory requires a not null string value to be able to create a new Script object');
    }
}