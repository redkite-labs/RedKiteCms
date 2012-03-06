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

require_once __DIR__.'/CmsTestKernel/CmsTestKernel.php';
require_once __DIR__.'/../../../../../app/AppKernel.php';

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;

class TestCase extends \PHPUnit_Framework_TestCase {
 
    private $container = null;
    
    protected function setUp()
    {
        if(null === $this->container)
        {
            $app = new \AppKernel('test', true);
            $app->boot();
            $this->container = $app->getContainer(); 
        }
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
    public function __construct(ContainerInterface $container, $languageId = null, $pageId = null, $themeName = 'AlphaLemonThemeBundle', $templateName = 'Home')
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