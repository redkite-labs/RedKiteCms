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
            //new Symfony\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            //new Acme\WebSiteBundle\AcmeWebSiteBundle(),
            new AlphaLemon\BootstrapBundle\AlphaLemonBootstrapBundle(),

            /*
            new AlphaLemon\PageTreeBundle\AlphaLemonPageTreeBundle(),
            new AlphaLemon\ThemeEngineBundle\AlphaLemonThemeEngineBundle(),
            new AlphaLemon\Theme\BusinessWebsiteThemeBundle\BusinessWebsiteThemeBundle(),
            new AlphaLemon\AlphaLemonCmsBundle\AlphaLemonCmsBundle(),
            new AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\TextBundle\TextBundle(),
            new AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MediaBundle\MediaBundle(),
            new AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MenuBundle\MenuBundle(),
            new AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\NavigationMenuBundle\NavigationMenuBundle(),
            new AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\ScriptBundle\ScriptBundle(),
            new AlphaLemon\Block\BusinessCarouselBundle\BusinessCarouselBundle(),
            new AlphaLemon\Block\BusinessDropCapBundle\BusinessDropCapBundle(),
            new AlphaLemon\Block\BusinessMenuBundle\BusinessMenuBundle(),
            new AlphaLemon\Block\BusinessSliderBundle\BusinessSliderBundle(),
            new AlphaLemon\FrontendBundle\AlphaLemonFrontendBundle(),
            new Propel\PropelBundle\PropelBundle(),*/
            new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),
            new Sensio\Bundle\DistributionBundle\SensioDistributionBundle(),
            new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),
            //new AlphaLemon\Block\MarkdownGeshiBundle\MarkdownGeshiBundle(),
            //new AlphaLemon\CmsInstallerBundle\AlphaLemonCmsInstallerBundle(),

            //new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            //new Highlight\Bundle\HighlightBundle(),
        );

        $bootstrapper = new \AlphaLemon\BootstrapBundle\Core\Autoloader\BundlesAutoloader($this->getEnvironment(), __DIR__, $bundles);
        $bundles = $bootstrapper->setVendorDir(__DIR__ . '/../../../vendor')->getBundles();

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
