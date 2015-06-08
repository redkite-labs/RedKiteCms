#Timeline block
This plugin handles a block which renders a nice timeline used to represent the company history. This plugin is made by three blocks:

- TimeLine
- TimeLineItem
- TimeLineEmptyItem

The **TimeLine block** collects both the **TimeLineItem** and the **TimeLineEmptyItem**. 
The **TimeLineItem** renders the single timeline entry.
The **TimeLineEmptyItem** renders an empty timeline item used to nicely close the represented history.

Please note that the only block you can add is the first one, while others are internal, which means they are used by the parent block, TimeLine, but they cannot be added singularly to the page. 

## Get the block
This block is included into the **redkitecms-plugins** library which is already bundled with RedKite CMS 2 since alpha 3 release. It is installed using composer as follows:
    
    "require": {
       [...]
       "redkite-labs/redkitecms-plugins": "@dev",
    } 
 
## TimeLineItem Definition
Here it is the timeline item definition:

    item1:
      children:
        item1:
          value: ''
          tags:
            src: /plugins/agencytheme/img/about/1.jpg
            data-src: ''
            class: 'img-responsive img-circle'
            title: ''
            alt: ''
          href: ''
          type: Image
      tags:
        class: timeline
      type: TimeLineItem
      year: 2009-2011
      subtitle: 'Our Humble Beginnings'
      body: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam'
 
The **children part** defines an image with the **img-responsive img-circle** Bootstrap class attributes to render the image as a circle and can be edited as usual:

    children:
      item1:
        value: ''
        tags:
          src: /plugins/agencytheme/img/about/1.jpg
          data-src: ''
          class: 'img-responsive img-circle'
          title: ''
          alt: ''
        href: ''
        type: Image
 
Then it provides some additional properties:
 
    year: 2009-2011
    subtitle: 'Our Humble Beginnings'
    body: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam'
    
    
## TimeLineEmptyItem Definition
Here it is the empty timeline definition:

    item2:
      value: ''
      tags: {  }
      type: TimeLineEmptyItem
      body: 'Be Part<br>Of Our<br>Story!'
        
Here you just need to change the **body** property.
      
## TimeLine Definition

This block is a collection of TimeLineItem and TimeLineEmptyItem blocks, you can edit them as explained above. To add a new item just click the buttons on the inline editor. 