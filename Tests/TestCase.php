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
namespace AlphaLemon\AlphaLemonCmsBundle\Tests;



use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;




class TestCase extends \PHPUnit_Framework_TestCase {
 
    protected $connection = null;
    
    protected function setUp()
    {
        $this->connection = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Tests\Pdo\MockPDO');
    }


    public static function setUpBeforeClass()
    {
        $config = array("datasources" => array (
            "default" => array (
                "adapter" => "mysql",
                "connection" => array
                (
                    "dsn" => "mysql:host=localhost;dbname=alphaLemonDevTest",
                    "user" => "root",
                    "password" => "passera73",
                    "classname" => "DebugPDO",
                    "options" => array(),
                    "attributes" => array (),
                    "settings" => array (),
                )
            )
        ));
        
        if (!\Propel::isInit()) {
            \Propel::setConfiguration($config);
            \Propel::initialize();
        }
        /*
        //tools\AlphaLemonDataPopulator::depopulate();
                               
        /*
        if(null === $this->container)
        {
            $isLocal = false;
            $localKernel = __DIR__.'/../../../../../../app/AppKernel.php';
            if(is_file($localKernel)) {
                require_once $localKernel;
                $isLocal = true;
            }
            else {
                require_once __DIR__.'/CmsTestKernel/CmsTestKernel.php';
            }

            $app = ($isLocal) ? new \AppKernel('test', true) : new \CmsTestKernel('test', true);
            $app->boot();
            $this->container = $app->getContainer(); 
        }*/
    }
    
    public function getContainer()
    {
        return $this->container;
    }

    public function setupPageTree($languageId = null, $pageId = null)
    {
        $pageTree = new TestAlPageTree($this->getMock('Symfony\\Component\\DependencyInjection\\ContainerInterface'), $languageId, $pageId);
        $this->container->set('al_page_tree', $pageTree);
        
        return $this;
    }
}


class TestAlPageTree extends AlPageTree
{
    public function __construct(ContainerInterface $container, $languageId = null, $pageId = null, $themeName = 'BusinessWebsiteThemeBundle', $templateName = 'Home')
    {
        parent::__construct($container);
        
        $languageId = (null != $languageId) ? $languageId : 2;
        $this->alLanguage = new AlLanguage();
        $this->alLanguage->setId($languageId);
        
        $pageId = (null != $pageId) ? $pageId : 2;
        $this->alPage = new AlPage();
        $this->alPage->setId($pageId);
        $this->alPage->setTemplateName($templateName);
        
        $this->setThemeName($themeName);
        $this->setTemplateName($templateName);
    }
}