Migrate from RedKite CMS 1.1.3 to 1.1.4
=======================================
This document describes in detail how to migrate a from RedKite CMS 1.1.3 to RedKite CMS
1.1.4.

Migration
---------
In the new release all RedKite labs blocks and themes had been moved under the RedKiteLabs
namespace to simplify the application update for next releases.

Remove the src/RedKiteLabs folder
Remove the src/RedKiteCms/Block/BootbusinessBlockBundle folder
Remove the src/RedKiteCms/Block/RedKiteCmsBaseBlocksBundle folder
Remove the src/RedKiteCms/Block/TinyMceBlockBundle folder
Remove the src/RedKiteCms/Block/TwitterBootstrapBundle folder
Remove the src/RedKiteCms/Theme/BootbusinessThemeBundle folder
Remove the src/RedKiteCms/Theme/ModernBusinessThemeBundle folder

Download the http://redkite-labs.com/download/cms/redkite-cms-1-1-4-upgrade.zip and unzip it into 
the src folder. This will recreate the entire RedKiteLabs namespace.

If you are using the ModernBusinessThemeBundle you need to update the bundle namespace, so open
your app/AppKernel.php file and change its declaration as follows:


    new RedKiteLabs\ModernBusinessThemeBundle\ModernBusinessThemeBundle();

Now you must change the search folders for the RedKiteLabs BootstrapBundle in the  app/RedKiteCmsAppKernel.php,
so open that file and replace the following code:


    $searchFolders = $this->searchFolders = array(
        __DIR__ . '/../src/RedKiteCms/Block',
        __DIR__ . '/../src/RedKiteCms/Theme',
        __DIR__ . '/../src/RedKiteLabs/RedKiteCms',
    );

with this one:

    $searchFolders = $this->searchFolders = array(
        __DIR__ . '/../src/RedKiteCms/Block',
        __DIR__ . '/../src/RedKiteCms/Theme',
        __DIR__ . '/../src/RedKiteLabs',
        __DIR__ . '/../src/RedKiteLabs/RedKiteCms',
    );

Open you composer.json file and add the following dependency:

    "knplabs/knp-markdown-bundle": "1.2.*@dev"

At last, clean up everything running these commands:

    php app/rkconsole assets:install web --symlink --env=rkcms
    php app/rkconsole assetic:dump --env=rkcms
    rm -rf app/cache
    rm -rf app/config/bundles

Note for windows users latest two commands removes the app/cache and app/config/bundles folders.
