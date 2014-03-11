<?php

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Core\Generator;


use Sensio\Bundle\GeneratorBundle\Generator\Generator;

/**
 * ConfigurationGenerator generates the RedKite CMS environments files
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class EnvironmentsGenerator extends Generator
{
    private $kernelDir;
    private $skeletonDir;

    /**
     * Constructor
     *
     * @param string $themeSkeletonDir
     */
    public function __construct($kernelDir = null)
    {
        $this->kernelDir = $kernelDir;
        $this->skeletonDir = __DIR__ . '/../../Resources/skeleton';
    }
    
    public function generateFrontcontrollers()
    {
        $this->setSkeletonDirs($this->skeletonDir);
        
        $this->renderFile('frontcontroller.php', $this->kernelDir.'/../web/rkcms.php', array('environment' => 'rkcms'));
        $this->renderFile('frontcontroller_dev.php', $this->kernelDir.'/../web/rkcms_dev.php', array('environment' => 'rkcms_dev'));
        $this->renderFile('frontcontroller.php', $this->kernelDir.'/../web/stage.php', array('environment' => 'stage'));
        $this->renderFile('frontcontroller_dev.php', $this->kernelDir.'/../web/stage_dev.php', array('environment' => 'stage_dev'));
    }
}