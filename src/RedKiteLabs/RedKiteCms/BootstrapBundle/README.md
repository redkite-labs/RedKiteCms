# AlphaLemonBootstrapBundle
AlphaLemonBootstrapBundle takes care to autoload and configure bundles on a composer base application. The responsibility to configure the bundle
is delegated to the bundle's author, who implements an autoloader.json file, where declares the bundle's configuration.

[![Build Status](https://secure.travis-ci.org/alphalemon/BootstrapBundle.png)](http://travis-ci.org/alphalemon/BootstrapBundle)

## Install the AlphaLemonBootstrapBundle
To install the AlphaLemonBootstrapBundle, simply require it in your composer.json:

    "require": {
        [...]
        "alphalemon/alphalemon-bootstrap-bundle": "dev-master"
    }

then update the packages:

    php composer.phar install

At last the bundle must be added to the AppKernel.php file:

    public function registerBundles()
    {
        $bundles = array(
            new AlphaLemon\BootstrapBundle\AlphaLemonBootstrapBundle(),

            [...]
        );
    }

## The autoload.json file
The autoload.json file is a very basic json implementation, structured as follows:

    {
        "bundles" : {
            "AlphaLemon\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle" : {
                "environments" : ["alcms", "alcms_dev", "test"]
            }
        },
        "scripts" : {
            "package-installed" : "\\AlphaLemon\\Block\\BusinessDropCapBundle\\Core\\Listener\\TestListener",
            "package-uninstalled" : "\\AlphaLemon\\Block\\BusinessDropCapBundle\\Core\\Listener\\TestListener"
        }
    }

There are two possible sections:

- bundles
- scripts

### Bundles section
The bundles section declares the bundles that must be autoloaded and configured.

Each bundle must have an **environments section** where the bundle's author must declare the environments where the bundle will be enabled.
Environments could be an array, as in the example above, or a string when a single environment is managed. A bundle is enable for all the
environments giving the **all** value.

### Scripts section
The scripts section let the bundles author to define a custom action that will be executed when a bundle is installed or uninstalled. The possibile
values could be the following:

- package-installed
- package-uninstalled

The value must be the full namespaced path to the listener that implements the script action. The listener for the declared action could be the following:

    namespace AlphaLemon\Block\BusinessDropCapBundle\Core\Listener;

    use AlphaLemon\BootstrapBundle\Core\Event\PackageInstalledEvent;
    use AlphaLemon\BootstrapBundle\Core\Event\PackageUninstalledEvent;

    /**
     * Executes some actions when a package is installed or uninstalled
     */
    class TestListener
    {
        public function onPackageInstalled(PackageInstalledEvent $event)
        {
            echo "<br>TestListener installed<br>";
        }

        public function onPackageUninstalled(PackageUninstalledEvent $event)
        {
            echo "<br>TestListener uninstalled<br>";
        }
    }

## Configuration files
When a new package is autoloaded, the bundle's configuration files are copied under the **app/config/bundles** folder and loaded in the AppKernel class.
In this way the user that installs the bundle is not required to add the bundle's configuration to the **config.yml** and **routing.yml** files.

To add a configuration that usually goes into the **config.yml** file, just add a config.yml file under the Resources/config folder of your bundle. The
autoloader will copy that file for the config section and the routing.yml file for the routing section.

Obviously if something has to be changed to tune the autoloaded bundle, those customizations will be made in the config.yml file as usual.

### A practical example
For example, AlphaLemon CMS requires a deploy bundle to work, which usually resides into the **src** folder of the application. This configuration is made
in the FrontendBundle, which requires the following configuration into the config.yml file:

    alpha_lemon_frontend:
        deploy_bundle: AcmeWebSiteBundle

This configuration has been added to a **config.yml** file placed under the **Resources/config** folder of the **FrontendBundle**, so the user has the
default configuration ready to be used and doesn't require to add nothing to the **config.yml** file.

When the user wants to change the **deploy_bundle** value, simply adds the configuration under the **config.yml** file as usual and that value is ovverided
as well.

## Enabling the routing autoloader
To enable the routing autoloader the following configuration must be added to the **routing.yml** configuration file:

    AlphaLemonBootstrapBundle:
        resource: .
        type: bootstrap

## Use the BootstrapBundle in your AppKernel
To enable the autoloading some small changes must be applied to the AppKernel file. At the end of the **registerBundles** method, declare
a new **BundlesAutoloader** object, as follows:


    public function registerBundles()
    {
        [...]

        $bootstrapper = new \AlphaLemon\BootstrapBundle\Core\Autoloader\BundlesAutoloader($this->getEnvironment(), $bundles);
        $bundles = $bootstrapper->getBundles();

        return $bundles;
    }

That object requires the current environment and the instantiated bundles. Then bundles are retrieved by the **getBundles** method and
returned as asual.

To load the configurations from the app/config/bundles folder, the **registerContainerConfiguration** must be changed as follows:

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $configFolder = __DIR__ . '/config/bundles/config';
        $finder = new \Symfony\Component\Finder\Finder();
        $configFiles = $finder->depth(0)->name('*.yml')->in($configFolder);
        foreach($configFiles as $config) {
            $loader->load((string)$config);
        }

        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

That's enough to autoload all the bundles that have an autoload file

## Autoload a bundle that hasn't an autoload.json file
Each bundle can be always autoloaded also if any autoload.json is provided. The following json enables the **WebProfilerBundle** bundle
for the alcms_dev environment:

    {
        "bundles" : {
            "Symfony\\Bundle\\WebProfilerBundle\\WebProfilerBundle" : {
                "environments" : ["alcms_dev"]
            }
        }
    }


## Force a bundle to be loaded just for one or more environments
Many bundles comes configured as follows:

    {
        "bundles" : {
            "AlphaLemon\\ElFinderBundle\\AlphaLemonElFinderBundle" : {
                "environments" : "all"
            }
        }
    }

AlphaLemonCMS requires the ElFinder bundle but it must be enabled just for the environments that manages the backed. To do that the AlphaLemonCMS' autoload.json
will declare the ElFinder bundle as follows:

    {
        "bundles" : {
            "AlphaLemon\\ElFinderBundle\\AlphaLemonElFinderBundle" : {
                "environments" : ["alcms", "alcms_dev", "test"]
            }
        }
    }
