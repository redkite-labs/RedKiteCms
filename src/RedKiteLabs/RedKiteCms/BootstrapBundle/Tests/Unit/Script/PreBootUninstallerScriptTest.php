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

use AlphaLemon\BootstrapBundle\Core\Script\PreBootUninstallerScript;
use org\bovigo\vfs\vfsStream;
use AlphaLemon\BootstrapBundle\Tests\Unit\Base\BaseFilesystem;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;
use AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManager;


/**
 * PackagesBootstrapperTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class PreeBootUninstallerScriptTest extends BaseFilesystem
{
    private $preBootUninstallerScript = null;

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
        $this->preBootUninstallerScript = new PreBootUninstallerScript(vfsStream::url('root/app/config/bundles'));
    }

    public function testUninstallPreBoot()
    {
        $this->setUpFileSystemForUninstall();
        $actionsManagers = array('BusinessCarouselBundle' => $this->initActionsManager('packageUninstalledPreBoot'));
        $this->preBootUninstallerScript->executeActions($actionsManagers);
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/.PostUninstall')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/.packageUninstalledPreBoot')));

        $postActions = json_decode(file_get_contents(vfsStream::url('root/app/config/bundles/.PostUninstall')), true);
        $this->assertArrayHasKey('BusinessCarouselBundle', $postActions);
        $this->assertEquals($postActions['BusinessCarouselBundle'], get_class($actionsManagers['BusinessCarouselBundle']));
    }

    public function testUninstallPreBootGeneratesAJsonFileWhenAnActionIsNotExecuted()
    {
        $this->setUpFileSystemForUninstall();
        $actionsManagers = array('BusinessCarouselBundle' => $this->initActionsManager('packageUninstalledPreBoot', false));
        $this->preBootUninstallerScript->executeActions($actionsManagers);
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/.packageUninstalledPreBoot')));

        $actionsNotExecuted = json_decode(file_get_contents(vfsStream::url('root/app/config/bundles/.packageUninstalledPreBoot')), true);
        $this->assertArrayHasKey('BusinessCarouselBundle', $actionsNotExecuted);
        $this->assertEquals($actionsNotExecuted['BusinessCarouselBundle'], get_class($actionsManagers['BusinessCarouselBundle']));
    }

    public function testThePackageUninstalledPreBootJsonFileIsNotRemovedBecauseTheActionHasNotCorrectlyExecuted()
    {
        $this->setUpFileSystemForUninstall();

        $notExecutedActions = array('BusinessCarouselBundle' => '\AlphaLemon\Block\BusinessCarouselBundle\BusinessCarouselBundle');
        file_put_contents(vfsStream::url('root/app/config/bundles/.packageUninstalledPreBoot'), json_encode($notExecutedActions));

        $actionManager = $this->initActionsManager('packageUninstalledPreBoot', false);

        $actionManagerGenerator = $this->getMock('AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerGenerator');
        $actionManagerGenerator->expects($this->once())
            ->method('generate');

        $actionManagerGenerator->expects($this->once())
            ->method('getActionManager')
            ->will($this->onConsecutiveCalls($actionManager));

        $preBootUninstallerScript = new preBootUninstallerScript(vfsStream::url('root/app/config/bundles'), $actionManagerGenerator);
        $preBootUninstallerScript->executeActions(array());
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/.packageUninstalledPreBoot')));
    }

    public function testTheNotExecutedActionsFileIsRemovedWhenTheActionHasCorrectlyExecuted()
    {
        $this->setUpFileSystemForUninstall();

        $notExecutedActions = array('BusinessCarouselBundle' => '\AlphaLemon\Block\BusinessCarouselBundle\BusinessCarouselBundle');
        file_put_contents(vfsStream::url('root/app/config/bundles/.packageUninstalledPreBoot'), json_encode($notExecutedActions));

        $actionManager = $this->initActionsManager('packageUninstalledPreBoot');
        $actionManagerGenerator = $this->getMock('AlphaLemon\BootstrapBundle\Core\ActionManager\ActionManagerGenerator');
        $actionManagerGenerator->expects($this->once())
            ->method('generate');

        $actionManagerGenerator->expects($this->once())
            ->method('getActionManager')
            ->will($this->returnValue($actionManager));
        $preBootUninstallerScript = new preBootUninstallerScript(vfsStream::url('root/app/config/bundles'), $actionManagerGenerator);
        $preBootUninstallerScript->executeActions(array());
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/.packageUninstalledPreBoot')));
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
        $this->addClassManager('root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/Core/ActionManager/', 'ActionManagerBusinessCarousel.php', 'BusinessCarouselBundle');
        $this->createFolder('root/app/config/bundles/cache/BusinessCarouselBundle');
        copy(vfsStream::url('root/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/Core/ActionManager/ActionManagerBusinessCarousel.php'), vfsStream::url('root/app/config/bundles/cache/BusinessCarouselBundle/ActionManagerBusinessCarousel.php'));
    }
}