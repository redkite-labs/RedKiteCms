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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Core\Content\Slot\Repeated\Converter;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Tests\tools\AlphaLemonDataPopulator;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;

class AlSlotConverterBase extends TestCase 
{    
    protected function setUp()
    {
        parent::setUp();
        
        AlphaLemonDataPopulator::depopulate();
        
        $alLanguageManager = new AlLanguageManager(
            $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface')
        );
        $params = array('language' => 'en');
        $alLanguageManager->save($params);
        
        $params = array('language' => 'it');
        $alLanguageManager->set(null);
        $alLanguageManager->save($params);
        
        $container = $this->setupPageTree(AlLanguageQuery::create()->mainLanguage()->findOne()->getId())->getContainer();
        $alPageManager = new AlPageManager(
            $container
        );
        
        $params = array('pageName'      => 'fake page 1', 
                        'template'      => 'home',
                        'permalink'     => 'this is a website fake page',
                        'title'         => 'page title',
                        'description'   => 'page description',
                        'keywords'      => '');
        $alPageManager->save($params);
        $alPageManager->set(null);
        
        $params['pageName'] = 'fake page 2';
        $alPageManager->save($params);
        $alPageManager->set(null);
        
        $params['pageName'] = 'fake page 3';
        $alPageManager->save($params);
        $alPageManager->set(null);
        
        $params['pageName'] = 'fake page 4';
        $alPageManager->save($params);
    }
}