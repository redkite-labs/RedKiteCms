<?php
/*
 * This file is part of the RedKiteLabsBootstrapBundle and it is distributed
 * under the MIT License. To use this bundle you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://redkite-labs.com
 *
 * @license    MIT License
 */

namespace RedKiteLabs\BootstrapBundle\Tests\Unit\Script;

use RedKiteLabs\BootstrapBundle\Core\Script\PreBootUninstallerScript;
use org\bovigo\vfs\vfsStream;
use RedKiteLabs\BootstrapBundle\Tests\Unit\Base\BaseFilesystem;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;
use RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManager;


/**
 * PackagesBootstrapperTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class PreBootUninstallerScriptTest extends BaseFilesystem
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
        $actionsManagers = array('BusinessCarouselFakeBundle' => $this->initActionsManager('packageUninstalledPreBoot'));
        $this->preBootUninstallerScript->executeActions($actionsManagers);
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/.PostUninstall')));
        $this->assertFalse(file_exists(vfsStream::url('root/app/config/bundles/.packageUninstalledPreBoot')));

        $postActions = json_decode(file_get_contents(vfsStream::url('root/app/config/bundles/.PostUninstall')), true);
        $this->assertArrayHasKey('BusinessCarouselFakeBundle', $postActions);
        $this->assertEquals($postActions['BusinessCarouselFakeBundle'], get_class($actionsManagers['BusinessCarouselFakeBundle']));
    }

    public function testUninstallPreBootGeneratesAJsonFileWhenAnActionIsNotExecuted()
    {
        $this->setUpFileSystemForUninstall();
        $actionsManagers = array('BusinessCarouselFakeBundle' => $this->initActionsManager('packageUninstalledPreBoot', false));
        $this->preBootUninstallerScript->executeActions($actionsManagers);
        $this->assertTrue(file_exists(vfsStream::url('root/app/config/bundles/.packageUninstalledPreBoot')));

        $actionsNotExecuted = json_decode(file_get_contents(vfsStream::url('root/app/config/bundles/.packageUninstalledPreBoot')), true);
        $this->assertArrayHasKey('BusinessCarouselFakeBundle', $actionsNotExecuted);
        $this->assertEquals($actionsNotExecuted['BusinessCarouselFakeBundle'], get_class($actionsManagers['BusinessCarouselFakeBundle']));
    }

    public function testThePackageUninstalledPreBootJsonFileIsNotRemovedBecauseTheActionHasNotCorrectlyExecuted()
    {
        $this->setUpFileSystemForUninstall();

        $notExecutedActions = array('BusinessCarouselFakeBundle' => '\RedKiteLabs\Block\BusinessCarouselFakeBundle\BusinessCarouselFakeBundle');
        file_put_contents(vfsStream::url('root/app/config/bundles/.packageUninstalledPreBoot'), json_encode($notExecutedActions));

        $actionManager = $this->initActionsManager('packageUninstalledPreBoot', false);

        $actionManagerGenerator = $this->getMock('RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManagerGenerator');
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

        $notExecutedActions = array('BusinessCarouselFakeBundle' => '\RedKiteLabs\Block\BusinessCarouselFakeBundle\BusinessCarouselFakeBundle');
        file_put_contents(vfsStream::url('root/app/config/bundles/.packageUninstalledPreBoot'), json_encode($notExecutedActions));

        $actionManager = $this->initActionsManager('packageUninstalledPreBoot');
        $actionManagerGenerator = $this->getMock('RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManagerGenerator');
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
        $actionsManagerPost = $this->getMock('RedKiteLabs\BootstrapBundle\Core\ActionManager\ActionManagerInterface');
        $actionsManagerPost->expects($this->once())
            ->method($method)
            ->will($this->returnValue($returnValue));

        return $actionsManagerPost;
    }

    private function setUpFileSystemForUninstall()
    {
        $this->addClassManager('root/vendor/alphalemon/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/Core/ActionManager/', 'ActionManagerBusinessCarousel.php', 'BusinessCarouselFakeBundle');
        $this->createFolder('root/app/config/bundles/cache/BusinessCarouselFakeBundle');
        copy(vfsStream::url('root/vendor/alphalemon/app-business-carousel-bundle/RedKiteLabs/Block/BusinessCarouselFakeBundle/Core/ActionManager/ActionManagerBusinessCarousel.php'), vfsStream::url('root/app/config/bundles/cache/BusinessCarouselFakeBundle/ActionManagerBusinessCarousel.php'));
    }
}