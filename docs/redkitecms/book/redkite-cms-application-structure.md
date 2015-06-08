# RedKite CMS application structure
The RedKite CMS application follows the structure of a Symfony2 bundle and but also comes with several additional folders you should know about if you want to understand how to configure it to get maximum benefits:

## The app folder
The folder **app** contains the data related to the application and contains the following folders:

**cache** The cache folder
**config** An empty folder where you can add your custom configuration files to override the standard configuration for all the sites handled by the application
**data** The handled sites data is stored here
**logs** Application logs. Each site has its own log
**plugins** The folder reserved for custom plugins. It comes with the **Block** folder where you can add your custom blocks and with the **Theme** folder where you can add your custom themes.

## The docs folder
The directory where all the RedKite CMS documentation can be found.

### The plugins folder
The plugin folder contains the bundled blocks and themes that come with RedKite CMS itself. These plugins are saved respectively under the **Block** and **Theme** folders. In addition there is the **Core** folder where the **RedKiteCms plugin** is saved. This handles the application frontend and the **AceEditor** plugin handles the blocks editor based on the **Ace9** web editor.

## The src folder
This folder is designed to add your custom application logic to handle your websites. For example, if you need to implement a listener to replace a content at runtime, it must be saved here.

## The vendor folder
Contains the required vendor components required by RedKite CMS application

## The web folder
Contains the application public files. Here you will find the front controller that load RedKite CMS, the application assets, your assets, the frontend frameworks

Found a typo? Found something wrong in this documentation? [Just fork and edit it !](https://github.com/redkite-labs/RedKiteCms/edit/master/docs/book/redkite-cms-configuration.md)
