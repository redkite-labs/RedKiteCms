# RedKite CMS
Thank you for your interest in the RedKite CMS Application, the full Content Management
System built on top of Symfony2 components, jQuery, Knockout and Bootstrap frameworks
that allows you to build a website as easily as you make a cup of coffee.

## Requirements
To use Redkite CMS you will need the following:

1. A web-server like Apache or Nginx
2. Php 5.3.3+

## Get the application
To use RedKite CMS 2 simply install using composer:

    curl -s http://getcomposer.org/installer | php

then run the following command to begin a new RedKite CMS application:

    php composer.phar create-project redkite-labs/redkite-cms -s dev RedKiteCms

## Set up RedKite CMS Application
Next you need to unpack the RedKite CMS Application into your web server.

If you want to use the application on a remote server, just follow the guidelines
from your hosting provider, making sure that you configure your host to point to the
**web** folder of the Redkite CMS application.

If you want to use RedKite CMS on your computer you will also need to configure the web server
to handle this new web site.

RedKite CMS can handle multiple websites with a single installation based on the
requested domain, so it is best practice to configure the web server to add a new
virtual host for each domain.

## Web server configuration
Add a new **virtualHost** to the web-server to handle the application, and configure
its **DocumentRoot** to point to the RedKite CMS **web folder**.

Please refer [this guide](http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html) 
if you are not comfortable with seting up a web-server.

We will now assume that you have
configured the **redkitecms** virtualhost.

Before starting the application you also need to do some adjustments to your
configuration.

### Advanced configuration
When you work on a web site locally on your computer, you will most likely need to transfer
your site to a remote server when you finish, and again after any further modifications. We will use
**http://example.com**. to show the method for this.

To correctly configure the virtualhost to handle this domain on your local computer,
you need to suffix the server name with the **.local** token, so the host will be
**http://example.com.local**.

RedKite CMS will handle a data folder called **http://example.com**, ignoring the suffix.
When you will deploy your site from your computer to the remote server, you just need
to transfer the folder which matches the remote site name host you are working on.

### Permissions 
RedKite CMS requires that the **app** and **web/upload** folders must be writable by the web server.
At [Symfony website](http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup) 
you will find a comprehensive section explaining this part of the configuration in detail.

### Xdebug configuration
If you use **Xdebug** with your php installation, you also need to configure that module
as follows:

    xdebug.max_nesting_level=1000

otherwise you could get an error, or worse the page rendering could stop without
displaying anything on the screen.

However, if you don't use **Xdebug** you can safety skip this step.

## Start RedKite CMS
Starting work with RedKite CMS is then just a case of opening the host you configured, so point the
browser to **http://redkite/login** host and you should see the login page.

Enter **admin** as **user and password** and start enjoying RedKite CMS!

Found a typo? Found something wrong in this documentation? [Just fork and edit it !](https://github.com/redkite-labs/RedKiteCms/edit/master/docs/book/redkite-cms-configuration.md)
