RedKiteCms
==============
Welcome to the RedKiteCms a Content Management System Application, built on top of
Symfony2 and Twitter Bootstrap frameworks.

RedKiteCms is designed to provide an easy an intuitive user-interface, to improve the
website's content management experience.


[![Build Status](https://secure.travis-ci.org/redkite-labs/RedKiteCmsBundle.png)](http://travis-ci.org/redkite-labs/RedKiteCmsBundle)


Requirements
------------
- PHP 5.3+
- Symfony 2.3.*+
- Propel 1.6+


Install RedKiteCms
----------------------
To install RedKiteCms you just need to download the [RedKiteCms Sandbox](https://github.com/alphalemon/AlphaLemonCmsSandbox)
and follow the intructions that comes with the sandbox itself.

If you want to install RedKiteCms for an existing application or from the scratch,
just read the [RedKiteCms install chapter](https://alphalemon.com/how-to-install-alphalemon-cms)


Start RedKiteCms
--------------------
To browse your site, simply open a browser and point to **http://localhost** or whatever your domain is.

To work with RedKiteCms simply point to **http://localhost/rkcms.php/backend/login**

You may debug your application using the rkcms_dev.php environment: **http://localhost/rkcms_dev.php/backend/login**


The page is blank
-----------------
If you encounter a blank web page, something went wrong. To understand what's appened, you could open
the same page in the _dev environment or open the rkcms.php and change the following row from:

    $kernel = new AppKernel('rkcms', false);

to

    $kernel = new AppKernel('rkcms', true);


Documentation
-------------
You can read RedKiteCms official documentation at alphalemon.com:

- [The book](http://alphalemon.com/the-official-alphalemon-cms-documentation)
- [The cookbook](http://alphalemon.com/alphalemon-cms-cookbook)
- [Developers guide](http://alphalemon.com/getting-started-contributing-to-alphalemon-cms)

RedKiteCms [documentation repository](https://github.com/alphalemon/alphalemon-docs)
lives at github.


Support
-------
If you require support you can ask for help at [RedKiteCms users forum](https://groups.google.com/forum/?hl=it#!forum/alphalemoncms-users).

If you want to collaborate, just introduce yourself at [RedKiteCms developers forum](https://groups.google.com/forum/?hl=it#!forum/alphalemoncms-dev).


Stay in touch
-------------
RedKiteCms is present on major social networks:

Follow [@alphalemoncms on Twitter](https://twitter.com/alphalemoncms) for the latest news

Like RedKiteCms at [Facebook](https://www.facebook.com/alphalemon)

Connect with RedKiteCms at [Google+](https://plus.google.com/103994964006724386514/posts)


Notes for windows users
-----------------------
RedKiteCms has been written on a linux system machine, so you might encounter some small issues when
you work on a windows machine:

- assetic:dump command might return an error
- Skin problems

None of those problems breaks the usability of RedKiteCms. If you are a windows user and you want
to fix them on your own, fork the repository, do the fixes then ask for a pull request: it would be really
appreciated! :)

Enjoy!