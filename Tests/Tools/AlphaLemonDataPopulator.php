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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Tools;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlLanguageQuery;

class AlphaLemonDataPopulator
{
    public static function populate($dispatcher = null, $con = null)
    {
        $alLanguageManager = new AlLanguageManager(
            $container
        );
        $params = array('language' => 'en');
        $alLanguageManager->save($params);
        
        $params = array('language' => 'it');
        $alLanguageManager->set(null);
        $alLanguageManager->save($params);
        exit;
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
    
    public static function depopulate($con = null)
    {
        /*
        $peerClasses = array(
            '\AlphaLemon\AlphaLemonCmsBundle\Model\AlBlockPeer',
            '\AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguagePeer',
            '\AlphaLemon\AlphaLemonCmsBundle\Model\AlPageAttributePeer',
            '\AlphaLemon\AlphaLemonCmsBundle\Model\AlPagePeer',
            '\AlphaLemon\ThemeEngineBundle\Model\AlThemePeer',
        );
        // free the memory from existing objects
        foreach ($peerClasses as $peerClass) {
                foreach ($peerClass::$instances as $o) {
                        $o->clearAllReferences();
                }
        }
        // delete records from the database
        if($con === null) {
                $con = \Propel::getConnection();
        }
        $con->beginTransaction();
        foreach ($peerClasses as $peerClass) {
                $peerClass::doDeleteAll($con);
        }
        
        $query = 'INSERT INTO al_language (language) VALUES(\'-\')';
        $statement = $con->prepare($query);
        $statement->execute();
        
        $query = 'INSERT INTO al_page (page_name) VALUES(\'-\');';
        $statement = $con->prepare($query);
        $statement->execute();
        
        $con->commit();*/
        
        $connection = \Propel::getConnection();
        $queries = array('TRUNCATE al_block;',
                         'TRUNCATE al_language;',
                         'TRUNCATE al_page;',
                         'TRUNCATE al_page_attribute;',
                         'TRUNCATE al_theme;',
                         'INSERT INTO al_language (language) VALUES(\'-\');',
                         'INSERT INTO al_page (page_name) VALUES(\'-\');',
                        );
        
        foreach($queries as $query)
        {
            $statement = $connection->prepare($query);
            $statement->execute();
        }
    }
}
