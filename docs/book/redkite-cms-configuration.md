# Configure RedKite CMS
RedKite CMS handles its own configuration in several json files saved under the **lib/config** folder. There are three configuration files here:

    general.json
    commands.json
    assets.json
    
The **general.json** file defines the general settings of the Redkite CMS, the **commands.json** file defines the Control Panel command buttons, the **assets.json** file defines the assets used by RedKite CMS depending on the context.

You can customize these settings for the whole group of sites handled by RedKite CMS or by site, just by adding a new configuration file into the **/app/config** folder affecting the whole group of sites, or under the **/app/[site]/config** folder to affect only that single website.

## General setting
To change a general setting just add a **general.json** file under the **/app/config** folder and paste this code inside:

    // app/config/general.json
    {
      "baseTemplate": "Path/To/Custom/Base/Template/base.html.twig"
    }
    
This code will replace the **baseTemplate** general setting with a custom setting.

## Add a new command button
To add a new command button just add a **commands.json** file under the **/app/config** folder and paste this code inside:

    // app/config/commands.json
    [
      [
        {
          "id": "rkcms-custom-button",
          "label": "common_label_custom_button",
          "icon": "cog",
          "binding": "click: customFunction"
        }
      ],
    ]
    
A button is defined as an array and has four attributes: the html **id** tag, the label, a font-awesome icon and a knockout binding function, which must always be implemented. 

You can also define a toggle button as follows:
 
    // lib/config/commands.json
    [
      [
        {
          "id": "rkcms-button-edit",
          "class": "rkcms-edit",
          "label": "control_panel_edit_contents_button",
          "icon": "pencil",
          "binding": "visible: editorStatus()=='off', click: startEdit"
        },
        {
          "id": "rkcms-button-stop",
          "class": "rkcms-edit",
          "label": "control_panel_stop_edit_contents_button",
          "icon": "times",
          "binding": "visible: editorStatus()=='on', click: stopEdit"
        }
      ],
      ...
    ]
    
This is where the RedKite CMS Edit button is defined. It shows the first button when the editor is not active and the second one when the editor is activated.

## Manage assets
The assets are defined in the **assets.json** file. It has several potential assets to be used depending on the context, so we have assets for production identified by the **prod** key, assets for the editor identified by the **cms** key and also assets for the dashboard identified by the **dashboard** key.

Suppose we want to change the default stylesheet to highlight a section of code on the page for a specific site. This asset is obviously declared both in production and in the cms editor sections, so we need to change both of them. Add this code to the **assets.json** file under the site in which you want to change the style:

    // app/data/[site]/config/assets.json
    {
      "prod" :
      {
        "getExternalStylesheets": [
            "%web_dir%/components/redkitecms/twitter-bootstrap/css/bootstrap.min.css",
            "%web_dir%/components/redkitecms/font-awesome/css/font-awesome.min.css",
            "%web_dir%/components/highlight/styles/monokai.css"
        ]
      },
      "cms" :
      {
        "getExternalStylesheets": [
            "%web_dir%/components/highlight/styles/monokai.css"
        ]
      }
    }

This code will add the monokai.css for both the contexts, but in a different way. in fact RedKite CMS will entirely replace the base **prod** section with the custom one and will merge the custom configuration with the default for other contexts.

## Customize the application configuration
The RedKite CMS application is bootstrapped instantiating the RedKiteCms class saved in the root data folder of your website.
This object uses two methods, **configure** and **register**, which you can use to change the application
behaviour, adding new services, listeners or routes.

When a new site is bootstrapped, RedKite CMS creates the file and implements those methods adding two empty functions, so you
are not required to do anything if you do not need any customization. Here's the generated code:

    // app/data/[site name]/RedKiteCms.php
    class RedKiteCms extends RedKiteCmsBase
    {
        protected function configure()
        {
            // Return an array of options to change RedKite CMS internal configuration
            // or an empty array to use the default configuration
            return array();
        }

        protected function register(Silex\Application $app)
        {
        }
    }


## Change the configuration
The **configure** method requires to return an array of options you can use to change the web folder and the assets upload
folders by now.

To change the web folder just return an array with the **web_dir** option configured. For example if you need to change the
name to **public_html** because your website host gives you a static folder to handle the website public elements you cannot
rename, you can change it as follows:

    // app/data/[site name]/RedKiteCms.php
    protected function configure()
    {
        return array(
            'web_dir' => 'public_html',
        );
    }

If you are not working on a remote server, you must configure the virtual host in accordance with this change and you must
rename RedKite CMS web folder as **public_html**

You can change the folder where assets are saved. To do so, just configure the **uploads_dir** option as follows:

    // app/data/[site name]/RedKiteCms.php
    protected function configure()
    {
        return array(
            'uploads_dir' => '/assets',
        );
    }

That configures the upload folder to **assets** folder instead of the RedKite CMS default one.

## Register additional services, listeners and routes
The **register** method allows you to add extra services, listeners or routes to your application. This method is executed after
RedKite CMS has registered its services but before booting the CMS itself.

### Register a service
To register the **Doctrine** service, which is not included with RedKite CMS, you can use this code:

    // app/data/[site name]/RedKiteCms.php
    protected function register(Silex\Application $app)
    {
        $app->register(new Silex\Provider\DoctrineServiceProvider(), array(
            'db.options' => array(
                'driver'   => 'pdo_sqlite',
                'path'     => __DIR__.'/app.db',
            ),
        ));
    }

Obviously do not forget to require **Doctrine** in your composer file

### Add a listener
To add a new listener just add a code like this:

    // app/data/[site name]/RedKiteCms.php
    protected function register(Silex\Application $app)
    {
        $app["red_kite_cms.listener.slot_rendering_download"] = new DownloadRenderingListener($app["twig"]);
        $app["dispatcher"]->addListener('slots.rendering.download', array($app["red_kite_cms.listener.slot_rendering_download"], 'onSlotsRendering'));
    }

Here we registered the custom **DownloadRenderingListener** which listens to the **slots.rendering.download** event. The purpose
of this listener is to replace the main content of the download page with a custom one rendered in the listener itself.


### Add a new route
Similarly we can add a new route for the frontend and the backend to show a hypothetical **awesome-report** page:

    // app/data/[site name]/RedKiteCms.php
    protected function register(Silex\Application $app)
    {
        $routes = array(
            array(
                'pattern' => "/awesome-report",
                'controller' => 'Controller\AwesomeReportController::showAction',
                'method' => array('get'),
                'bind' => '_awesome_report',
            ),
            array(
                'pattern' => "/backend/awesome-report",
                'controller' => 'Controller\AwesomeReportController::showAction',
                'method' => array('get'),
                'bind' => '_backend_awesome_report',
            ),
        );

        $this->routingServiceProvider->addRoutes($this->app, $routes);
    }

Here we defined the **awesome-report** route and registered it using the **routingServiceProvider** object.

Found a typo? Found something wrong in this documentation? [Just fork and edit it !](https://github.com/redkite-labs/RedKiteCms/edit/master/docs/book/redkite-cms-configuration.md)
