# Add a custom Theme-App to AlphaLemon CMS
This chapter will explain how to add a new Theme-App to AlphaLemon CMS.

## What is a Theme
An AlphaLemon CMS Theme Aapplication could be defined as a collection of twig templates which have their own assets like
javascripts, stylesheets and images, packaged into a well-known structure, that defines a website design.

## How is structured a Theme
A Theme-App is a standalone symfony2 bundle. This approach has several advantages:

1. Is a Symfony2 Bundle
2. Is reusable in many web sites
3. Assets required by the content are packed into a well known structure

## Create the FancyThemeBundle
The very first step is to add a new bundle to your application. AlphaLemon does not require to have the bundle placed
into a specific location, so you can place it everywhere. Let's add this new bundle into the standard **src** folder:

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

Well done! Your very first Theme-App has been created! At the moment AlphaLemon CMS does not know yet that it will manage
that bundle as a Theme, so let's see how to tell AlphaLemon to use the FancyThemeBundle as a Theme.

## Add the Theme service
An AlphaLemon CMS theme must be defined as a service in the DIC **Dependency Injector Container**. To tell AlphaLemon CMS to manage
this bundle open the **service.xml** file under the bundle's **Resources/config** folder and add the following code:

    Acme/Theme/FancyThemeBundle/Resources/config/services.xml
    <services>
        <service id="fancy.theme" class="%alphalemon_theme_engine.theme.class%">
            <argument type="string">FancyTheme</argument>
        </service>
    </services>

This service defines an AlTheme object and its class has already been declared in the ThemeEngine services configuration and it is identified by the
**%alphalemon_theme_engine.theme.class%** parameter.

The theme's id is defined as **[theme_name].theme** and requires a string argument which contains the theme's name, **FancyTheme** in this case. To tell AlphaLemon
that this bundle is a Theme-App the service must be tagged as follows:

    Acme/Theme/FancyThemeBundle/Resources/config/services.xml
    <service id="fancy.theme" class="%alphalemon_theme_engine.theme.class%">
        [...]
        <tag name="alphalemon_theme_engine.themes.theme" />
    </service>

and the name option must always be **alphalemon_theme_engine.themes.theme**.

## Add a template
As saw for themes, templates must be declared in the **Dependency Injector Container**. Best practice is to add a new folder called **templates**
under the **Resources/config** directory, so add that folder and under it a new file using the name of the template to name the file itself, in this
case call it **home.xml**. Open it and add the following code:

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
        <service id="fancy.theme.template.home" class="%alphalemon_theme_engine.template.class%">
            <argument type="service" id="kernel" />
            <argument type="service" id="fancy.theme.template_assets.home" />
            <argument type="service" id="fancy.theme.template.home.slots" />
            <tag name="fancy.theme.template" />
        </service>

        <call method="setThemeName">
            <argument type="string">FancyThemeBundle</argument>
        </call>
        <call method="setTemplateName">
            <argument type="string">Home</argument>
        </call>
    </services>

This service defines an AlTemplate object and its class has already been declared in the ThemeEngine services configuration and it is identified by the
**%alphalemon_theme_engine.template.class%** parameter.

The template id has been defined as **[theme_name].template.[template_name]** and, while this scheme is not mandatory, you should follow it as best practice.

This service requires three arguments: the symfony's kernel service, a **fancy.theme.template_assets.home** service and a
**fancy.theme.template.home.slots** service which are the services mentioned above, that will be defined in a while.

The most important setting is the tag one, which name option must always follow the schema **[theme_name].template**, in this example **fancy.theme.template**.

As last the **setThemeName** and **setTemplateName** methods are called to define respectly the theme's name an the template's name.

### The template assets service
The template assets service contains the assets used by the template and it is defined as follows:

    Acme/Theme/FancyThemeBundle/Resources/config/templates/home.xml
    <services>
        <service id="fancy.theme.template_assets.home" class="%alphalemon_theme_engine.template_assets.class%">
            <call method="setExternalStylesheets">
                <argument type="collection">
                    <argument>@FancyThemeBundle/Resources/public/css/reset.css</argument>
                    <argument>@FancyThemeBundle/Resources/public/css/layout.css</argument>
                </argument>
            </call>
        </service>

        [...]
    </services>

This service defines an AlTemplateAssets object and its class has already been declared in the ThemeEngine services configuration and it is identified by the
**%alphalemon_theme_engine.template_assets.class%** parameter.

The template assets id has been defined as **[theme_name].template_assets.[template_name]** and, while this scheme is not mandatory, you should follow it as best practice.

It calls the **setExternalStylesheets** method to add two external stylesheets to the template. You may call several methods to define the template assets:

1. setExternalStylesheets - Adds some stylesheets to the template
2. setExternalJavascripts - Adds some javascripts to the template
3. setInternalStylesheets - Adds an internal stylesheet to the template
4. setInternalJavascripts - Adds an internal javascript to the template

### The template slots service
The last service to define is the **template slots** service. Each AlphaLemon's template is made by slots and each slot is the place where one or more blocks live.
The code that defines that service is the following:

    Acme/Theme/FancyThemeBundle/Resources/config/templates/home.xml
    <services>
        <service id="fancy.theme.template.home.slots" class="%alphalemon_theme_engine.template_slots.class%">
        </service>

        [...]
    </services>

This service defines an AlTemplateSlots object and its class has already been declared in the ThemeEngine services configuration and it is identified by the
**%alphalemon_theme_engine.template_slots.class%** parameter.

The template slots' id is defined as **[theme].template.[template_name].slots** and its implementation object has already been defined in
the theme engine.

The template assets id has been defined as **[theme_name].template.[template_name].slot** and is mandatory.

## The design
AlphaLemon Cms uses **twig** as template engine, so when you have converted the templates to html, you must write them to
twig.

### Clean the template
First of all the template does not need the header section because it is inherited by the symfony's
base twig template or from another custom one, so remove everything is external to the body tag:

    <!DOCTYPE html>
    <html>
        <head>
            <title></title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link href="stylesheets/screen.css" media="screen, projection" rel="stylesheet" type="text/css" />
            <link rel="stylesheet" href="stylesheets/960.css" />
        </head>
        <body>
            [ JUST KEEP THIS ]
        </body>
    </html>

### The slots
Now you must identify the slots on the template. The **slot** is the html tag that contains the content you want to edit. For
example consider the following code:

    <div id="header">
        <div id="logo">
            <a href="#"><img src="images/logo.png" title="Download AlphaLemonCMS" alt="" /></a>
        </div>
    </div>
    [...]

The content to edit is the one contained inside the div that has the logo id, so the only thing to do is to replace that content
with a built-in twig function called **renderSlot**:

    <div id="header">
        <div id="logo">
            {{ renderSlot('logo') }}
        </div>
    </div>
    [...]

This function requires a string as argument which is the name of the slot.

The id assigned to the slot is not mandatory, so you could name it as you prefer, but it is best practice to
name the slot's id and the slot name in the same way.

Another best practice to follow is to use the **renderSlot** function inside a **div** tag, so avoid something like this:

    <p id="logo">
        {{ renderSlot('logo') }}
    </p>

### Prepare your template to be overriden

That code is enough to render the contents placed on the slot logo, but if you plan to distribute your theme, you must
wrap the renderSlot function with a block instruction:

    <div id="header">
        <div id="logo">
            {% block logo %}
            {{ renderSlot('logo') }}
            {% endblock %}
        </div>
    </div>
    [...]

## Declare the template slots
The last thing to do is to define the slots for each template. This configuration is always made in the DIC and, as best practice, it
should live inside the **Resources/config/templates/slots** folder of your Theme-App, so add that folder and create a new **home.xml**
file inside. Open that file and add the following code:

    Acme/Theme/FancyThemeBundle/Resources/config/templates/slots/home.xml
    <?xml version="1.0" encoding="UTF-8" ?>
    <container xmlns="http://symfony.com/schema/dic/services"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

        <services>
        </services>
    </container>

The name file is not mandatory, but it should be named as the template where the slot lives. Now the logo slot must be defined,
so, inside the **services** tag add the following code:

    Acme/Theme/FancyThemeBundle/Resources/config/templates/slots/home.xml
    <service id="fancy.theme.template.home.slots.logo" class="%alphalemon_theme_engine.slot.class%">
        <argument type="string">logo</argument>
        <tag name="business_website_theme.template.home.slots" />
    </service>

This service defines an AlSlot object and its class has already been declared in the ThemeEngine services configuration and it is identified by the
**%alphalemon_theme_engine.slot.class%** parameter.

This object requires as first argument a string that defines the slot name.

As saw for other services, this service must be tagged following this scheme: **[theme_name].template.[template_name].slots**.

### Addictiona options for AlSlot object
The AlSlot object accepts an array of options as second argumentis an optiona array of options. The possibile values are:

1. blockType
2. htmlContent
3. repeated

### blockType option
Defines the block type AlphaLemon CMS must add for that slot when a new page is added and, by default, the block type added is Text.
For this slot the default type is good, so this option is not defined.

### htmlContent option
For the logo content we want that AlphaLemon adds the same content designed by the template's designer. To do this we must define
the **htmlContent** option as follows:

    Acme/Theme/FancyThemeBundle/Resources/config/templates/slots/home.xml
    <service id="fancy.theme.template.home.slots.logo" class="%alphalemon_theme_engine.slot.class%">
        <argument type="string">logo</argument>
        <argument type="collection" >
            <argument key="htmlContent">
                <![CDATA[
                    <a href="#"><img src="images/logo.png" title="Download AlphaLemonCMS" alt="" /></a>
                ]]>
            </argument>
        </argument>
        <tag name="business_website_theme.template.home.slots" />
    </service>

In this way every time a new page is added, the content added to the page by AlphaLemon CMS will be the one defined by the htmlContent option.
To use the default value added by the block, simply don't declare this option.

### repeated option
Most of the contents displayed on a web page are repeated through the website pages. For example the site logo usually is the same for all the
site's pages, while a navigation menu is the same for a specific language.

The repeated option manages this behavior and repeats the content for the blocks that live on a slot. The possibile values for this option are:

1. page (default)
2. language
3. site

The logo for this website must be the same on each page, so we add the repeated option as follows:

    <argument type="collection" >
        [...]
        <argument key="repeated">site</argument>
    </argument>