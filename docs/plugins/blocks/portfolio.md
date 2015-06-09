#Portfolio block
This block handles a terrific portfolio content which renders single works in a row. Each work can be clicked and a nice modal opens to provide extra details for that job. 

- Portfolio
- PortfolioItem

The **PortfolioItem** represents a job and cannot be used singularly.
The **Portfolio** collects **PortfolioItem** blocks.

## Get the block
This block is included into the **redkitecms-plugins** library which is already bundled with RedKite CMS 2 since alpha 3 release. It is installed using composer, so add the library to your **composer.json** as follows:
    
    "require": {
       [...]
       "redkite-labs/redkitecms-plugins": "@dev",
    }
    
To get the library just run:

    php composer.phar update
    
## PortfolioItem Definition
Here it is the PortfolioItem definition:

    children:
      item1:
        value: ''
        tags:
          src: /plugins/agencytheme/img/portfolio/roundicons.png
          class: img-responsive
          title: ''
          alt: ''
        href: ''
        type: Image
      item2:
        value: ''
        tags:
          src: /plugins/agencytheme/img/portfolio/roundicons-free.png
          class: img-responsive
          title: ''
          alt: ''
        href: ''
        type: Image
    tags:
      class: 'portfolio-item col-md-4 col-sm-6'
    type: PortfolioItem
    hover_icon: 'fa fa-plus fa-3x'
    title: Round Icons
    subtitle: Graphic Design
    modal_title: 'Project name'
    modal_description: 'Lorem ipsum dolor sit amet consectetur.'
    modal_body: 'Use this section to describe your project. You can add normal text as html text as well. Please note this text lives into a modal form, so you must click the item to check it'

This block has several properties to describe the job:
    
    hover_icon: 'fa fa-plus fa-3x'
    title: Round Icons
    subtitle: Graphic Design
    modal_title: 'Project name'
    modal_description: 'Lorem ipsum dolor sit amet consectetur.'
    modal_body: 'Use this section to describe your project. You can add normal text as html text as well. Please note this text lives into a modal form, so you must click the item to check it'
    
**hover_icon** represents the icon displayed when the mouse is placed over the block, **title** and **subtitle** handle the text for the block when displayed in the collection and **modal_title**, **modal_description** and **modal_body** handles the texts on the modal dialog.

The item block size can be changed managing the block's class property:
  
    tags:
      class: 'portfolio-item col-md-4 col-sm-6'

and, at last, there are two images the first one handles the image block when displayed in the collection, the second one handles the image for the modal dialog

    item1:
      value: ''
      tags:
        src: /plugins/agencytheme/img/portfolio/roundicons.png
        class: img-responsive
        title: ''
        alt: ''
      href: ''
      type: Image
    item2:
      value: ''
      tags:
        src: /plugins/agencytheme/img/portfolio/roundicons-free.png
        class: img-responsive
        title: ''
        alt: ''
      href: ''
      type: Image
      
## Portfolio Definition

This block is a collection of PortfolioItem blocks, you can edit them as explained above. To add a new item just click the button on the inline editor. 