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
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),
            new Sensio\Bundle\DistributionBundle\SensioDistributionBundle(),
            new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),
            new AlphaLemon\Theme\BusinessWebsiteThemeBundle\BusinessWebsiteThemeBundle(),
        );
        
        $bundles[] = new AlphaLemon\ThemeEngineBundle\AlphaLemonThemeEngineBundle();

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
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
