RedKiteCms
==============
Welcome to the RedKiteCms a Content Management System Application, built on top of
Symfony2 and Twitter Bootstrap frameworks.

RedKiteCms is designed to provide an easy an intuitive user-interface, to improve the
website's content management experience.


[![Build Status](https://secure.travis-ci.org/redkite-labs/RedKiteCmsBundle.png)](http://travis-ci.org/redkite-labs/RedKiteCmsBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/redkite-labs/RedKiteCmsBundle/badges/quality-score.png?s=c7e103682f556b0ece668946ab66d4f023f38f74)](https://scrutinizer-ci.com/g/redkite-labs/RedKiteCmsBundle/)

Requirements
------------
- PHP 5.3+
- Symfony 2.3.*+
- Propel 1.6+


Install RedKiteCms
----------------------
To install RedKiteCms you just need to download the [RedKiteCms Sandbox](https://github.com/redkite-labs/RedKiteCmsSandbox)
and follow the intructions that comes with the sandbox itself.

If you want to install RedKiteCms for an existing application or from the scratch,
just read the [RedKiteCms install chapter](http://redkite-labs.com/how-to-install-redkite-cms)


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
You can read RedKiteCms official documentation at redkite-labs.com:

- [The book](http://redkite-labs.com/the-official-redkite-cms-documentation)
- [The cookbook](http://redkite-labs.com/redkite-cms-cookbook)
- [Developers guide](http://redkite-labs.com/getting-started-contributing-to-redkite-cms)

RedKiteCms [documentation repository](https://github.com/redkite-labs/redkitecms-docs)
lives at github.


Support
-------
If you require support you can ask for help at [RedKiteCms users forum](https://groups.google.com/forum/?hl=it#!forum/redkitecms-users).

If you want to collaborate, just introduce yourself at [RedKiteCms developers forum](https://groups.google.com/forum/?hl=it#!forum/redkitecms-dev).


Stay in touch
-------------
RedKiteCms is present on major social networks:

Follow [@redkitecms on Twitter](https://twitter.com/redkitecms) for the latest news

Like RedKiteCms at [Facebook](https://www.facebook.com/redkitecms)

Connect with RedKiteCms at [Google+](https://plus.google.com/103994964006724386514)


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