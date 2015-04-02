# RedKite CMS application structure
RedKite CMS application is structured as follows:

## The app folder
The folder **app** contains the data related to the application and contains the following folders:

**cache** Cache folder
**config** Place here you custom configuration files to override the standard configuration for the whole sites handled by the application
**data** Here are saved the handled sites data
**logs** Application logs per site
**plugins** Plache here your custom plugins

## The docs folder
Here you will find the RedKite CMS documentation

## The lib folder
This folder contains RedKite CMS library and contains the following folders:

**config** Contains the standard RedKite CMS configuration files
**controllers** Contains the controller implementation for Silex microframework
**frameword** Contains the RedKite CMS framework
**plugins** Contains the plugins distributed with RedKite CMS


## The src folder
This folder is deputed to add your custom application logic to handle your websites. For example, if you need to implement a listener to replace a content at runtime, you must put it here.

## The vendor folder
Contains the required vendor components required by RedKite CMS application

## The web folder
Contains the application public files. Here there live the front controller that load RedKite CMS, the application assets, your assets, the frontend frameworks