Note: This chapther refers to AlphaLemon CMS because it was written for the website. ThemeEngineBundle is used by AlphaLemon CMS as theme engine so 
talkink about one or the other is the same thing.

# Create a new theme

A theme is a set of templates and each of them have their own assets, like stylesheets, javascripts and images packed together.

A theme for AlphaLemon Cms is is nothing more than a standard Symfony2 bundle, but, differently from a symfony2 bundle, it doesn't require to be registered in the AppKernel configuration file, because AlphaLemon CMS auto register your theme.

To start a new theme called HelloTheme, run the following built-in command from the root of your symfony2 application:

    app/console generate:bundle

Then answer as follows:

    Bundle namespace: Themes/HelloThemeBundle
    Bundle name: HelloThemeBundle
    Target directory: /path/to/your/project/vendor/bundles/AlphaLemon/ThemesBundle/Themes
    Configuration format (yml, xml, php, or annotation) [annotation]: [ENTER]
    Do you want to generate the whole directory structure [no]? [ENTER]
    Do you confirm generation [yes]?  [ENTER]

If everything is ok, you will get this response:

    Generating the bundle code: OK
    Checking that the bundle is autoloaded: OK

At last complete the bundle creation:

    Confirm automatic update of your Kernel [yes]? no
    Confirm automatic update of the Routing [yes]? no

This creates a new HelloThemeBundle under the ThemesBundle bundle.

## The design

After the bundle was created, you must convert your graphic design in xhtml markup language. Each theme can have as many templates you want and the only rule you must follow is how you name each template, because the template's name is mandatory.


The template you want to use as home page have to be named **home** and each other template has to be named **internal, internal1, internal2, ..., internal[n]**.

This convention is useful when you need to change the theme, so AlphaLemonCms will match the templates, by using their names.

Any particular convention is required to name the template's divs, but you should name them according to the rules explained below.

## From xhmtl to Twig

AlphaLemon Cms works only with TWIG templates, so when your templates are ready, you must convert them to 
twig. Start naming them to "home.twig.html", "internal.twig.html", "internal1.twig.html", "internal2.twig.html" 
and so on, then open one of them.

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
                    [ JUST SAVE THIS ]
            </body>
    </html>

Then you may start to replace the very last content of each div. For example consider the following code:

    <div id="header">
            <div id="logo">
                    <a href="#"><img src="images/logo.png" title="Download AlphaLemonCMS" alt="" /></a>
            </div>
    </div>
    [...]

The header div remains unthouched because it contains another div, instead of the logo div must be changed as
follows:

    <div id="header">
            <div id="logo">
                    {{ renderSlot('logo') }}
            </div>
    </div>
    [...]

So the rule is quite simple: the div which has to contain the content must be replaced with the built in twig's function 
**renderSlot**. This function requires a string as argument and this value represents the slot name. From now this div 
will be called slot.

The id to assign to the slot is not mandatory, so you could name it as you prefer, but it is a best practice to
name the slot's id and the slot name in the same way.

The twig template must be saved inside a Theme folder, inside the Resouces/views directory, so create that folder if 
it doesn't exist.

## External resources

Each template usually uses external resources as stylesheets, javascripts, images. A theme, as for a regular bundle,
requires that its external resources lives in the Resources folder and loads them by the Dependency Injector 
Container (a.k.a. DIC). Let's see how.

The HelloTheme's Home template uses the 960 css framework and requires two stylesheets to be loaded: the 960grid stylesheet and another one called screen.css, the stylesheet that formats the template graphic layout.

Open the services.xml under the Resources/config folder and add the following code inside the container     tags:

    <parameters>
            <parameter key="themes.hellotheme_home.stylesheets" type="collection">
                    <parameter>@HelloThemeBundle/Resources/public/css/960.css</parameter>
                    <parameter>@HelloThemeBundle/Resources/public/css/screen.css</parameter>
            </parameter>
    </parameters>

You may replace the standard code added by bundle creation. This adds to the DIC those two stylesheets
as an array.

This is enough to load the stylesheets because the bundle's generator has already generated the necessary
code to load the service. To see how it does this work, give a look to the HelloThemeExtension.php
class inside the DependencyInjection folder.

To add javascripts or stylesheets for a new template just add a new <parameter /> section to the parameters
section.

As you may notice in the code above, the stylesheets must live inside the Resources/public/css folder.

If you'll plan to use the 960 grid's framework, keep in mind that Alphalemon provides an external bundle that
manages it.


## Slots

Any slot can be named as you wish, but AlphaLemonCms provides a well format names ready to be used in your templates:

    header              middle_sidebar
    content             right_sidebar
    footer              screenshots_box
    logo                download_box
    small_logo          social_box	
    nav_menu            ads_box
    nav_menu_1          slogan_box
    nav_menu_2          search_box
    nav_menu_3          information
    nav_menu_4          license_box
    left_sidebar        copyright_box
    rss_box

You may use all of them, or simply don't use some if your theme doesn't require a particular slot or add new ones: its up to you. To let AlphalemonCms to know which slots are used by the template you must add a new class to your bundle. 

Create a src folder under your bundle and another one called Slots inside it. The folders' name are mandatory but the namespace is not required to be registered. Create a new class called as follows:

    [BundleName][TemplateName]Slots.php

in this example HelloThemeBundleHomeSlots.php under the Slots folder, open it and add the following code:

    namespace Themes\HelloThemeBundle\src\Slots;

    use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots;

    class HelloThemeBundleHomeSlots extends AlTemplateSlots
    {
    }

This code is enough to load all the predefined slots for your template. You can add new slots or changing the behavior for the existing ones by extending the AlTemplateSlots's configure method as follows:

    use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

    class HelloThemeBundleHomeSlots extends AlTemplateSlots
    {
            public function configure()
            {
                    return array('box_title_1' => new AlSlot('box_title_1'),
                                 'box_title_2' => new AlSlot('box_title_2'),
                                 'tweets_box' => new AlSlot('tweets_box'),);
            }
    }

As you can see another class, called AlSlot, is introduced. This object is basically a container where the slot information are saved and managed. The above code adds three new slots to the template, using the slot's dafault parameters.

You may change those parameters providing the second argument accepted by the AlSlot constructor as follows:

    public function configure()
    {
            return array('logo' => new AlSlot('box_title_1', array('defaultText' => "Place here the website's logo",
                         'contentType' => "Media",
                         'repeated' => "site")),);
    }

As you can see in the example above, the accepted parameters are:

    defaultText: The displayed text when a new page is added
    contentType: The content type used when a new page is added. The default value is Text.
    repeated: How the content is repeated through the site. 

## Repeated contents

A content usually lives on a single page of your website, but sometimes, it is required to repeat that content through the site's pages. 

Think about the site's logo: usually it is the same for the whole website's pages, or the navigation menu usually it is the same for all the pages, which have the same language. The repeated parameter manages this situation. It can be one of the following values:

    page        The content changes for each page
    group       The content is repeated for a group of pages
    language    The content is repeated for a the pages that belongs each language
    site        The content is repeated for the whole site
	
In the configure method you may change how a slot repeats its contents through the website. When you change a parameter, AlphaLemon CMS automatically adapts the contents repetion. This means that you may change how a slot repeats its contents trasparently, without worrying to do the job manually.

## Ready themes
There are many themes ready to use you can download from http://alphalemon website, which are made following the guidelines exposed in this tutorial. You should download
at least one of theme, BikersThemeBundle is a very good example because it has a good compromise number of templates, and dig into the code to learn how a theme is made.