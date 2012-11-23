<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\Block\FileBundle\Core\ElFinder;

use AlphaLemon\ElFinderBundle\Core\Connector\AlphaLemonElFinderBaseConnector;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;

/**
 * Description of ElFinderMarkdownConnector
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class ElFinderFileConnector extends AlphaLemonElFinderBaseConnector
{
    protected function configure()
    {
        $request = $this->container->get('request');
        $folder = $this->container->getParameter('file.base_folder') ;
        $absolutePath = $this->container->getParameter('alpha_lemon_cms.upload_assets_absolute_path') . '/' . $folder . '/';
        $filesPath = $this->container->getParameter('alpha_lemon_cms.upload_assets_full_path') . '/' . $folder;
        if (!is_dir($filesPath)) @mkdir($filesPath);
        
        $options = array(
            'locale' => '',
            'roots' => array(
                array(
                    'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
                    'path'          => $filesPath,         // path to files (REQUIRED)
                    'URL'           => $request->getScheme().'://'.$request->getHttpHost() . '/' . $this->container->getParameter('alpha_lemon_cms.upload_assets_dir') . '/' . $folder, // URL to files (REQUIRED)
                    'accessControl' => 'access',             // disable and hide dot starting files (OPTIONAL)
                    'rootAlias'     => 'File',
                )
            )
        );

        return $options;
    }
}
