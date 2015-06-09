#Services block
This block handles a content that describes the services your company offer. It provides two blocks:

- AgencyService
- AgencyServicesCollection

The AgencyService is the single service
The AgencyServicesCollection is a collection of AgencyService blocks

## Get the block
This block is included into the **redkitecms-plugins** library which is already bundled with RedKite CMS 2 since alpha 3 release. It is installed using composer, so add the library to your **composer.json** as follows:
    
    "require": {
       [...]
       "redkite-labs/redkitecms-plugins": "@dev",
    }
    
To get the library just run:

    php composer.phar update
    
## AgencyService Definition
Here it is the service definition:

    children:
      item1:
        children:
          item1:
            value: ''
            tags:
              class: 'fa fa-circle fa-stack-2x text-primary'
            type: Icon
          item2:
            value: ''
            tags:
              class: 'fa fa-shopping-cart fa-stack-1x fa-inverse'
            type: Icon
        tags:
          class: 'fa-stack fa-4x'
        type: IconStacked
        value: ''
    tags:
      class: col-md-4
    type: AgencyService
    service_title: 'Service title'
    service_description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.'
 
The **children part** defines a [fontawesome stacked icon](http://fortawesome.github.io/Font-Awesome/examples/) you can easily customize changing the **item2** entry. For example, to display a bomb icon you would change that entry as follows:

    item2:
        value: ''
        tags:
          class: 'fa fa-bomb fa-stack-1x fa-inverse'
        type: Icon
 
To change the icon dimension your change the item class:

    tags:
        class: 'fa-stack fa-2x'
    
To change the block dimension just change the block class:

    children:
        [..]
        tags:
          class: col-md-6
          
Then, to change the title and the displayed text, just edit the **service_title** and **service_description** properties:

    service_title: 'E-Commerce'
    service_description: 'It is easy to sell your products from your website...'
    
## AgencyServicesCollection Definition

As explained at the beginning, This block collections AgencyService blocks, so you can edit them as explained above. To add a new item just click the button on the inline editor. 