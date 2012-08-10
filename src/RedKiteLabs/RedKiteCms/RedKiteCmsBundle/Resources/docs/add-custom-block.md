# Add a custom App-Block to AlphaLemon CMS

This chapter will tell you how to add a new Block to AlphaLemon CMS.

## What is a Block

A Block represent a content displayed on a web page. In addiction an app-block includes the editor to manage 
the block itself by AlphaLemon CMS when the editor is active. 

## How is structured a Block

An App-Block is a standalone symfony2 bundle. This approach has several advantages:

1. Is a Symfony2 Bundle
2. Is reusable in many web sites
3. Assets required by the content are packed into a well known container

## Create the FancyBlockBundle

The very first step is to add a new bundle to your application. AlphaLemon does not require to place the bundle into a specific
location, so you can place it everywhere. Let's add this new bundle to the standard **src** folder:

    php app/console generate:bundle


    Welcome to the Symfony2 bundle generator
    [...]

    Use / instead of \ for the namespace delimiter to avoid any problem.

Enter the bundle name, as follows:

    Bundle namespace: Acme/FancyBlockBundle

    In your code, a bundle is often referenced by its name. It can be the
    concatenation of all namespace parts but it's really up to you to come
    up with a unique name (a good practice is to start with the vendor name).
    Based on the namespace, we suggest AcmeFancyBlockBundle.

The proposed bundle **name must be changed** to FancyBlockBundle:

    Bundle name [AcmeFancyBlockBundle]: FancyBlockBundle

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


    You are going to generate a "Acme\FancyBlockBundle\FancyBlockBundle" bundle
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

Well done! Your very first App-Bundle has been created! At the momoent AlphaLemon CMS does not know yet that it will manage
that bundle, so let's see how to tell AlphaLemon to use the FancyBlockBundle.

## The basis of AlBlockManager object
AlphaLemon CMS requires you to implement a new class derived from the **AlBlockManager** object. This class can be placed everywhere
into the FancyBlockBundle's folder, but it is a best practice to ad it under the **FancyBlockBundle/Core/Block** folder.

So create those two folders and add a new php class and call it **AlBlockManagerFancyBlock**. The name is not mandatory, but, as
saw for the folder's name it is best practice to prefix the bundle's name with **AlBlockManager**.

Open that file and add the following code:

    namespace AlphaLemon\Block\FancyBlockBundle\Core\Block;

    use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;

    /**
     * AlBlockManagerFancyBlock
     *
     * @author [ Put here your name ]
     */
    class AlBlockManagerFancyBlock extends AlBlockManager
    {
    }

This new object symply extends the AlBlockManager base class, but at the moment, it is not completed,
because the parent object requires you to implement a method that defines the default value displayed 
on the web page when a new content is added. 

This method is called **getDefaultValue** and its implementation for this object is simply the following one:

    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {
        return array("HtmlContent" => '<p>My Fancy block</b>');
    }

This method returns and array, which may have the following options:

    1. HtmlContent
    2. ExternalStylesheet
    3. InternalStylesheet
    4. ExternalJavascript
    5. InternalJavascript

Combining the available options, you may have the desidered behaviour your content must have, when it is added to the 
web page. In our example just a simple html paragraph is added to the page. 

> The ExternalStylesheet and ExternalJavascript must contain a string of assets separated by a comma value.

## How to tell AlphaLemonCMS to manager the Bundle
An App-Block Bundles must be declared as services in the **Dependency Injector Container**. To tell AlphaLemonCMS to manage 
this bundle open the **service.xml** file under the bundle's **Resources/config** folder and add the following code:

    <parameters>
        <parameter key="app_fancy_block.block.class">Acme\FancyBlockBundle\Core\Block\AlBlockManagerFancyBlock</parameter>
    </parameters>

    <services>
        <service id="app_fancy_block.block" class="%app_fancy_block.block.class%">
            <argument type="service" id="event_dispatcher" />
        </service>
    </services>

A new service named **app_fancy_block.block** has been declared. Please notice that this object requires the symfony2 **event_dispatcher** 
service passed as argument.

But that's not enough to declare that service as a App-Block, in fact to achieve that task, the service must be tagged as follows:

    <service id="app_fancy_block.block" class="%app_fancy_block.block.class%">
        [...]
        <tag name="alphalemon_cms.blocks_factory.block" description="Fancy block" type="FancyBlock" group="acme_company" />
    </service>

The block's tag accepts serveral options:

- **name**: identifies the block. Must always be **alphalemon_cms.blocks_factory.block**
- **description**: the description that describes the block in the menu used to add a new block on the page
- **type**: the block's class type which **must be** the Bundle's name without the Bundle suffix
- **group**: blocks that belong the same group are kept togheter and displayed one next the other in the menu used to add a new block on the page

## Enabling the block
To have the bundle work, it must be enabled in the AppKernel class, so open your **AppKernel.php** and add the following code to the
**registerBundles** method:

    /app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            [...]
            new Acme\FancyBlockBundle\FancyBlockBundle(),
        );

        [...]
    }

To check if everything is right, open AlphaLemonCMS in your browser, enter in **Edit mode**, right click on a block and verify that
the **Fancy block** entry has been added to **Add** menu. 

You made a great work since now, so, glad yourself and add the Fancy block to the page.

## The editor
If you were impatient and you clicked on the Fancy block, you got an error message. This because any editor for the Fancy block has 
been added yet.

Adding a new editor is really simple, if fact the only required thing to do is to add a new twig file that must live under the 
**Resources/views** folder of the FancyBlockBundle, into a directory called **Block**. The file name must follow this convention:

    **[bundle name without bundle suffix in lower case]_editor.html.twig**

Add the **fancyblock_editor.html.twig** file, then open it and add the following code to take advantage from the base editor that comes 
with AlphaLemonCms:

    {% extends 'AlphaLemonCmsBundle:Block:base_editor.html.twig' %}

The base editor can manage several aspects related to the content:

- html (rich_editor / html_editor)
- internal_javascript (internal_js)
- internal_stylesheet (internal_css)
- external_javascripts (external_js)
- external_stylesheets (external_css)

To enable the editor you must add a parameter to the services configuration file:

    <parameter key="fancyblock_editor_settings" type="collection">
        <parameter key="rich_editor">true</parameter>
        <parameter key="external_js">true</parameter>
    </parameter>

That configuration enables both the editor to manage the html content as a rich editor and the editor to manage the external 
javascripts. So you just combine those options to get the editor you need.

### Custom editor
Sometimes you may need to add a custom editor. What you need to do is to **follow the naming conventions** exposed before, to correctly name
the editor, then add your custom code to the editor. And example could be this one:

    {% extends 'AlphaLemonCmsBundle:Elfinder:media_library.html.twig' %}

    {% block init_script %}
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('<div/>').dialogelfinder({
                url : frontController + 'backend/' + $('#al_available_languages').val() + '/al_elFinderMediaConnect',
                lang : 'en',
                width : 840,
                destroyOnClose : true,
                commandsOptions : {
                    getfile : {
                        onlyURL  : false,
                    }
                },
                handlers: {
                    destroy: function(event){ isEditorOpened = false;$('#al_editor_dialog').dialog('destroy').remove(); }
                },
                getFileCallback : function(file, fm) {
                    $('#al_file').val(file.path);
                    $('#al_file').EditBlock('HtmlContent');
                    $('#al_file').val('');
                }
            }).dialogelfinder('instance');
        });
    </script>
    {% endblock %}

which renders the ElFinder media library tool.

## Share your App-Bundle
The Bundle just created works at the moment but it could difficult to share it with the world. To achieve this job something must 
be changed.

### VCS
The very first thing you need is to put your code under a **VCS tool**. You may use whatever you want, but it's strongly suggested to 
use **git** as VCS and **github** as remote repository.

### The composer.json file
The Bundle is shared is by [composer](http://getcomposer.org) an awesome package manager tool. If you don't know it or how it works, there 
is a great documentation on their site which explains how to start with it.

Add a new composer.json file under the FancyBlockBundle folder and paste this code:

    {
        "autoload": {
                "psr-0": { "AlphaLemon\\Block\\FancyBlockBundle": ""
            }
        },
        "target-dir" : "AlphaLemon/Block/FancyBlockBundle"
    }

By reading the code, you should have understood that something must be changed in Bundle's namespaces because the filesystem 
structure will change when composer will install your App-Bundle, infact it will install the bundle under 

    [your repo name]/AlphaLemon/Block/FancyBlockBundle

If you come from this tutorial, you must rename all the namespaces created by the bundles generator wizard to reflect the new
namespace. When you will create your next App-Bundle you will enter the right values to avoid this step. 

So, to rename the namespaces you may use an editor that will replace all the occourences of your old namespace to the new one:

    old: Acme\FancyBlockBundle
    new: AlphaLemon\Block\FancyBlockBundle

Publish your Bundle to **github** then add the Bundle to [packagist](http://packagist.org) to let it be distributable by composer.
But there is abetter solution instead using packagist: you should email us to add your bundle to our packages system, to avoid 
spamming packagist with bundles made for a specific application. So feel free to write at **info [aT] alphalemon [DoT] com** to have
your bundle managed by our packagist.


### Autoload your bundle
It's quite difficult to ask a user that uses AlphaLemon CMS and wants to try your Bundle to add it to the AppKernel file of his
application.

For this reason AlphaLemon takes advantage of the **BootstrapBundle** that takes care to autoload a bundle. This step is well
documented in the [BootstrapBundle](http://github.com/alphalemon/bootstrapbundle) README file.

## Learn for existing App-Bundles
There are several full working, well commented App-Blocks you may explore, to learn how to add advanced configuration to create a
great App-Bundle for AlphaLemon CMS.

A serie of tutorials will explain in detail how some App-Bundles have been developed.