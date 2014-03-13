<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class RedKiteCmsAppKernel extends Kernel
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
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),
            new Sensio\Bundle\DistributionBundle\SensioDistributionBundle(),
            new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),
            new RedKiteLabs\RedKiteCms\BootstrapBundle\RedKiteLabsBootstrapBundle(),
            new Propel\PropelBundle\PropelBundle(),
        );
        
        $bootstrapper = new \RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Autoloader\BundlesAutoloader(__DIR__, $this->getEnvironment(), $bundles);
        $bundles = $bootstrapper->setVendorDir(__DIR__ . '/../../../vendor')
                                ->getBundles();
        
        $bundles[] = new RedKiteLabs\RedKiteCms\RedKiteCmsBundle\RedKiteCmsBundle();

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $configFolder = __DIR__ . '/config/bundles/config/' . $this->getEnvironment();
        $finder = new \Symfony\Component\Finder\Finder();
        $configFiles = $finder->depth(0)->name('*.yml')->in($configFolder);
        foreach ($configFiles as $config) {
            $loader->load((string) $config);
        };
        
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * Credits for this awesome time-saver method to Kris Wallsmith
     *
     * http://kriswallsmith.net/post/27979797907
     */
    protected function initializeContainer()
    {
        static $first = true;

        if (strpos($this->getEnvironment(), 'test') === false) {
            parent::initializeContainer();

            return;
        }

        $debug = $this->debug;

        if (!$first) {
            // disable debug mode on all but the first initialization
            $this->debug = false;
        }

        // will not work with --process-isolation
        $first = false;

        try {
            parent::initializeContainer();
        } catch (\Exception $e) {
            $this->debug = $debug;
            throw $e;
        }

        $this->debug = $debug;
    }
}
