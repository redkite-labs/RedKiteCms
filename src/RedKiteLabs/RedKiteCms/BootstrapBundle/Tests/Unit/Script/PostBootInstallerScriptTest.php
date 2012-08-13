<?php
/*
 * This file is part of the AlphaLemonBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 */

namespace AlphaLemon\BootstrapBundle\Tests\Unit\Script;

use AlphaLemon\BootstrapBundle\Core\Script\PostBootInstallerScript;
use org\bovigo\vfs\vfsStream;
use AlphaLemon\BootstrapBundle\Tests\Unit\Base\BaseFilesystem;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;
use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManager;


/**
 * PostBootInstallerScriptTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class PostBootInstallerScriptTest extends BaseFilesystem
{
    private $postBootInstallerScript = null;
    private $container;

    protected function setUp()
    {
        parent::setUp();

        $folders = array('app' =>
                        array('config' =>
                            array('bundles' => array()
                                )
                            ),
                        'vendor' => array()
                        );
        $this->root = vfsStream::setup('root', null, $folders);

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->postBootInstallerScript = new PostBootInstallerScript(vfsStream::url('root/app/config/bundles'), null, $this->container);
    }

    /**
     * @expectedException \AlphaLemon\BootstrapBundle\Core\Exception\MissingDependencyException
     * @expectedExceptionMessage You must give a ContainerInterface object to execute a post action. Use the setContainer method to fix it up
     */
    public function testAnExceptionIsThrownWhenTheContainerHasNotBeenGiven()
    {
        $this->setUpFileSystemForInstall();
        $actionsManagerPost = $this->getMock('AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerInterface');
        $actionsManagerPost->expects($this->never())
            ->method('packageInstalledPostBoot');
        $actionsManagers = array('BusinessCarouselFakeBundle' => $actionsManagerPost);
        $postBootInstallerScript = new postBootInstallerScript(vfsStream::url('root/app/config/bundles'));
        $postBootInstallerScript->executeActions($actionsManagers);
    }

    public function testInstallPostBoot()
    {
        $this->setUpFileSystemForInstall();
        $actionsManagers = array('BusinessCarouselFakeBundle' => $this->initActionsManager('packageInstalledPostBoot'));
        $this->postBootInstallerScript->executeActions($actionsManagers);
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/.PostInstall')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/.packageInstalledPreBoot')));
    }

    public function testInstallPostBootGeneratesAJsonFileWhenAnActionIsNotExecuted()
    {
        $this->setUpFileSystemForInstall();
        $actionsManagers = array('BusinessCarouselFakeBundle' => $this->initActionsManager('packageInstalledPostBoot', false));
        $this->postBootInstallerScript->executeActions($actionsManagers);
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/.packageInstalledPostBoot')));

        $actionsNotExecuted = json_decode(file_get_contents(vfsStream::url('root/app/config/bundles/.packageInstalledPostBoot')), true);
        $this->assertArrayHasKey('BusinessCarouselFakeBundle', $actionsNotExecuted);
        $this->assertEquals($actionsNotExecuted['BusinessCarouselFakeBundle'], get_class($actionsManagers['BusinessCarouselFakeBundle']));
    }

    public function testThePackageInstalledPostBootJsonFileIsNotRemovedBecauseTheActionHasNotCorrectlyExecuted()
    {
        $this->setUpFileSystemForInstall();

        $notExecutedActions = array('BusinessCarouselFakeBundle' => '\AlphaLemon\Block\BusinessCarouselFakeBundle\BusinessCarouselFakeBundle');
        file_put_contents(vfsStream::url('root/app/config/bundles/.packageInstalledPostBoot'), json_encode($notExecutedActions));

        $actionManager = $this->initActionsManager('packageInstalledPostBoot', false);

        $actionManagerGenerator = $this->getMock('AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerGenerator');
        $actionManagerGenerator->expects($this->once())
            ->method('generate');

        $actionManagerGenerator->expects($this->once())
            ->method('getActionManager')
            ->will($this->returnValue($actionManager));

        $postBootInstallerScript = new PostBootInstallerScript(vfsStream::url('root/app/config/bundles'), $actionManagerGenerator);
        $postBootInstallerScript->setContainer($this->container)
                                ->executeActions(array());
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/.packageInstalledPostBoot')));
    }

    public function testTheNotExecutedActionsFileIsRemovedWhenTheActionHasCorrectlyExecuted()
    {
        $this->setUpFileSystemForInstall();

        $notExecutedActions = array('BusinessCarouselFakeBundle' => '\AlphaLemon\Block\BusinessCarouselFakeBundle\BusinessCarouselFakeBundle');
        file_put_contents(vfsStream::url('root/app/config/bundles/.packageInstalledPostBoot'), json_encode($notExecutedActions));

        $actionManager = $this->initActionsManager('packageInstalledPostBoot');
        $actionManagerGenerator = $this->getMock('AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerGenerator');
        $actionManagerGenerator->expects($this->once())
            ->method('generate');

        $actionManagerGenerator->expects($this->once())
            ->method('getActionManager')
            ->will($this->returnValue($actionManager));

        $postBootInstallerScript = new postBootInstallerScript(vfsStream::url('root/app/config/bundles'), $actionManagerGenerator);
        $postBootInstallerScript->setContainer($this->container)
                                ->executeActions(array());
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/.packageInstalledPostBoot')));
    }

    private function initActionsManager($method, $returnValue = null)
    {
        $actionsManagerPost = $this->getMock('AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerInterface');
        $actionsManagerPost->expects($this->once())
            ->method($method)
            ->will($this->returnValue($returnValue));

        return $actionsManagerPost;
    }

    private function setUpFileSystemForInstall()
    {
        $fileContents = '"businesscarousel":"AlphaLemon\\Block\\BusinessCarouselFakeBundle\\Core\\ActionManager\\ActionManagerBusinessCarousel"';
        $this->createFile('root/app/config/bundles/.postInstall', $fileContents);

        $classFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/Core/ActionManager/';
        $this->createFolder($classFolder);
        $this->createFile($classFolder . 'ActionManagerBusinessCarousel.php');
    }
}