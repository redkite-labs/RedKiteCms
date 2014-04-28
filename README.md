RedKite CMS
===========
Welcome to RedKite CMS - a fully-functional Symfony2 application powered by
RedKite CMS.

This document contains information on how to download and to start using RedKite CMS.

[![Build Status](https://secure.travis-ci.org/redkite-labs/RedKiteCms.png)](http://travis-ci.org/redkite-labs/RedKiteCms)

Before starting
---------------
This repository houses the RedKite CMS Sandbox and requires to install several mandatory vendor
libraries and to setup the application.

This operation is trivial but, if you are not confident with "composer", you might have some troubles.
In that case, please have a look to the [Get & Go Sandbox](http://redkite-labs.com/download-get-and-go-redkite-cms-sandbox),
ideal to have a quick try the application and perfect to be used by a single developer.

If you want to design a website powered by RedKite CMS or if you would like to contribute to RedKite CMS project,
this is the right place to start.


Install RedKite CMS
-------------------
Follow these steps to install RedKite CMS:

Download composer:

    curl -s http://getcomposer.org/installer | php

then run this command to start a new RedKite CMS application from the stable release:

    php composer.phar create-project redkite-labs/redkite-cms RedKiteCms

If you prefer to get the latest developing release you run this command:

    php composer.phar create-project redkite-labs/redkite-cms -s dev RedKiteCms


RedKite CMS setup
-----------------
RedKite CMS requires several steps to be accomplished to properly setup the CMS itself. Luckily
it comes with an installer which will do all the job for you.

The installer provides a web installer interface or an interactive symfony2 command to install
RedKite CMS.

Permissions
-----------
Before starting you must be sure that following folders and files are writable:

    app/cache
    app/logs
    app/config
    web
    app/AppKernel.php
    app/config/config.yml
    app/config/routing.yml
    app/config/parameters.yml
 
because RedKite CMS installer will update them for you. If you prefer to setup RedKite CMS manually just
follow this guide: http://redkite-labs.com/how-to-install-redkite-cms.

The best way to set up you permissions is using ACL:

    sudo setfacl -R -m u:www-data:rwx -m u:[USERNAME]:rwx *
    sudo setfacl -dR -m u:www-data:rwx -m u:[USERNAME]:rwx *

To get more details, please refer to the [symfony2 setup and configuration tutorial](http://symfony.com/doc/current/book/installation.html#configuration-and-setup)

RedKite CMS console
-------------------
RedKite CMS is highly decoupled from Symfony2 and works using its own kernel to keep things separated as well. For this reason
RedKite CMS comes with its own console, called **rkconsole**, which must be used to run all RedKite CMS commands.

Installing from the console
---------------------------
Installing RedKite CMS from the console is really easy. Run ti command using the Symfony2 console:

    php app/console redkitecms:configure

This will run an interactive command which asks you some mandatory information. If everything goes well,
you will be prompted that the configuration has been written and you are ready to start the install.

Run the following command from the console:
    
    php app/rkconsole redkitecms:install --env=rkcms

When the setup ends, point your browser at

    http://localhost/rkcms.php/backend/login

to start using RedKite CMS.


Installing using the web interface
----------------------------------
To install RedKite CMS using the web interface, you need to install the web assets,
so run the following command from your console:

    php app/console assets:install web

then open a broswer and point it at:

    http://localhost/app_dev.php/install

Provide the mandatry information and you are done!


Using another database intead of mysql, postgres, sqlite
--------------------------------------------------------

Please refer [this document](http://redkitelabs/rkcms_dev.php/backend/en/download-redkite-cms-sandbox#use-another-database-instead-of-mysql-postgres-or-sqlite) to use a database different than mysql, postgres or sqlite.

Sign in
-------
RedKite CMS is secured by default and a new user is created when the application is 
installed:

    username: admin
    password: admin

enter the credentials above to sign in.


Documentation
-------------
Although RedKite CMS is designed to be as simple and intuitive as possible, you probably would like to go deeper, reading
some documentation:

- [The book](http://redkite-labs.com/getting-started-with-redkite-cms)
- [The cookbook](http://redkite-labs.com/redkite-cms-practical-manual-part-1)
- [Developers guide](http://redkite-labs.com/getting-started-contributing-to-redkite-cms)

RedKite CMS [documentation repository](https://github.com/redkite-labs/redkitecms-docs)
lives at github.


Support
-------
If you require support you can ask for help at [RedKite CMS users forum](https://groups.google.com/forum/#!forum/redkitecms-users).

If you want to collaborate, just introduce yourself at [RedKite CMS developers forum](https://groups.google.com/forum/#!forum/redkitecms-dev).


Stay in touch
-------------
RedKite CMS is present on major social networks:

Follow [@redkite-cms on Twitter](https://twitter.com/redkitecms) for the latest news

Like RedKite CMS at [Facebook](https://www.facebook.com/redkitecms)

Connect with RedKite CMS at [Google+](https://plus.google.com/103994964006724386514)


Notes for windows users
-----------------------
RedKite CMS has been written on a linux system machine, so you might encounter some small issues when
you work on a windows machine:

- assetic:dump command might return an error
- Skin problems

None of those problems breaks the usability of RedKite CMS. If you are a windows user and you want
to fix them on your own, fork the repository, do the fixes then ask for a pull request: it would be really
appreciated! :)

Enjoy!
