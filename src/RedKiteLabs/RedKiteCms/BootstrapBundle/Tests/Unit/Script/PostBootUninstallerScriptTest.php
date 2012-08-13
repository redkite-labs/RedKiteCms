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

use AlphaLemon\BootstrapBundle\Core\Script\PostBootUninstallerScript;
use org\bovigo\vfs\vfsStream;
use AlphaLemon\BootstrapBundle\Tests\Unit\Base\BaseFilesystem;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;
use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManager;


/**
 * PostBootUninstallerScriptTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class PostBootUninstallerScriptTest extends BaseFilesystem
{
    private $postBootUninstallerScript = null;
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
        $this->root = vfsStream::setup('root', null, $folders);//print_r(vfsStream::inspect(new vfsStreamStructureVisitor())->getStructure());

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->postBootUninstallerScript = new PostBootUninstallerScript(vfsStream::url('root/app/config/bundles'));
        $this->postBootUninstallerScript->setContainer($this->container);
    }

    /**
     * @expectedException \AlphaLemon\BootstrapBundle\Core\Exception\MissingDependencyException
     * @expectedExceptionMessage You must give a ContainerInterface object to execute a post action. Use the setContainer method to fix it up
     */
    public function testAnExceptionIsThrownWhenTheContainerHasNotBeenGiven()
    {
        $this->setUpFileSystemForUninstall();
        $actionsManagerPost = $this->getMock('AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerInterface');
        $actionsManagerPost->expects($this->never())
            ->method('packageUninstalledPostBoot');
        $actionsManagers = array('BusinessCarouselFakeBundle' => $actionsManagerPost);
        $postBootUninstallerScript = new PostBootUninstallerScript(vfsStream::url('root/app/config/bundles'));
        $postBootUninstallerScript->executeActions($actionsManagers);
    }

    public function testUninstallPostBoot()
    {
        $this->setUpFileSystemForUninstall();
        $actionsManagers = array('BusinessCarouselFakeBundle' => $this->initActionsManager('packageUninstalledPostBoot'));
        $this->postBootUninstallerScript->executeActions($actionsManagers);
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/.PostInstall')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/.packageInstalledPreBoot')));
    }

    public function testInstallPostBootGeneratesAJsonFileWhenAnActionIsNotExecuted()
    {
        $this->setUpFileSystemForUninstall();
        $actionsManagers = array('BusinessCarouselFakeBundle' => $this->initActionsManager('packageUninstalledPostBoot', false));
        $this->postBootUninstallerScript->executeActions($actionsManagers);
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/.packageUninstalledPostBoot')));

        $actionsNotExecuted = json_decode(file_get_contents(vfsStream::url('root/app/config/bundles/.packageUninstalledPostBoot')), true);
        $this->assertArrayHasKey('BusinessCarouselFakeBundle', $actionsNotExecuted);
        $this->assertEquals($actionsNotExecuted['BusinessCarouselFakeBundle'], get_class($actionsManagers['BusinessCarouselFakeBundle']));
    }

    public function testThepackageUninstalledPostBootJsonFileIsNotRemovedBecauseTheActionHasNotCorrectlyExecuted()
    {
        $this->setUpFileSystemForUninstall();

        $notExecutedActions = array('BusinessCarouselFakeBundle' => '\AlphaLemon\Block\BusinessCarouselFakeBundle\BusinessCarouselFakeBundle');
        file_put_contents(vfsStream::url('root/app/config/bundles/.packageUninstalledPostBoot'), json_encode($notExecutedActions));

        $actionManager = $this->initActionsManager('packageUninstalledPostBoot', false);

        $actionManagerGenerator = $this->getMock('AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerGenerator');
        $actionManagerGenerator->expects($this->once())
            ->method('generate');

        $actionManagerGenerator->expects($this->once())
            ->method('getActionManager')
            ->will($this->returnValue($actionManager));

        $postBootUninstallerScript = new PostBootUninstallerScript(vfsStream::url('root/app/config/bundles'), $actionManagerGenerator);
        $postBootUninstallerScript->setContainer($this->container)
                                ->executeActions(array());
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/.packageUninstalledPostBoot')));
    }

    public function testTheNotExecutedActionsFileIsRemovedWhenTheActionHasCorrectlyExecuted()
    {
        $this->setUpFileSystemForUninstall();

        $notExecutedActions = array('BusinessCarouselFakeBundle' => '\AlphaLemon\Block\BusinessCarouselFakeBundle\BusinessCarouselFakeBundle');
        file_put_contents(vfsStream::url('root/app/config/bundles/.packageUninstalledPostBoot'), json_encode($notExecutedActions));

        $actionManager = $this->initActionsManager('packageUninstalledPostBoot');
        $actionManagerGenerator = $this->getMock('AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerGenerator');
        $actionManagerGenerator->expects($this->once())
            ->method('generate');

        $actionManagerGenerator->expects($this->once())
            ->method('getActionManager')
            ->will($this->returnValue($actionManager));

        $postBootUninstallerScript = new PostBootUninstallerScript(vfsStream::url('root/app/config/bundles'), $actionManagerGenerator);
        $postBootUninstallerScript->setContainer($this->container)
                                ->executeActions(array());
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/.packageUninstalledPostBoot')));
    }

    private function initActionsManager($method, $returnValue = null)
    {
        $actionsManagerPost = $this->getMock('AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerInterface');
        $actionsManagerPost->expects($this->once())
            ->method($method)
            ->will($this->returnValue($returnValue));

        return $actionsManagerPost;
    }

    private function setUpFileSystemForUninstall()
    {
        $fileContents = '"businesscarousel":"AlphaLemon\\Block\\BusinessCarouselFakeBundle\\Core\\ActionManager\\ActionManagerBusinessCarousel"';
        $this->createFile('root/app/config/bundles/.postUninstall', $fileContents);

        $this->addClassManager('root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/Core/ActionManager/', 'ActionManagerBusinessCarousel.php', 'BusinessCarouselFakeBundle');
        $this->createFolder('root/app/config/bundles/cache/BusinessCarouselFakeBundle');
        copy(vfsStream::url('root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselFakeBundle/Core/ActionManager/ActionManagerBusinessCarousel.php'), vfsStream::url('root/app/config/bundles/cache/BusinessCarouselFakeBundle/ActionManagerBusinessCarousel.php'));
    }
}