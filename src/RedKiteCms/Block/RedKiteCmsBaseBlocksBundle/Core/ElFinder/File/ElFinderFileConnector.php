<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\ElFinder\File;

use RedKiteLabs\RedKiteCmsBundle\Core\ElFinder\Base\ElFinderBaseConnector;

/**
 * Description of ElFinderMarkdownConnector
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ElFinderFileConnector extends ElFinderBaseConnector
{
    protected function configure()
    {
        $filesFolder = $this->container->getParameter('file.base_folder') ;
        
        return $this->generateOptions($filesFolder, 'Files');
    }
}