<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
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
 */

namespace AlphaLemon\ElFinderBundle\Core\Connector;

/**
 * Configures the connector
 *
 * @author AlphaLemon
 */
class AlphaLemonElFinderConnector extends AlphaLemonElFinderBaseConnector
{
    protected function configure()
    {
        $request = $this->container->get('request');
        
        $options = array(
            'roots' => array(
                array(
                    'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
                    'path'          => 'bundles/alphalemonelfinder/files/',         // path to files (REQUIRED)
                    'URL'           => $request->getScheme().'://'.$request->getHttpHost() . '/bundles/alphalemonelfinder/files/', // URL to files (REQUIRED)
                    'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
                )
            )
        );
        
        return $options;
    }
}
