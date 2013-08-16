<?php
/*
 * This file is part of the RedKiteLabsBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\BootstrapBundle\Tests\Unit\Script;

use RedKiteLabs\BootstrapBundle\Core\Script\PreBootInstallerScript;
use org\bovigo\vfs\vfsStream;
use RedKiteLabs\BootstrapBundle\Tests\Unit\Base\BaseFilesystem;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;
use RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManager;


/**
 * PreeBootInstallerScriptTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class PreBootInstallerScriptTest extends BaseFilesystem
{
    private $preBootInstallerScript = null;

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
        $this->preBootInstallerScript = new PreBootInstallerScript(vfsStream::url('root/app/config/bundles'));
    }

    public function testInstallPreBoot()
    {
        $this->setUpFileSystemForInstall();
        $actionsManagers = array('BusinessCarouselFakeBundle' => $this->initActionsManager('packageInstalledPreBoot'));
        $this->preBootInstallerScript->executeActions($actionsManagers);
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/.PostInstall')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/.packageInstalledPreBoot')));

        $postActions = json_decode(file_get_contents(vfsStream::url('root/app/config/bundles/.PostInstall')), true);
        $this->assertArrayHasKey('BusinessCarouselFakeBundle', $postActions);
        $this->assertEquals($postActions['BusinessCarouselFakeBundle'], get_class($actionsManagers['BusinessCarouselFakeBundle']));
    }

    public function testInstallPreBootGeneratesAJsonFileWhenAnActionIsNotExecuted()
    {
        $this->setUpFileSystemForInstall();
        $actionsManagers = array('BusinessCarouselFakeBundle' => $this->initActionsManager('packageInstalledPreBoot', false));
        $this->preBootInstallerScript->executeActions($actionsManagers);
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/.packageInstalledPreBoot')));

        $actionsNotExecuted = json_decode(file_get_contents(vfsStream::url('root/app/config/bundles/.packageInstalledPreBoot')), true);
        $this->assertArrayHasKey('BusinessCarouselFakeBundle', $actionsNotExecuted);
        $this->assertEquals($actionsNotExecuted['BusinessCarouselFakeBundle'], get_class($actionsManagers['BusinessCarouselFakeBundle']));
    }

    public function testThePackageInstalledPreBootJsonFileIsNotRemovedBecauseTheActionHasNotCorrectlyExecuted()
    {
        $this->setUpFileSystemForInstall();

        $notExecutedActions = array('BusinessCarouselFakeBundle' => '\RedKiteLabs\Block\BusinessCarouselFakeBundle\BusinessCarouselFakeBundle');
        file_put_contents(vfsStream::url('root/app/config/bundles/.packageInstalledPreBoot'), json_encode($notExecutedActions));

        $actionManager = $this->initActionsManager('packageInstalledPreBoot', false);

        $actionManagerGenerator = $this->getMock('RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManagerGenerator');
        $actionManagerGenerator->expects($this->once())
            ->method('generate');

        $actionManagerGenerator->expects($this->once())
            ->method('getActionManager')
            ->will($this->returnValue($actionManager));

        $preBootInstallerScript = new PreBootInstallerScript(vfsStream::url('root/app/config/bundles'), $actionManagerGenerator);
        $preBootInstallerScript->executeActions(array());
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/.packageInstalledPreBoot')));
    }

    public function testTheNotExecutedActionsFileIsRemovedWhenTheActionHasCorrectlyExecuted()
    {
        $this->setUpFileSystemForInstall();

        $notExecutedActions = array('BusinessCarouselFakeBundle' => '\RedKiteLabs\Block\BusinessCarouselFakeBundle\BusinessCarouselFakeBundle');
        file_put_contents(vfsStream::url('root/app/config/bundles/.packageInstalledPreBoot'), json_encode($notExecutedActions));

        $actionManager = $this->initActionsManager('packageInstalledPreBoot');
        $actionManagerGenerator = $this->getMock('RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManagerGenerator');
        $actionManagerGenerator->expects($this->once())
            ->method('generate');

        $actionManagerGenerator->expects($this->once())
            ->method('getActionManager')
            ->will($this->returnValue($actionManager));

        $preBootInstallerScript = new PreBootInstallerScript(vfsStream::url('root/app/config/bundles'), $actionManagerGenerator);
        $preBootInstallerScript->executeActions(array());
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/.packageInstalledPreBoot')));
    }

    private function initActionsManager($method, $returnValue = null)
    {
        $actionsManagerPost = $this->getMock('RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManagerInterface');
        $actionsManagerPost->expects($this->once())
            ->method($method)
            ->will($this->returnValue($returnValue));

        return $actionsManagerPost;
    }

    private function setUpFileSystemForInstall()
    {
        $classFolder = 'root/vendor/alphalemon/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/Core/ActionManager/';
        $this->createFolder($classFolder);
        $this->createFile($classFolder . 'ActionManagerBusinessCarousel.php');
    }
}