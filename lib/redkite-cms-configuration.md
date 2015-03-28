# Configure RedKite CMS
RedKite CMS application is bootstrapped instantiating the RedKiteCms class saved in the root data folder of your website.
This object requires to implement two methods **configure** and **register** which you can use to change the application
behaviour, adding new services or listeners or routes.

When a new site is bootstrapped, RedKite CMS creates that file and implements those methods adding two empty functions, so you
are not required to do nothing if you do not need any customization. Here's the generated code:

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
name to **public_html** because your website hoster gives you a static folder to handle the website public elements you cannot
rename, you can change it as follows:

    // app/data/[site name]/RedKiteCms.php
    protected function configure()
    {
        return array(
            'web_dir' => 'public_html',
        );
    }

If you are not working on a remote server, you must configure the virtual host in according with this change and you must
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

##
{
  "prod" :
  {
    "getExternalStylesheets": [
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

## Register additional services, listeners and routes
The **register** method allow to add extra services, listeners or routes to your application. This method is executed after
RedKite CMS registered its services and before booting the CMS itself.

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

Obviously do not forget to require **Doctrine** with your composer file

### Add a listener
To add a new listener just add a code like this:

    // app/data/[site name]/RedKiteCms.php
    protected function register(Silex\Application $app)
    {
        $app["red_kite_cms.listener.slot_rendering_download"] = new DownloadRenderingListener($app["twig"]);
        $app["dispatcher"]->addListener('slots.rendering.download', array($app["red_kite_cms.listener.slot_rendering_download"], 'onSlotsRendering'));
    }

Here we registered the custom **DownloadRenderingListener** which listens to **slots.rendering.download** event. The purpose
of this listener is to replace the main content of the download page by a custom one rendered in the listener itself.


### Add a new route
Similarly we can add a new route for the frontend and the backend to show an hypothetical **awesome-report** page:

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

Here we defined the **awesome-report** route and registered using the **routingServiceProvider** object.

