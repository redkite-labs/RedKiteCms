Upgrade your Symfony 2.1 application powered by AlphaLemon CMS
==============================================================

This document explains in detail how to migrate your AlphaLemon CMS Sandbox to Symfony 2.3.

Composer.json
-------------

Open your composer.json and change the old Symfony2 requirements as follows:

    "require": {  
        [...]
        "symfony/symfony": "2.3.*",
        "doctrine/orm": ">=2.2.3,<2.4-dev",
        "doctrine/doctrine-bundle": "1.2.*",
        "twig/extensions": "1.0.*",
        "symfony/assetic-bundle": "2.3.*",
        "symfony/swiftmailer-bundle": "2.3.*",
        "symfony/monolog-bundle": "2.3.*",
        "sensio/distribution-bundle": "2.3.*",
        "sensio/framework-extra-bundle": "2.3.*",
        "sensio/generator-bundle": "2.3.*",
        "incenteev/composer-parameter-handler": "~2.0",  
        [...]
    },

Update your vendors

    php composer.phar update


AppKernel.php
-------------

Open your app/AppKernel.php and change the old Symfony2 bundles declaration as follows:

    $bundles = array(
        new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
        new Symfony\Bundle\SecurityBundle\SecurityBundle(),
        new Symfony\Bundle\TwigBundle\TwigBundle(),
        new Symfony\Bundle\MonologBundle\MonologBundle(),
        new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
        new Symfony\Bundle\AsseticBundle\AsseticBundle(),
        new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
        new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

        [...]
    );


Configuration files
-------------------

Open the app/security.yml file and remove jms_security_extra configuration

Open the app/config.yml file and change these framework configurations

    framework:
        [...]
        templating:      { engines: ['twig'] } #assets_version: SomeVersionScheme
        default_locale:  %locale%
        trust_proxy_headers: false # Should Request object should trust proxy headers (X_FORWARDED_FOR/HTTP_CLIENT_IP)
        session:         ~

as follows:

    framework:
        [...]

        templating:
            engines: ['twig']
            #assets_version: SomeVersionScheme
        default_locale:  "%locale%"
        trusted_proxies: ~
        session:         ~
        fragments:       ~
        http_method_override: true


Symfony 2.3 upgrade
-------------------

Download the latest Symfony 2.3 standard version without vendors from http://symfony.com
and follow the UPGRADE-2.2.md and UPGRADE-2.3.md guides, minding that some changes
they require have already been made.

Repeat the steps for web/app_dev.php for web/alcms_dev.php and web/stage_dev.php.

Don't change the minimum-stability flag, because AlphaLemon CMS is not stable yet.

bootstrap.php.cache
-------------------

Copy the app/bootstrap.php.cache file from the Symfony 2.3 package to your application
app folder
