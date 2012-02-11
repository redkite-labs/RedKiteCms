AlphaLemon CMS
==============

Welcome to the AlphaLemon CMS a Content Management System Application, built on top of 
Symfony2 Framework, providing an easy an intuitive user-interface, to improve the 
website's content management experience.

This document contains information on how to download and start using AlphaLemon CMS and assumes that you will use the 
[AlphaLemon CMS Sandbox Without Vendors application](http://alphalemon.com/download-alphalemon-cms-for-symfony2-framework).

To learn more and get more detailed explanation or to learn how to install AlphaLemon CMS from the scratch with an empty Symfony2 project or 
how to upgrade an existing project, see the [Installation chapter](http://alphalemon.com/how-to-install-alphalemon-cms).

[![Build Status](https://secure.travis-ci.org/alphalemon/AlphaLemonCmsBundle.png)](http://travis-ci.org/alphalemon/AlphaLemonCmsBundle)


Vendor libraries installation
-----------------------------

Once you've downloaded and uncompressed the AlphaLemon CMS Sandbox, just open a console, 
move to the root folder of the sandbox and give the following command to install vendor 
libraries:

    php ./bin/vendors install

Now you must grab ElFinder vendor library, which is a submodule of ElFinderBundle. Give
this commands:

    cd vendor/bundles/AlphaLemon/ElFinderBundle/
    git submodule init
    git submodule update

Wait that until the library is downloaded then return to the top directory. 

AlphaLemon CMS setup
--------------------

Before starting the setup operation, you must configure the parameters required by the application.

The installation script comes with a config.php, where are saved the default configurations required 
to install the CMS, which are:

- A bundle where AlphaLemon CMS will save your contents
- The database parameters, like the host, the database name, the user and password

When you start a new Symfony2 project you always create a new bundle where your application lives. 
The required external bundle, by AlphaLemon CMS, is exactly that bundle.

Open the bin/config.php file and change the parameters to work with your environment then give the following 
command to setup AlphaLemon CMS: 

    php ./bin/cmsInstall

Use AlphaLemon CMS
------------------

To browse your site, simply open a browser and point to http://localhost or whatever your domain is.

To work with AlphaLemon CMS simply point to http://localhost/alcms.php/en/index. You may debug your
application using the alcms_dev.php environment: http://localhost/alcms_dev.php/en/index


The page is blank
-----------------
If you encounter a blank web page, something went wrong. To understand what's appened, you could open
the same page in the _dev environment or open the alcms.php and change the following row from:

    $kernel = new AppKernel('alcms', false);

to

    $kernel = new AppKernel('alcms', true);


Development status
------------------

AlphaLemon CMS is quite stable but it is still under development. The version out is just a Preview 
Release, so many improvements and features must be implemented: see TODO section just below.

You may partecipate to the development browsing the official AlphaLemon's space on github:

    https://github.com/alphalemon
 

TODO
----

- Secure the alcms environments (a help here will be really appreciated!!)
- Implement a bundle to render the languages navigation menu
- Make page publishing checkbox to work (actually all pages are published)
- Implement an object to check the compatibility between themes when a theme is changed 
- Fix the skin for windows
- Dictionaries for CMS translation
- Refactor and complete the unit tests


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