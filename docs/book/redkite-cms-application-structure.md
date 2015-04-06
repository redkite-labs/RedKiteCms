# RedKite CMS application structure
RedKite CMS application comes with several folders you should know, to understand how to configure it to get the best benefits to implement your website based on this cms.

## The app folder
The folder **app** contains the data related to the application and contains the following folders:

**cache** The cache folder
**config** An empty folder where you can add your custom configuration files to override the standard configuration for the whole sites handled by the application
**data** Here are saved the handled sites data
**logs** Application logs. Each site has its own log
**plugins** The folder reserved for custom plugins. It comes with the **Block** folder where you can add your custom blocks and with the **Theme** folder where you can add your custom themes.

## The docs folder
The directory where lives the RedKite CMS documentation.

## The lib folder
This folder contains the RedKite CMS library and it is structured as follows:

**config** Contains the standard RedKite CMS configuration files
**controllers** Contains the controller implemented for the Silex microframework
**frameword** Contains the RedKite CMS framework
**plugins** Contains the plugins distributed with RedKite CMS

### The plugins folder
The plugin folder contains the bundled blocks and themes that come with RedKite CMS itself. Those plugins are saved respectively under the **Block** and **Theme** folders. In addiction there is the **Core** folder where are saved the **RedKiteCms plugin** deputed to handle the application frontend and the **AceEditor** plugin deputed to handle the blocks editor based on the **Ace9** web editor.

## The src folder
This folder is deputed to add your custom application logic to handle your websites. For example, if you need to implement a listener to replace a content at runtime, you must put it here.

## The vendor folder
Contains the required vendor components required by RedKite CMS application

## The web folder
Contains the application public files. Here there live the front controller that load RedKite CMS, the application assets, your assets, the frontend frameworks

Found a typo ? Something is wrong in this documentation ? [Just fork and edit it !](https://github.com/redkite-labs/RedKiteCms/edit/master/docs/book/redkite-cms-application-structure.md)