# ThemeEngineBundle
ThemeEngineBundle adds theming feature to any Symfony2 application.You can pack a theme as a standard Symfony2 Bundle and manage it 
through a simple web interface, that lets you upload, add, activate and remove themes. 

This bundle is the theme engine used by AlphaLemon CMS.

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
            new AlphaLemon\ThemeEngineBundle\AlphaLemonThemeEngineBundle(),
        )
    }

Register the ThemeEngineBundle namespaces in `app/autoload.php`:

    $loader->registerNamespaces(array(
        ...
        'AlphaLemon'                     => __DIR__.'/../vendor/bundles',
        'ThemeEngineCore'                => __DIR__.'/../vendor/bundles/AlphaLemon/ThemeEngineBundle/src',
        'Themes'                         => __DIR__.'/../vendor/bundles/AlphaLemon/ThemeEngineBundle',
    ));

As you know each website has some contents that are repeated through the website, for example the site's logo must be repeated for all 
the pages of the website while the navigation menu usually is repeated for each language. The ThemeEngineBundle lets you manage this special 
kind of contents in a easy way: you just declare them into a yml file and the bundle will manage them for you. This file must be defined in 
the config.yml configuration file, as follows:

    alpha_lemon_theme_engine:
        slot_contents_dir: src/[Company/Bundle]/Resources/slotContents

Replace the [Company/Bundle] with yours.

At last you must import the routing configuration, so open the routing.yml file and adds the following directive:

    _alphaLemonThemeEngineBundle:
        resource: "@AlphaLemonThemeEngineBundle/Resources/config/routing.yml"


Install the assets as follows:

    app/console assets:install web --symlink

To complete the installation you must configure the other mandatory bundles. You can find detailed instructions within those bundles themselves.

## Autoloading

The ThemeEngineBundle provides a complete html interface to manage the themes that can be uploaded and added to your application. Themes are bundles and, 
as each bundle, must be loaded in the registerBundles() method. Sometimes this could be a pain to manually add the bundle to the AppKernel, after you have 
imported it with the web interface. To automate this operation you may use the autoloader provided with the bundle, as follows:

    app/AppKernel.php

    use ThemeEngineCore\Autoloader\ThemesAutoloader;

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            ...
        );

        $themes = new ThemesAutoloader();
        $bundles = array_merge($bundles, $themes->getBundles());
    }

Using this feature or not, is totally up to you. If you prefer to manually add your themes to the AppKernel, you may do it as you do for a standard bundle.

## Configure propel
ThemeEngineBundle needs a database to manage the themes and uses Propel as predefined ORM. To configure Propel open the app/config/config.yml and adds the following
configuration:

    propel:
        path:       "%kernel.root_dir%/../vendor/propel"
        phing_path: "%kernel.root_dir%/../vendor/phing"

        dbal:
            driver:               mysql
            user:                 [USER]
            password:             [PASSWORD]
            dsn:                  mysql:host=localhost;dbname=themeEngineSandbox
            options:              {}
            attributes:           {}
            default_connection:   default

After that from the console run this three commands:

    app/console propel:database:create
    app/console propel:build
    app/console propel:insert-sql --force

If you need additional information, you may refer the Propel official documentation.

## Usage

The controllers of your application must inherit from the TemplateController instead of the Symfony2 Controller. This controller implements a protected method 
called setUpPageTree() which should be called from your controller actions:

    /path/to/your/application/bundle/Controller/WebSiteController.php
    use AlphaLemon\ThemeEngineBundle\Controller\TemplateController;

    class WebSiteController extends TemplateController
    {
        /**
         * @Route("/test")
         */
        public function testAction()
        {
            $this->setUpPageTree('home', 'custom-dictionary');
        }
    }

The setUpPageTree() method requires as first argument the name of the template the action must render and as second argument accepts the name of the dictionary
to use. This last parameter is not mandatory. That method simply initializes the pageTree object within the template you want to render. When it is bootstrapped,
you can retrive and manage it as follows:
    
    /path/to/your/application/bundle/Controller/WebSiteController.php
    public function testAction()
    {
        $this->setUpPageTree('home', 'test');

        // Retrieve the pageTree object from the container
        $pageTree = $this->container->get('al_page_tree');

        // Adds a new content into the slogan_box slot
        $pageTree->addContent('slogan_box', array('<h1>Welcome to AlphaLemon ThemeBundle</h1>')); 

        // Defines the template to render
        $template = sprintf('%s:Theme:%s.html.twig', $pageTree->getThemeName(), $pageTree->getTemplateName());

        // Renders the template
        return $this->render($template, array('metatitle' => 'A site powered by AlphaLemonThemesBuilder bundle',
                                             'metadescription' => '',
                                             'metakeywords' => '',
                                             'internal_stylesheets' => '', // or $pageTree->getInternalStylesheet()
                                             'internal_javascripts' => '', // or $pageTree->getInternalJavascript(),
                                             'stylesheets' => $pageTree->getExternalStylesheetsForWeb(),
                                             'javascripts' => $pageTree->getExternalJavascriptsForWeb(),
                                             'base_template' =>  $this->container->getParameter('althemes.base_template')));
    }
    
During the configuration process, it was mantioned the slotContents.yml which contains the repeated content. Follows a sample configuration:

    /path/to/your/slotContent/slotContent.yml
    slots:
      search_box:
        0: |
          Search <input type="text" value="" size="10" /> <input type="submit" />

      logo:
        0: |
          The company logo

      nav_menu:
        0: |
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contacts</a></li>
          </ul>
      
      ...

This file is processed by the setUpPageTree() method which fills up the slots declared inside this file. You may declare as many contents you
want for each slot.

## Add a theme
AlphaLemon provides a base the AlphaLemonThemeBundle theme, you can use as example to try the ThemeEngineBundle. So download it as a zip file from github:

    https://github.com/alphalemon/AlphaLemonThemeBundle

then open a browser window and navigate the following route:

    http://yoursite/en/al_showThemes

Remember that the ThemeEngine accepts only themes packed as zip files.

## Build a custom theme
To build your own theme you may read the tutorial (http://alphalemon.com/en/add-a-custom-theme-to-alphalemon-cms)[add a custom theme to alphalemon cms]

### Info and help
To get extra information or help you may write an email to info [at] alphalemon [DoT] com