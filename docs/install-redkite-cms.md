# RedKite CMS
Thanks for interesting in the RedKite CMS Application, a full Content Management
System built on top of Symfony2 components, jQuery, Knockout and Bootstrap frameworks.

## Requirements
1. A web-server like Apache or Nginx
2. Php 5.3.3+

## Download
Download your free `RedKite CMS Application`_ copy now!

## Set up RedKite CMS Application
You need to unpack the RedKite CMS Application into your web server.

If you want to use the application on a remote server, just follow the guide lines
from your hosting provider, minding to configure your host to point the application
**web** folder.

If you want to use RedKite CMS on your computer you need to configure the web server
to handle this new web site.

RedKite CMS can handle multiple websites with a single installation basing on the
requested domain, so the best choice to configure the web server is to add a new
virtual host.

## Web server configuration
Add a new **virtualHost** to the web-server to handle the application and configure 
its **DocumentRoot** to point the RedKite CMS **web folder**. Let's assuming you
configured the **redkitecms** virtualhost.

Please refer [this guide](http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html) 
if you are not comfortable to set up a web-server

Before starting the application you should be able to do some adjustments to your
configuration.

### Advanced configuration
When you work on a web site locally on your computer, you probably would like to tranfer
that site on a remote server when you will finish. Let's supposing you are working on the 
**http://example.com**.

To correctly configure the virtualhosr to handle that domain on your local computer,
you need to suffix the server name with the **.local** token, so the host must be
**http://example.com.local**.

RedKite CMS will handle a data folder called **http://example.com**, ignoring the suffix.
When you will deploy your site from your computer to the remote server, you just need
to transfer that folder which matches the remote site name host you are working on.

### Permissions 
RedKite CMS requires the **app** and **web/upload** folders must be writable by the web server.
At [Symfony website](http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup) 
there's a comprehensive section which explains in detail this part of the configuration.

### Xdebug configuration
When you use **Xdebug** with your php installation, you need to configure that module
as follows:

    xdebug.max_nesting_level=1000

otherwise you could get an error or, worse, the page rendering could stop, without
displaying nothing on the screen.

If you don't use **Xdebug** you can safety skip this step.

## Start RedKite CMS
Start working with RedKite CMS is just opening the host you configured, so point the
browser to **http://redkite** host and you should see the login page.

Enter **admin** as **user and password** and start enjoying RedKite CMS!