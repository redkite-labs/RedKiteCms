# AlphaLemonBootstrapBundle
AlphaLemonBootstrapBundle takes care to autoload and configure bundles on a composer based application. Each developer 
could add an autoloader.json file to a bundle and configure it to autoload that bundle, without have to enable it 
manually in the AppKernel file.

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

## Configuration files
Usually each bundle requires to add some configurations inside the application's config.yml to make it work properly. Some of these settings could be
generic, ie. enabling the bundle to use assetic, while others could be specific for the application which is using that bundle. 

The **BootstrapBundle** let the developer to define the generic settings directly with the bundle. This will produce some benefits for the final
user:

- The bundle that requires only generic setting is ready to be used without thouching the application's config.yml file
- When the bundle is used by many applications, the generic configuration is already made
- Less frustation for the user
- Less frustation for the bundle's developer who has to write less documentation
- Light config.yml file

To add a configuration that usually goes into the **config.yml** file of your application, just add a **config.yml** file under the 
**Resources/config** folder of your bundle and add the required setting to it. The BootstrapBundle takes care to copy it into the 
**app/config/bundles/[environment]** folder and loaded in the AppKernel class.

The same concepts are applied to the routes implemented by the bundle, so you can add a **routing.yml** file into the **Resources/config**
of your bundle and the BootstrapBundle will do the rest for you.

### A practical example
For example, AlphaLemon CMS uses assetic to manage its assets, so the user who want to use that bundle should add the following configuration 
to the config.yml file of his application:

    app/config/config.yml
    
    assetic:
    bundles: [AlphaLemonCmsBundle]
    filters:
        cssrewrite: ~
        yui_css:
            jar: %kernel.root_dir%/Resources/java/yuicompressor.jar
        yui_js:
            jar: %kernel.root_dir%/Resources/java/yuicompressor.jar

With the BootstrapBundle these setting have been added to the config.yml file of AlphaLemonCms bundle so the user has the
generic configuration ready to be used and doesn't require to add nothing to the **config.yml** file.

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
        $configFolder = __DIR__ . '/config/bundles/config/' . $this->getEnvironment();
        $finder = new \Symfony\Component\Finder\Finder();
        $configFiles = $finder->depth(0)->name('*.yml')->in($configFolder);
        foreach ($configFiles as $config) {
            $loader->load((string)$config);
        };

        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

That's enough to autoload all the bundles that have an autoload file