<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder;

use AlphaLemon\ElFinderBundle\Core\Connector\AlphaLemonElFinderBaseConnector;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;

/**
 * Configures the ElFinder library to manage media files, like images, flash, pdf and more
 */
class ElFinderMediaConnector extends AlphaLemonElFinderBaseConnector
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $request = $this->container->get('request');

        $asset = new AlAsset($this->container->get('kernel'), '@AlphaLemonCmsBundle');
        $absolutePath = $asset->getAbsolutePath() . '/' . $this->container->getParameter('alpha_lemon_cms.upload_assets_dir') . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.media_folder') . '/';
        $filesPath = $this->container->getParameter('kernel.root_dir') . '/../' . $this->container->getParameter('alpha_lemon_cms.web_folder') . '/' . $absolutePath;
        if (!is_dir($filesPath)) @mkdir($filesPath);

        $options = array(
            'locale' => '',
            'roots' => array(
                array(
                    'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
                    'path'          => $filesPath,         // path to files (REQUIRED)
                    'URL'           => $request->getScheme().'://'.$request->getHttpHost() . '/' . $absolutePath, // URL to files (REQUIRED)
                    'accessControl' => 'access',             // disable and hide dot starting files (OPTIONAL)
                    'rootAlias'     => 'Media'             // disable and hide dot starting files (OPTIONAL)
                )
            )
        );

        return $options;
    }
}
