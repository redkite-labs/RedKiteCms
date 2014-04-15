<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteLabs <webmaster@RedKiteLabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://redkite-labs.com
 * 
 * @license    MIT License
 */

namespace RedKiteLabs\RedKiteCms\ElFinderBundle\Core\Connector;

/**
 * Configures the connector
 *
 * @author RedKiteLabs
 */
class RedKiteLabsElFinderConnector extends RedKiteLabsElFinderBaseConnector
{
    protected function configure()
    {
        $request = $this->container->get('request');
        
        $options = array(
            'roots' => array(
                array(
                    'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
                    'path'          => 'bundles/redkitelabselfinder/vendor/ElFinder/files/',         // path to files (REQUIRED)
                    'URL'           => $request->getScheme().'://'.$request->getHttpHost() . '/bundles/redkitelabselfinder/vendor/ElFinder/files/', // URL to files (REQUIRED)
                    'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
                )
            )
        );
        
        return $options;
    }
}
