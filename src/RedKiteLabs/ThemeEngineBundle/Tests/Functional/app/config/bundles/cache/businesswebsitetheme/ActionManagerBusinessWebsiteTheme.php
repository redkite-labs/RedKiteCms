<?php
/*
 * This file is part of the AlphaLemonThemeBundle Theme and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
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

namespace AlphaLemon\Theme\BusinessWebsiteThemeBundle\Core\ActionManager;

use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManager;
use Symfony\Component\DependencyInjection\ContainerInterface; 

class ActionManagerBusinessWebsiteTheme extends ActionManager
{   
    private $fileSystem = null;
    private $logo = 'business-website-original-logo.png';
    
    public function __construct()
    {
        $this->fileSystem = new \Symfony\Component\Filesystem\Filesystem();
    }
    
    public function packageInstalledPostBoot(ContainerInterface $container)
    {
        try {
            $originFile = __DIR__ . '/../../Resources/public/images/' . $this->logo;
            if (file_exists($originFile)) {
                $targetFile = $this->fetchLogoTargetPath($container);          
                $this->fileSystem->copy($originFile, $targetFile);
            }
            
            return true;
        } catch (\Exception $x) {
            return false;
        }        
    }
    
    public function packageUninstalledPostBoot(ContainerInterface $container)
    {
        try {
            $targetFile = $this->fetchLogoTargetPath($container);
            if (file_exists($targetFile)) $this->fileSystem->remove($targetFile);
            
            return true;
        } catch (\Exception $x) {
            return false;
        }        
    }
    
    private function fetchLogoTargetPath(ContainerInterface $container)
    {
        return $container->getParameter('alpha_lemon_cms.upload_assets_full_path') . '/' . $container->getParameter('alpha_lemon_cms.deploy_bundle.media_dir') . '/' . $this->logo;
    }
}
