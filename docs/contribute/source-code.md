# RedKite CMS Source Code
**RedKite CMS is hosted at GitHub** and uses **Git** for source control. In order to obtain the source code, you must first install Git on your system.

Instructions for installing and setting up Git can be found at [http://help.github.com/set-up-git-redirect](http://help.github.com/set-up-git-redirect).

## Obtain the code

RedKite CMS project is made by the RedKiteCms main application and the RedKite CMS framework library.

The best way to contribute to RedKite CMS project is to fork both of those repositories: login to Github with your user, open the RedKite Cms repository at [https://github.com/redkite-labs/RedKiteCms](https://github.com/redkite-labs/RedKiteCms) and click the **fork** button. 

When it is done, repeat the same steps with [RedKite CMS framework repository](https://github.com/redkite-labs/redkitecms-framework). 

You can find instructions for forking a repository at [http://help.github.com/fork-a-repo]( http://help.github.com/fork-a-repo).

Now clone your forked RedKite CMS repository:
 
    git clone git@github.com:[GITHUBUSERNAME]/RedKiteCms.git
    
when it is done, open the RedKiteCms's composer.json file and add your repository to **repositories** as follows:
 
     [...]
     "repositories": [
         {
             "type": "vcs",
             "url": "git@github.com:[GITHUBUSERNAME]/redkitecms-framework.git"
         }
     ],
     "require": {
        "php": ">=5.3.3",
        [...]
     
    
then you need to install vendor folders:
 
    composer install

Verify that the folder **vendor/redkite-labs/redkitecms-framework** contains your forked repository:
 
    cd vendor/redkite-labs/redkitecms-framework
    git remote -v
    
and you should receive a response like this one:

    composer	git://github.com/[GITHUBUSERNAME]/redkitecms-framework.git (fetch)
    composer	git://github.com/[GITHUBUSERNAME]/redkitecms-framework.git (push)
    origin	git://github.com/[GITHUBUSERNAME]/redkitecms-framework.git (fetch)
    origin	git@github.com:[GITHUBUSERNAME]/redkitecms-framework.git (push)

## Code standards
RedKite CMS project is written in php code and follow the [Symfony2 code standards](http://symfony.com/doc/current/contributing/code/standards.html).
    
## The RedKite CMS framework structure
The main application structure is explained in detail [here](redkite-cms-application-structure), so, before continuing, please make sure you read that document.

**config** Contains the standard RedKite CMS configuration files
**controllers** Contains the controller implemented for the Silex microframework
**framework** Contains the RedKite CMS framework
**plugins** Contains the plugins distributed with RedKite CMS

Now you are ready to contribute to RedKite CMS project. When you have finished your changes, just send us a [pull request](https://help.github.com/articles/using-pull-requests/) to let us check your code and hopefully merge in into the RedKite CMS repository.

Found a typo? Found something wrong in this documentation? [Just fork and edit it !](https://github.com/redkite-labs/RedKiteCms/edit/master/docs/contribute/source-code.md)