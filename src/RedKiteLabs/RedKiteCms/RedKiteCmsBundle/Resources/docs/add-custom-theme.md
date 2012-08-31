# Add a custom Theme-App to AlphaLemon CMS

This chapter will tell you how to add a new Theme to AlphaLemon CMS.

## What is a Theme

A Theme is a collection of twig templates which have their own assets like javascripts, stylesheets, images, that defines
a website design.

## How is structured a Theme

A Theme-App is a standalone symfony2 bundle. This approach has several advantages:

1. Is a Symfony2 Bundle
2. Is reusable in many web sites
3. Assets required by the content are packed into a well known structure

## Create the FancyThemeBundle

The very first step is to add a new bundle to your application. AlphaLemon does not require to place the bundle into a specific
location, so you can place it everywhere. Let's add this new bundle to the standard **src** folder:

    php app/console generate:bundle


    Welcome to the Symfony2 bundle generator
    [...]

    Use / instead of \ for the namespace delimiter to avoid any problem.

Enter the bundle name, as follows:

    Bundle namespace: Acme/Theme/FancyThemeBundle

    In your code, a bundle is often referenced by its name. It can be the
    concatenation of all namespace parts but it's really up to you to come
    up with a unique name (a good practice is to start with the vendor name).
    Based on the namespace, we suggest AcmeFancyBlockBundle.

The proposed bundle **name must be changed** to FancyThemeBundle:

    Bundle name [AcmeThemeFancyThemeBundle]: FancyThemeBundle

    The bundle can be generated anywhere. The suggested default directory uses
    the standard conventions.

The proposed folder is fine:
    
    Target directory [/home/alphalemon/www/AlphaLemonCmsSandbox/src]:

Leave the next options as proposed:

    Determine the format to use for the generated configuration.

    Configuration format (yml, xml, php, or annotation) [annotation]:

    To help you getting started faster, the command can generate some
    code snippets for you.

    Do you want to generate the whole directory structure [no]?


    Summary before generation


    You are going to generate a "Acme\FancyThemeBundle\FancyThemeBundle" bundle
    in "/home/alphalemon/www/AlphaLemonCmsSandbox/src" using the "annotation" format.

    Do you confirm generation [yes]?


  Bundle generation


    Generating the bundle code: OK
    Checking that the bundle is autoloaded: OK

Please, say **NO** to Kernel's and Routind update request:

    Confirm automatic update of your Kernel [yes]? no
    Enabling the bundle inside the Kernel: FAILED
    Confirm automatic update of the Routing [yes]? no
    Importing the bundle routing resource: FAILED

Well done! Your very first Theme-App has been created! At the momoent AlphaLemon CMS does not know yet that it will manage
that bundle, so let's see how to tell AlphaLemon to use the FancyThemeBundle.

## Add the Theme service
An AlphaLemon CMS theme must be defined as a service in the DIC **Dependency Injector Container**. To tell AlphaLemonCMS to manage 
this bundle open the **service.xml** file under the bundle's **Resources/config** folder and add the following code:

    Acme/Theme/FancyThemeBundle/Resources/config/services.xml
    <services>
        <service id="app_fancy.theme" class="%alphalemon_theme_engine.theme.class%">
            <argument type="string">FancyTheme</argument>
        </service>
    </services>

A new service named **app_fancy.theme** has been declared and its class is not im0plements in this bundle, but it has already been
implemented in the ThemeEngineBundle and used here. This object requires a string argument which contains the theme's name, **FancyTheme**
in this case. 

To tell AlphaLemon that this bundle is a Theme-App the service must be tagged as follows:

    Acme/Theme/FancyThemeBundle/Resources/config/services.xml
    <service id="app_fancy.theme" class="%alphalemon_theme_engine.theme.class%">
        [...]
        <tag name="alphalemon_theme_engine.themes.theme" />
    </service>

And the name option must always be **alphalemon_theme_engine.themes.theme**.

## Add a template
As saw for themes, templates must be declared in the **Dependency Injector Container**. Best practice is to add a new folder called **templates**
under the **Resources/config** directory, so add that folder and under it a new file called with the name of the template, in this case call it
**home.xml**. Open it and add the following code:

    Acme/Theme/FancyThemeBundle/Resources/config/templates/home.xml
    <?xml version="1.0" encoding="UTF-8" ?>
    <container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

        <services>
        </services>
    </container>

To define a valid template you must initialize three services:

1. The template
2. The template assets
3. The template slots.

### The template service
To add a new template service add the following code:

    Acme/Theme/FancyThemeBundle/Resources/config/templates/home.xml
    <services>
        <service id="fancy_theme.template.home" class="%alphalemon_theme_engine.template.class%">
            <argument type="service" id="kernel" />
            <argument type="service" id="fancy_theme.template_assets.home" />
            <argument type="service" id="fancy_theme.template.home.slots" />
            <tag name="app_fancy.theme" />
        </service>

        <call method="setThemeName">
            <argument type="string">FancyThemeBundle</argument>
        </call>
        <call method="setTemplateName">
            <argument type="string">Home</argument>
        </call>
    </services>

The template's id is defined as **[theme].template.[template_name]** and, as for the themes its implementation object has already been defined in 
the theme engine. It requires three arguments: the symfony's kernel service, a **fancy_theme.template_assets.home** service and a 
**fancy_theme.template.home.slots** service which are the ones mentioned above that will be defined in a while.

The most important setting is the tag one, which name option must be the id of the theme service the template belongs, in this example **app_fancy.theme**.

As last thing the **setThemeName** and **setTemplateName** methods are called to define respectly the theme's name an the template's name.

### The template assets service
The template assets service contains the assets used by the template. The service is defined as follows:

    Acme/Theme/FancyThemeBundle/Resources/config/templates/home.xml
    <services>
        <service id="fancy_theme.template_assets.home" class="%alphalemon_theme_engine.template_assets.class%">
            <call method="setExternalStylesheets">
                <argument type="collection">
                    <argument>@FancyThemeBundle/Resources/public/css/reset.css</argument>
                    <argument>@FancyThemeBundle/Resources/public/css/layout.css</argument>
                </argument>
            </call>
        </service>

        [...]
    </services>

The template's id is defined as **[theme].template_assets.[template_name]** and, as for the themes its implementation object has already been defined in 
the theme engine. 

It calls the **setExternalStylesheets** method to add two external stylesheets to the template, so you may call the following methods to define
the template assets:

1. setExternalStylesheets - Adds some stylesheets to the template
2. setExternalJavascripts - Adds some javascripts to the template
3. setInternalStylesheets - Adds an internal stylesheet to the template
4. setInternalJavascripts - Adds an internal javascript to the template

### The template slots service      
The last service to define is the **template slots** service. Each AlphaLemon's template is made by slots and each slot is the place where the blocks live.
The code that defines that service is the following:

    Acme/Theme/FancyThemeBundle/Resources/config/templates/home.xml  
    <services>
        <service id="fancy_theme.template.home.slots" class="%alphalemon_theme_engine.template_slots.class%">
            <tag name="fancy_theme.template.home" />
        </service>

        [...]
    </services>

The template slots' id is defined as **[theme].template.[template_name].slots** and its implementation object has already been defined in 
the theme engine. 

As for other services, it requires a tag, which name option must be the id of the template service the template slots belongs, in this example **fancy_theme.template.home**.



## Declare the template slots

