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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPage;

use Symfony\Bundle\AsseticBundle\Tests\TestKernel;

class AlBlockManagerFactoryTest extends TestCase
{    
    public function testcreateBlock()
    {
        $container = $this->setupPageTree()->getContainer();     
        
        $contenManager = AlBlockManagerFactory::createBlock(
                $container, 
                null, 
                'logo'
                );
        $this->assertNull($contenManager);
        
        try
        {
            $contenManager = AlBlockManagerFactory::createBlock(
                    $container, 
                    'fake', 
                    'logo'
                    );
            $this->fail('::createBlock() has not generated an exception when the content type does not exists');
        }
        catch(\Exception $ex)
        {
        }
        
        $contenManager = AlBlockManagerFactory::createBlock(
                $container, 
                '  text ', 
                'logo'
                );
        $this->assertNotNull($contenManager, '::createBlock() has not created a text content when the class name has not required spaces');
        
        $contenManager = AlBlockManagerFactory::createBlock(
                $container, 
                'text', 
                'logo'
                );
        $this->assertNotNull($contenManager, '::createBlock() has not created a text content when the class name is given in lowercase');
        
        $contenManager = AlBlockManagerFactory::createBlock(
                $container, 
                'Text', 
                'logo'
                );
        $this->assertNotNull($contenManager, '::createBlock() has not created a text content');
        $this->assertNull($contenManager->get());
        
        $content = new \AlphaLemon\AlphaLemonCmsBundle\Model\AlContent();
        $content->setId(1);
        $content->setClassName('Media');
        
        $contenManager = AlBlockManagerFactory::createBlock(
                $container, 
                $content, 
                'ads_box'
                );
        $this->assertNotNull($contenManager, '::createBlock() has not created a media content manager');
        $this->assertEquals('AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\AlMediaBundle\Core\Block\AlBlockManagerMedia', get_class($contenManager), '::createBlock() has not created the expected object');        
    }
}