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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;

/**
 * AlBlockManagerFactory creates a Block Manager object 
 * 
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlBlockManagerFactory
{
    /**
     * Creates an instance of an AlBlockManager object
     * 
     * @param ContainerInterface    $container
     * @param mixed                 $block        An instance of an AlBlock object or a valid content type
     * 
     * @return AlBlockManager or null
     * @throws InvalidArgumentException When the class cannot be created
     */
    public static function createBlock(ContainerInterface $container, $block) 
    {
        if(null === $block || !is_string($block) && !$block instanceOf AlBlock)
        {
            return null;
        }
        
        if($block instanceOf AlBlock)
        {
            $alBlock = $block;
            $className = $alBlock->getClassName();
        }
        else
        {
            $alBlock = null;
            $className = ucfirst(trim($block));
        }

        $class = sprintf("AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\%sBundle\Core\Block\AlBlockManager%1\$s", $className); 
        if(!class_exists($class))
        {
            $class = sprintf("AlphaLemon\Block\%1\$sBundle\Core\Block\AlBlockManager%1\$s", $className); 
            if(!class_exists($class))
            {
                throw new \InvalidArgumentException(AlToolkit::translateMessage($container, 'The class AlBlockManager%className% does not exist. Please create a new AlBlockManager%className% object that extends the AlBlockManager class to fix the problem.', array('%className%' => $className)));
            }
        }

        $alBlockManager = new $class($container);
        if(null !== $alBlock) $alBlockManager->set($alBlock);
        
        return $alBlockManager;
    }
}