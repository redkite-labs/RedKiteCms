<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageModelation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Functional\Controller;

use AlphaLemon\AlphaLemonCmsBundle\Tests\WebTestCaseFunctional;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlPageModelPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlSeoModelPropel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlBlockModelPropel;
use org\bovigo\vfs\vfsStream;

/**
 * CmsControllerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class DeployControllerTest extends WebTestCaseFunctional
{
    private $pageModel;
    private $seoModel;
    private $blockModel;

    /**/
    public static function setUpBeforeClass()
    {
        self::$languages = array(array('Language'      => 'en', 'Language'      => 'it',));

        self::$pages = array(array('PageName'      => 'index',
                                    'TemplateName'  => 'home',
                                    'IsHome'        => '1',
                                    'Permalink'     => 'this is a website fake page',
                                    'MetaTitle'         => 'page title',
                                    'MetaDescription'   => 'page description',
                                    'MetaKeywords'      => 'key'),
                            array('PageName'      => 'page1',
                                    'TemplateName'  => 'fullpage',
                                    'Permalink'     => 'page-1',
                                    'MetaTitle'         => 'page 1 title',
                                    'MetaDescription'   => 'page 1 description',
                                    'MetaKeywords'      => ''));
        self::populateDb();
    }

    protected function setUp()
    {
        parent::setUp();

        $this->mockKernel = $this->getMock('Symfony\Component\HttpmockKernel\KernelInterface');
        $this->mockKernel->expects($this->any())
            ->method('locateResource')
            ->will($this->onConsecutiveCalls(vfsStream::url('AcmeWebSiteBundle'), vfsStream::url('AlphaLemonCmsBundle')));

        $this->mockKernel->expects($this->any())
            ->method('getRootDir')
            ->will($this->returnValue(vfsStream::url('app')));

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $folders = array('app' => array(),
                         'web' => array('bundles'
                                => array('alphalemoncms'
                                    => array('uploads'
                                        => array('assets'
                                            => array('media' => array('image1.png' => '', 'image2.png' => ''),
                                                    'js' => array('code.js' => ''),
                                                    'css' => array('style.css' => ''),
                                            )
                                        )
                                    )
                                )
                             ),
                         'AcmeWebSiteBundle' => array('Resources' => array()),
                         'AlphaLemonCmsBundle' => array(),
                        );
        $this->root = vfsStream::setup('root', null, $folders);


    }

    public function testOpeningAPageThatDoesNotExistShowsTheDefaultWelcomePage()
    {return;
        //$deployer = new \AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlTwigDeployer($this->container);
       /* $this->container->expects($this->any())
            ->method('getParameter')
            ->will($this->onConsecutiveCalls(
                    'AcmeWebSiteBundle',
                    'Resources/config',
                    'Resources/public/',
                    'uploads/assets',
                    'web',
                    'Resources/views'));
*/
        $container = $this->client->getContainer();
        $this->container->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($container, $this->mockKernel,
                    $container->get('seo_model'),
                    $container->get('template_manager') ,
                    $container->get('language_model'),
                    $container->get('page_model'),
                    $container->get('theme_model'),
                    $container->get('seo_model')));


        $controller = new \AlphaLemon\AlphaLemonCmsBundle\Controller\DeployController();
        $controller->localAction();


        /*
        $crawler = $this->client->request('GET', 'backend/en/al_local_deploy');
        $response = $this->client->getResponse();
        /*
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Welcome to AlphaLemon CMS")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("This is the AlphaLemon CMS background and usually it should be hide")')->count() > 0);
         *
         */
    }
}