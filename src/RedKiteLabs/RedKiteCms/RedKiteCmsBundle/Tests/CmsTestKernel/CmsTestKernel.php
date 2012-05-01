<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class CmsTestKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            //new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            //new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new Propel\PropelBundle\PropelBundle(),
            new AlphaLemon\FrontendBundle\AlphaLemonFrontendBundle(),
            new AlphaLemon\PageTreeBundle\AlphaLemonPageTreeBundle(),
            new AlphaLemon\ThemeEngineBundle\AlphaLemonThemeEngineBundle(),
            new AlphaLemon\AlphaLemonCmsBundle\AlphaLemonCmsBundle(),
            new AlphaLemon\AlValumUploaderBundle\AlValumUploaderBundle(),
            new AlphaLemon\ElFinderBundle\AlphaLemonElFinderBundle(),
            //new Themes\AlphaLemonThemeBundle\AlphaLemonThemeBundle(),
        );
        
        $internalBundles = new \AlphaLemon\AlphaLemonCmsBundle\Core\Autoloader\InternalBundlesAutoloader();
        $bundles = array_merge($bundles, $internalBundles->getBundles());

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
