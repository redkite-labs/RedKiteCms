# AlphaLemonBootstrapBundle
AlphaLemonBootstrapBundle takes care to autoload and configure bundles on a composer based application. Each developer could add
an autoloader.json file to its Bundle and configure it to autoload the bundle

The responsibility to configure the bundle
is delegated to the bundle's author, who implements an autoloader.json file, where declares the bundle's configuration.

[![Build Status](https://secure.travis-ci.org/alphalemon/BootstrapBundle.png)](http://travis-ci.org/alphalemon/BootstrapBundle)

## Install the AlphaLemonBootstrapBundle
To install the AlphaLemonBootstrapBundle, simply require it in your composer.json:

    "require": {
        [...]
        "alphalemon/alphalemon-bootstrap-bundle": "dev-master"
    }

then install/update the packages:

    php composer.phar install/update

At last the bundle must be added to the AppKernel.php file:

    public function registerBundles()
    {
        $bundles = array(
            new AlphaLemon\BootstrapBundle\AlphaLemonBootstrapBundle(),

            [...]
        );
    }


## The autoload.json file
The autoload.json file must be placed into the root of the bundle you want to autoload. It is made by two sections:

- bundles (mandatory)
- actionManager

The mandatory **bundles** section contains the bundles you want to autoload. Let's see a very basic example:

    {
        "bundles" : {
            "AlphaLemon\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle" : ""
        }
    }
	
This autoloads the BusinessDropCapBundle for all the environments.

### Environments
Sometimes it could be useful to autoload a bundle for certains environments, so a simple configuration could be added for the bundle
as follows:

	 {
        "bundles" : {
            "AlphaLemon\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle" : {
				"environments" : ["dev", "test"]
			}
        }
    }
	
The **environments** option enables the bundle only for the specified environments. In the example above, the BusinessDropCapBundle 
is enabled only for the dev and test enviroments.

### The all keyword
To specifiy all the enviroments you can use the **all** keyword:

	{
        "bundles" : {
            "AlphaLemon\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle" : {
				"environments" : ["all"]
			}
        }
    }
	
This example is equivalent to the very first one.

### The **overrides** options
Sometimes it could happen that a bundle overrides a part of another bundle. In this specific case the overrider bundle must be declared
after the overriden one. The **overrides** option can be used to achieve this task:

	{
        "bundles" : {
            "AlphaLemon\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle" : {
				"environments" : ["dev", "test"],
				"overrides" : ["BusinessCarouselBundle"]
			}
        }
    }
	
The bundles order will be resolved instantiating the BusinessCarouselBundle before the BusinessDropCapBundle

### Autoloading a bundle without the autoloader.json file	
You might wonder why we are talking about "bundles" and not just "bundle". This is quite simple to explain, in fact you could autoload a bundle without
the autoloader.json file.

Let's suppose the BusinessCarouselBundle has not the autoloader.json file and the BusinessDropCapBundle requires it. You can
write the BusinessDropCapBundle's autoloader as follows to autoload it:

	{
        "bundles" : {
            "AlphaLemon\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle" : {
				"environments" : ["dev", "test"]
			},
			"AlphaLemon\\Block\\BusinessCarouselBundle\\BusinessCarouselBundle" : ""
        }
    }

If you need to enable it for specific environments, you just  have to add the **environments** option as explained above.

### Execute and action when the package is installed or uninstalled
When you need to execute some actions after the package is installed or uninstalled, you have to add a class that extends the 
**ActionManager** object and that implements the **ActionManagerInterface**. This last one requires four methods which are:

	packageInstalledPreBoot
    packageUninstalledPreBoot
    packageInstalledPostBoot
    packageUninstalledPostBoot
	
The **ActionManager** class implemements all those methods as blank methods because all of them are always executed, so
the only thing you have to do is to extend the **ActionManager** object and override the method you need.

Let's see the actions in detail. The most important thing to notice is when the action is executed: there are two actions that are suffixed by 
**PreBoot** and two actions that are suffixed by **PostBoot**. The difference is quite important, in fact the first actions are executed when
the kernel is not booted, the second ones when it has been booted and they receive the container as well.

To declare your ActionManager class in your autoloader.json file, you just need to specify that class to the actionManager section as follows:

	{
        "bundles" : {
            "AlphaLemon\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle" : ""
        },
        "actionManager" : "\\AlphaLemon\\Block\\BusinessCarouselBundle\\Core\\\\ActionManager\\ActionManagerBusinessDropCap"
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

        $bootstrapper = new \AlphaLemon\BootstrapBundle\Core\Autoloader\BundlesAutoloader(__DIR__, $this->getEnvironment(), $bundles);
        $bundles = $bootstrapper->getBundles();

        return $bundles;
    }

That object requires the kernel dir, the one where the AppKernel is placed, the current environment and the instantiated bundles. Then bundles 
are retrieved by the **getBundles** method and returned as usual.

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