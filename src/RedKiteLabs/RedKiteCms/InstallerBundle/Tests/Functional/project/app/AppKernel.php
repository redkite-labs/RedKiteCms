<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            //new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new AlphaLemon\CmsInstallerBundle\AlphaLemonCmsInstallerBundle(),
            new Acme\WebSiteBundle\AcmeWebSiteBundle(),
            new AlphaLemon\BootstrapBundle\AlphaLemonBootstrapBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            //$bundles[] = new Acme\DemoBundle\AcmeDemoBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        $bootstrapper = new \AlphaLemon\BootstrapBundle\Core\Autoloader\BundlesAutoloader(__DIR__, $this->getEnvironment(), $bundles);
        $bundles = $bootstrapper->getBundles();

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $configFolder = __DIR__ . '/config/bundles/config';
        $finder = new \Symfony\Component\Finder\Finder();
        $configFiles = $finder->depth(0)->name('*.yml')->in($configFolder);
        foreach ($configFiles as $config) {
            $loader->load((string)$config);
        };

        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
