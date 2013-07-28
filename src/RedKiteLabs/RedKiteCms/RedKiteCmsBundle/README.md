AlphaLemon CMS
==============
Welcome to the AlphaLemon CMS a Content Management System Application, built on top of
Symfony2 and Twitter Bootstrap frameworks.

AlphaLemon CMS is designed to provide an easy an intuitive user-interface, to improve the
website's content management experience.


[![Build Status](https://secure.travis-ci.org/alphalemon/AlphaLemonCmsBundle.png)](http://travis-ci.org/alphalemon/AlphaLemonCmsBundle)


Requirements
------------
- PHP 5.3+
- Symfony 2.3.*+
- Propel 1.6+


Install AlphaLemon CMS
----------------------
To install AlphaLemon CMS you just need to download the [AlphaLemon CMS Sandbox](https://github.com/alphalemon/AlphaLemonCmsSandbox)
and follow the intructions that comes with the sandbox itself.

If you want to install AlphaLemon CSM for an existing application or from the scratch,
just read the [AlphaLemon CMS install chapter](https://alphalemon.com/how-to-install-alphalemon-cms)


Start AlphaLemon CMS
--------------------
To browse your site, simply open a browser and point to **http://localhost** or whatever your domain is.

To work with AlphaLemon CMS simply point to **http://localhost/alcms.php/backend/login**

You may debug your application using the alcms_dev.php environment: **http://localhost/alcms_dev.php/backend/login**


The page is blank
-----------------
If you encounter a blank web page, something went wrong. To understand what's appened, you could open
the same page in the _dev environment or open the alcms.php and change the following row from:

    $kernel = new AppKernel('alcms', false);

to

    $kernel = new AppKernel('alcms', true);


Documentation
-------------
You can read AlphaLemon CMS official documentation at alphalemon.com:

- [The book](http://alphalemon.com/the-official-alphalemon-cms-documentation)
- [The cookbook](http://alphalemon.com/alphalemon-cms-cookbook)
- [Developers guide](http://alphalemon.com/getting-started-contributing-to-alphalemon-cms)

AlphaLemon CMS [documentation repository](https://github.com/alphalemon/alphalemon-docs)
lives at github.


Support
-------
If you require support you can ask for help at [AlphaLemon CSM users forum](https://groups.google.com/forum/?hl=it#!forum/alphalemoncms-users).

If you want to collaborate, just introduce yourself at [AlphaLemon CSM developers forum](https://groups.google.com/forum/?hl=it#!forum/alphalemoncms-dev).


Stay in touch
-------------
AlphaLemon CMS is present on major social networks:

Follow [@alphalemoncms on Twitter](https://twitter.com/alphalemoncms) for the latest news

Like AlphaLemon CMS at [Facebook](https://www.facebook.com/alphalemon)

Connect with AlphaLemon CMS at [Google+](https://plus.google.com/103994964006724386514/posts)


Notes for windows users
-----------------------
AlphaLemon CMS has been written on a linux system machine, so you might encounter some small issues when
you work on a windows machine:

- assetic:dump command might return an error
- Skin problems

None of those problems breaks the usability of AlphaLemon CMS. If you are a windows user and you want
to fix them on your own, fork the repository, do the fixes then ask for a pull request: it would be really
appreciated! :)

Enjoy!