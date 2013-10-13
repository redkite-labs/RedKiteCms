<?php

namespace RedKiteCms\InstallerBundle\Core\Generator;


use Sensio\Bundle\GeneratorBundle\Generator\Generator;

/**
 * ConfigurationGenerator generates the RedKite CMS configuration files
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ConfigurationGenerator extends Generator
{
    private $kernelDir;
    private $skeletonDir;

    /**
     * Constructor
     *
     * @param string $themeSkeletonDir
     */
    public function __construct($kernelDir = null, array $options = array())
    {
        $this->kernelDir = $kernelDir;
        $this->skeletonDir = __DIR__ . '/../../Resources/skeleton';
        
        $dsnBuilderClassName = '\RedKiteCms\InstallerBundle\Core\DsnBuilder\GenericDsnBuilder';
        $specificDsnBuilderClassName = '\RedKiteCms\InstallerBundle\Core\DsnBuilder\\' . ucfirst($options["driver"]) . 'DsnBuilder';
        if (class_exists($specificDsnBuilderClassName)) {
            $dsnBuilderClassName = $specificDsnBuilderClassName;
        }
        $this->dsnBuilder = new $dsnBuilderClassName($options);
        $options['dsn'] = $this->dsnBuilder->configureParametrizedDsn();        
        $options['dsn_test'] = $this->dsnBuilder->configureParametrizedDsnForTestEnv();
        $options['website_url'] = $options['website-url'];
        
        $this->options = $options;
    }
    
    public function generateConfigurations()
    {
        $this->setSkeletonDirs($this->skeletonDir);
        
        $this->renderFile('config_rkcms.yml', $this->kernelDir.'/config/config_rkcms.yml', $this->options);
        $this->renderFile('config_rkcms_dev.yml', $this->kernelDir.'/config/config_rkcms_dev.yml', array());
        $this->renderFile('config_rkcms_test.yml', $this->kernelDir.'/config/config_rkcms_test.yml', $this->options);
        $this->renderFile('config_stage.yml', $this->kernelDir.'/config/config_stage.yml', array());
        $this->renderFile('config_stage_dev.yml', $this->kernelDir.'/config/config_stage_dev.yml', array());
    }
    
    public function generateRoutes()
    {
        $this->setSkeletonDirs($this->skeletonDir);

        $this->renderFile('routing_rkcms.yml', $this->kernelDir.'/config/routing_rkcms.yml', $this->options);
        $this->renderFile('routing_rkcms_dev.yml', $this->kernelDir.'/config/routing_rkcms_dev.yml', array());
        $this->renderFile('routing_rkcms_test.yml', $this->kernelDir.'/config/routing_rkcms_test.yml', array());
        $this->renderFile('routing_stage.yml', $this->kernelDir.'/config/routing_stage.yml', $this->options);
        $this->renderFile('routing_stage_dev.yml', $this->kernelDir.'/config/routing_stage_dev.yml', array());
    }
}
