<?php

include __DIR__ . "/AppKernel.php";

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class RedKiteCmsAppKernel extends AppKernel
{
    public function registerBundles()
    {
        $bundles = parent::registerBundles();
        $bundles[] = new RedKiteLabs\RedKiteCms\BootstrapBundle\RedKiteLabsBootstrapBundle();

        $searchFolders = $this->searchFolders = array(
            __DIR__ . '/../src/RedKiteCms/Block',
            __DIR__ . '/../src/RedKiteCms/Theme',
            __DIR__ . '/../src/RedKiteLabs',
            __DIR__ . '/../src/RedKiteLabs/RedKiteCms',
        );
        $bootstrapper = new \RedKiteLabs\RedKiteCms\BootstrapBundle\Core\Autoloader\BundlesAutoloader(__DIR__, $this->getEnvironment(), $bundles, $searchFolders);
        $bundles = $bootstrapper->getBundles();

        if ('test' === $this->getEnvironment()) {
            $bundles[] = new Behat\MinkBundle\MinkBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $configFolder = __DIR__ . '/config/bundles/config/' . $this->getEnvironment();
        if (is_dir($configFolder)) {
            $finder = new \Symfony\Component\Finder\Finder();
            $configFiles = $finder->depth(0)->name('*.yml')->in($configFolder);
            foreach ($configFiles as $config) {
                $loader->load((string)$config);
            };
        };

        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
