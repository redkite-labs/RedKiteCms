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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\BlockModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;

/**
 * AlBlockManagerFactory is the object responsible to create a new BlockManager object 
 * 
 * BlockManagers are created by an existing AlBlock object or by a valid string that identifies
 * a valid AlBlockType
 * 
 * @api
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerFactory implements AlBlockManagerFactoryInterface
{
    private $dispatcher;
    private $translator;
    
    /**
     * Constructor
     * 
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface $translator 
     */
    public function __construct(EventDispatcherInterface $dispatcher, TranslatorInterface $translator)
    {
        $this->dispatcher = $dispatcher;
        $this->translator = $translator;
    }
    
    /**
     * { @inheritDoc }
     */
    public function createBlock(BlockModelInterface $alBlockModel, $block)         
    {
        if ((null === $block || !is_string($block)) && !$block instanceOf AlBlock) {
            return null;
        }
        
        if (is_string($block) && empty($block)) {
            return null;
        }
        
        if ($block instanceOf AlBlock) {
            $alBlock = $block;
            $className = $alBlock->getClassName();
        }
        else {
            $alBlock = null;
            $className = ucfirst(trim($block));
        }
        
        $class = sprintf("AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\%sBundle\Core\Block\AlBlockManager%1\$s", $className); 
        if (!class_exists($class)) {
            $class = sprintf("AlphaLemon\Block\%1\$sBundle\Core\Block\AlBlockManager%1\$s", $className); 
            if (!class_exists($class)) {
                if (null !== $alBlock) {
                    // The block has been removed from the website and, cause of that, the block is deleted
                    $alBlock->setToDelete(1);
                    $alBlock->save();
                    
                    return null;
                } else {                    
                    // The block has never added so there's something wrong with the derived block class implementation
                    return null;
                }
            }
        }
        
        $alBlockManager = new $class($this->dispatcher, $this->translator, $alBlockModel);
        if (null !== $alBlock) $alBlockManager->set($alBlock);
        
        return $alBlockManager;
    }
}