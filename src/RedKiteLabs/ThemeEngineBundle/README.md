# ThemeEngineBundle

ThemeEngineBundle is build with the pourpose to add theming feature to any Symfony2 application, solving one of the most difficult problem related to 
templating: the preservation of contents when a graphic theme is changed. ThemeEngineBundle tries to solve this problem introducing slots. A slot is simple a 
placeholder on a twig template, identified by a name, and implemented as a twig function. Follows an example:

    {{ renderSlot('logo') }}
    
This function tells twig to render the contents identified by the slot called "logo", nothing more. The next step is quite easy: the contents retained by the 
"logo" slot are rendered on each template where the placeholder is called.

The themes, managed by the ThemeEngineBundle, are nothing more than standard Symfony2 Bundles, so each theme has its own templates, configurations and assets
packed togheter into a well defined known structure.

[![Build Status](https://secure.travis-ci.org/alphalemon/ThemeEngineBundle.png)](http://travis-ci.org/alphalemon/ThemeEngineBundle)

## Install the ThemeEngineBundle
The ThemeEngine depends on several bundles and packages: 

- PageTreeBundle
- AlValumUploaderBundle
- Propel ORM
- Propel Bundle

## Get the ThemeEngineBundle
Clone this bundle in the vendor/bundles/AlphaLemon directory:

    git clone git://github.com/alphalemon/ThemeEngineBundle.git vendor/bundles/AlphaLemon/ThemeEngineBundle

## Configure the ThemeEngineBundle
Open the AppKernel configuration file and add the bundle to the registerBundles() method:

    public function registerBundles()
    {
        $bundles = array(
            ...
            new RedKiteLabs\ThemeEngineBundle\RedKiteLabsThemeEngineBundle(),
        )
    }

Register the ThemeEngineBundle namespaces in `app/autoload.php`:

    $loader->registerNamespaces(array(
        ...
        'AlphaLemon'                     => __DIR__.'/../vendor/bundles',
        'Themes'                         => __DIR__.'/../vendor/bundles/AlphaLemon/ThemeEngineBundle',
    ));
    
Import the routing configuration into the routing.yml file:

    _alphaLemonThemeEngineBundle:
        resource: "@RedKiteLabsThemeEngineBundle/Resources/config/routing.yml"
        
To complete the bundle configuration you must install assets as follows:

    app/console assets:install web
    
## Configure propel
ThemeEngineBundle needs a database to manage the themes and uses Propel as predefined ORM. To propely setup Propel with Symfony2, foolow the excellent
setup procedure provide by the [PropelBundle](https://github.com/propelorm/PropelBundle/blob/master/Resources/doc/README.markdown) bundle. When the ORM 
is properly configured, run the following commands:

    app/console propel:database:create
    app/console propel:build
    app/console propel:insert-sql --for

## Themes autoloading

The ThemeEngineBundle provides a complete web interface to manage themes. As explained above, themes are nothing more than symfony2 bundles and, 
as each symfony2 bundle, they must be loaded in the registerBundles() method. Sometimes it could be a pain to manually add each bundle to 
AppKernel class, so if you wish to automate this operation, the bundle provides an autoloader class that loads all the bundles placed inside a 
given directory:

    app/AppKernel.php

    use RedKiteLabs\ThemeEngineBundle\Core\Autoloader\ThemesAutoloader;

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            ...
        );

        $themes = new ThemesAutoloader();
        $bundles = array_merge($bundles, $themes->getBundles());
    }

Using this feature or not, is totally up to you. If you prefer to manually add your themes to the AppKernel, you may do it as usual.

## Tutorial
A detailed tutorial on usage of ThemeEngineBundle can be found under the Resources/docs folder of the bundle itself.