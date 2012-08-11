AlphaLemon CMS
==============

Welcome to the AlphaLemon CMS a Content Management System Application, built on top of
Symfony2 Framework, providing an easy an intuitive user-interface, to improve the
website's content management experience.


[![Build Status](https://secure.travis-ci.org/alphalemon/AlphaLemonCmsBundle.png)](http://travis-ci.org/alphalemon/AlphaLemonCmsBundle)


Requirements
------------

- PHP 5.3+
- Symfony 2.1


Install AlphaLemon CMS
----------------------

To install AlphaLemon CMS you just need to download the [AlphaLemon CMS Sandbox](https://github.com/alphalemon/AlphaLemonCmsSandbox)
and follow the intructions that comes with the sandbox itself.

The AlphaLemon CMS Sandbox is just a Symfony2 Application fully configured for AlphaLemon CMS, which contains the tools required by AlphaLemon CMS, like the
yuicompressor or tiny_mce.


Use AlphaLemon CMS
------------------

To browse your site, simply open a browser and point to **http://localhost** or whatever your domain is.

To work with AlphaLemon CMS simply point to **http://localhost/alcms.php/backend/en/index**

You may debug your application using the alcms_dev.php environment: **http://localhost/alcms_dev.php/backend/en/index**


The page is blank
-----------------
If you encounter a blank web page, something went wrong. To understand what's appened, you could open
the same page in the _dev environment or open the alcms.php and change the following row from:

    $kernel = new AppKernel('alcms', false);

to

    $kernel = new AppKernel('alcms', true);

Development status
------------------

AlphaLemon CMS has been eavily refactored since preview releases and it is not stable yet. A pre-alpha version is
available now, but it is buggy and incomplete, so don't expect so much from it.

You may partecipate to the development browsing the official AlphaLemon's space on github [https://github.com/alphalemon](https://github.com/alphalemon).


Documentation
-------------
For more information and documentation see the [documentation](https://github.com/alphalemon/AlphaLemonCmsBundle/tree/master/Resources/docs)
that comes with AlphaLemon CMS.


Notes for windows users
-----------------------
AlphaLemon CMS has been written on a linux system machine, so you may encounter some small issues when
you work on a windows machine:

- assetic:dump command might return an error
- The upload themes button stylesheets is not visible
- After a theme upload, the response could be false though the theme has been loaded correctly
- Skin problems

None of those problems breaks the usability of AlphaLemon CMS. If you are a windows user and you want
to fix them on your own, fork the repository, do the fixes then ask for a pull request: it will be really
appreciated! :)

Enjoy!