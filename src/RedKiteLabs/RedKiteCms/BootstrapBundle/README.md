# RedKiteLabsBootstrapBundle
RedKiteLabsBootstrapBundle takes care to autoload and configure Symfony2 bundles for 
an application which dependencies are managed by composer. 

To autoload a bundle, without requiring to declare it in Symfony2's AppKernel, you 
just need to create an autoloader.json file on the bundle's top folder and let the 
RedKiteLabsBootstrapBundle do the hard job for you.

You can autoload your bundles by environments and you can add a base configuration 
to your bundle, saved in a config.yml file which comes with the bundle itself, to define
the base configuration for your bundle the user should manually add to application's
config.yml file.

As for configuration, you can define your routes in the routing.yml file and distribute
them with the bundle.

[![Build Status](https://secure.travis-ci.org/redkite-labs/BootstrapBundle.png)](http://travis-ci.org/redkite-labs/BootstrapBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/redkite-labs/BootstrapBundle/badges/quality-score.png?s=d323d86ff9055c23989c3db8e0f398d1816b02a3)](https://scrutinizer-ci.com/g/redkite-labs/BootstrapBundle/)
[![Code Coverage](https://scrutinizer-ci.com/g/redkite-labs/BootstrapBundle/badges/coverage.png?s=aeb0bc487a40d4a69c376d3e17bed5730b27ad51)](https://scrutinizer-ci.com/g/redkite-labs/BootstrapBundle/)

## Install the RedKiteLabsBootstrapBundle
To install the RedKiteLabsBootstrapBundle, simply require it in your composer.json:

    "require": {
        [...]
        "redkite-labs/bootbusiness-theme-bundle": "dev-master"
    }

then install/update the packages:

    php composer.phar install/update

At last the bundle must be added to the AppKernel.php file:

    public function registerBundles()
    {
        $bundles = array(
            new RedKiteLabs\BootstrapBundle\RedKiteLabsBootstrapBundle(),

            [...]
        );
    }


## The autoload.json file
The autoload.json file must be placed in bundle's top folder and it is structured as
follows:

- bundles (mandatory)
- routing

The mandatory **bundles** section contains the bundles you want to autoload. Let's 
see a very basic example:

    {
        "bundles" : {
            "RedKiteLabs\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle" : ""
        }
    }

This autoloads the BusinessDropCapBundle for the whole application's environments.

### Environments
When you need to autoload a bundle only for certains environments, just add the 
**environments** option to the bundle:

    {
        "bundles" : {
            "RedKiteLabs\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle" : {
                "environments" : ["dev", "test"]
            }
        }
    }

The **environments** option enables the bundle only for the declared environments. In 
the example above, the BusinessDropCapBundle is enabled only for the dev and test enviroments.

### The all keyword
To specifiy all the enviroments you can use the **all** keyword:

    {
        "bundles" : {
            "RedKiteLabs\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle" : {
                "environments" : ["all"]
            }
        }
    }

This is the default option used by the library when the **environments** one is not
defined.

### The **overrides** options
When a bundle overrides another bundle, it must be instantiated in the AppKernel after 
the overrided bundle.

You can implement this case in your autoload.json adding the **overrides** option:


    {
        "bundles" : {
            "RedKiteLabs\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle" : {
                "environments" : ["dev", "test"],
                "overrides" : ["BusinessCarouselBundle"]
            }
        }
    }

In this example the bundles order will be resolved instantiating the BusinessCarouselBundle 
before the BusinessDropCapBundle

### Autoloading a bundle without the autoloader.json file
You might wonder why we are talking about "bundles" and not just "bundle". This is 
quite simple to explain, in fact you could autoload a bundle when it does not implement
the autoloader.json file.

Let's suppose the BusinessCarouselBundle has not the autoloader.json file and the BusinessDropCapBundle requires it. You can
write the BusinessDropCapBundle's autoloader as follows to autoload it:

For example, let's uppose the BusinessCarouselBundle requires the PropelBundle to work and 
this last bundle does not implements the autoloader.json file.

In this case you can easily autoload the PropelBundle just declaring that bundle 
in you autoload.json file:

    {
        "bundles" : {
            "RedKiteLabs\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle" : {
                    "environments" : ["dev", "test"]
            },
            "RedKiteLabs\\Block\\BusinessCarouselBundle\\BusinessCarouselBundle" : ""
        }
    }

If you need to enable it for specific environments, you just  have to add the **environments** option as explained above.


Another situation could ben when a bundle implements its own environments. For example 
the RedKiteCmsBundle implements the rkcms and the rkcms_dev environments so we need
to register many thirdy part bundles:

    {
        "bundles" : {
            "RedKiteLabs\\RedKiteCmsBundle\\RedKiteCmsBundle" : {
                "environments" : ["rkcms", "rkcms_dev", "rkcms_test", "test"]
            },
            "Propel\\PropelBundle\\PropelBundle" : {
                "environments" : ["rkcms", "rkcms_dev", "rkcms_test", "test"]
            },
            "Symfony\\Bundle\\WebProfilerBundle\\WebProfilerBundle" : {
                "environments" : ["rkcms_dev", "rkcms_test"]
            },
            "Sensio\\Bundle\\DistributionBundle\\SensioDistributionBundle" : {
                "environments" : ["rkcms_dev", "rkcms_test"]
            },
            "Sensio\\Bundle\\GeneratorBundle\\SensioGeneratorBundle" : {
                "environments" : ["rkcms", "rkcms_dev", "rkcms_test"]
            }
        }
    }

The **"RedKiteLabs\\RedKiteCmsBundle\\RedKiteCmsBundle"** section enables the RedKiteCmsBundle
for the **"rkcms", "rkcms_dev", "rkcms_test", "test"**, then requires the PropelBundle for the
same environments and at last requires the WebProfilerBundle, the SensioDistributionBundle
and the SensioGeneratorBundle for the **"rkcms_dev", "rkcms_test"** environments.

## Configuration files (config.yml - routing.yml)
Usually each bundle requires to add some configurations inside the application's config.yml to 
make it work properly. Some of these settings could be generic, ie. enabling the bundle to use 
assetic, while others could be specific for the application which is using that bundle.

The **BootstrapBundle** let the developer to define the generic settings directly with the bundle. 
This will produce some benefits for the final user:

- The bundle that requires only generic setting is ready to be used without touching the application's config.yml file
- When the bundle is used by many applications, the generic configuration is already made
- Less frustation for the user
- Less frustation for the bundle's developer who has to write less documentation
- Light config.yml file

To add a configuration that usually goes into the application's **config.yml** file, 
just add a **config.yml** file under the **Resources/config** folder of your bundle 
and add the required setting to it.

The BootstrapBundle takes care to copy it into the **app/config/bundles/[environment]** 
folder and load your configuration in the AppKernel class.

The same concepts are applied to the routes implemented by the bundle, so you can add 
a **routing.yml** file into the **Resources/config** of your bundle and the BootstrapBundle 
will do the rest for you.

## Routing priority
When you need to assign a specific priority to routing files, you can add a **routing/priority**
setting to your configuration file:

    {
        "bundles" : {
            "RedKiteLabs\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle" : ""
        },
        "routing" : {
            "priority" : "128"
        }
    }

Each bundle gets zero as routing priority when the option is not specified. To load 
the routing file after, specify a value higher than zero, to load the routing file before, 
specify a value lower than zero.

### A practical example
For example, RedKiteCmsBundle uses assetic to manage its assets, so the user who want 
to use that bundle should add the following configuration to the config.yml file of 
his application:

    app/config/config.yml

    assetic:
    bundles: [RedKiteLabsCmsBundle]
    filters:
        cssrewrite: ~
        yui_css:
            jar: %kernel.root_dir%/Resources/java/yuicompressor.jar
        yui_js:
            jar: %kernel.root_dir%/Resources/java/yuicompressor.jar

With the BootstrapBundle these setting have been added to RedKiteCmsBundle's config.yml 
file so the user is not required to manually add those settings to application's config.yml.

## Enabling the routing autoloader
To enable the routing autoloader the following configuration must be added to the 
application's **routing.yml** configuration file:

    RedKiteLabsBootstrapBundle:
        resource: .
        type: bootstrap

## Use the BootstrapBundle in your AppKernel
To enable the autoloading some small changes must be implemented to the AppKernel file. 
At the end of the **registerBundles** method, declare a new **BundlesAutoloader** object, 
as follows:


    public function registerBundles()
    {
        $bundles = array(
            [...]
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            [...]
        }

        $bootstrapper = new \RedKiteLabs\BootstrapBundle\Core\Autoloader\BundlesAutoloader(__DIR__, $this->getEnvironment(), $bundles);
        $bundles = $bootstrapper->getBundles();

        return $bundles;
    }

BundlesAutoloader requires the kernel dir, the one where the AppKernel is placed, 
as first argument, the current environment as second argument and the instantiated 
bundles as third. 

Bundles are retrieved by the **getBundles** method and returned as usual.

To load the configurations from the app/config/bundles folder, the default 
**registerContainerConfiguration** must be changed as follows:

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


### Autoload bundles from other folders
BundlesAutoloader can be configured to look for autoloading bundles placed inside other 
folders in your application.
 
By defaut the BundlesAutoloader looks into the **src/RedKiteCms/Block** and the 
**src/RedKiteCms/Theme** folders.

You can easily change that parameter, passing an array with your folders defined into
to the BundlesAutoloader declaration.

For example, if you would need to tell BundlesAutoloader to look into the **src/Acme**
just change the BundlesAutoloader instantiation as follows:

    $bootstrapper = new \RedKiteLabs\BootstrapBundle\Core\Autoloader\BundlesAutoloader(__DIR__, $this->getEnvironment(), $bundles, array(__DIR__ . '/../src/Acme');

Please notice that the BundlesAutoloader assumes that your bundles are placed inside the **Acme** folder.