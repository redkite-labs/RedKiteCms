#Team block
This block handles a content which renders an awesome representation of a working team. This plugin is made by two blocks:

- MemberTeam
- MemberTeamCollection

The **MemberTeam** represents the single member of the team.
The **MemberTeamCollection** collects **MemberTeam** blocks. 

## Get the block
This block is included into the **redkitecms-plugins** library which is already bundled with RedKite CMS 2 since alpha 3 release. It is installed using composer, so add the library to your **composer.json** as follows:
    
    "require": {
       [...]
       "redkite-labs/redkitecms-plugins": "@dev",
    }
    
To get the library just run:

    php composer.phar update

## MemberTeam Definition
Here it is the timeline definition:

    children:
      item1:
        value: ''
        tags:
          src: /plugins/memberteam/images/team.jpg
          class: 'img-responsive img-circle'
          title: ''
          alt: ''
        href: ''
        type: Image
      item2:
        children:
          item1:
            value: ''
            tags:
              class: 'fa fa-twitter'
            type: Link
          item2:
            value: ''
            tags:
              class: 'fa fa-facebook'
            type: Link
          item3:
            value: ''
            tags:
              class: 'fa fa-linkedin'
            type: Link
        tags:
          class: 'list-inline social-buttons'
        type: Menu
    tags:
      class: col-sm-4
    type: MemberTeam
    member_name: 'Jane Doe'
    member_role: 'Lead Designer'

This block has two properties to define the member:
    
    member_name: 'Jane Doe'
    member_role: 'Lead Designer'
    
and then collects an image to show the member face, with the **img-responsive img-circle** Bootstrap class attributes to render the image as a circle:

    item1:
      value: ''
      tags:
        src: /plugins/memberteam/images/team.jpg
        class: 'img-responsive img-circle'
        title: ''
        alt: ''
      href: ''
      type: Image
 
and a menu to provide the member social contact:

    item2:
      children:
        item1:
          value: ''
          tags:
            class: 'fa fa-twitter'
          type: Link
        item2:
          value: ''
          tags:
            class: 'fa fa-facebook'
          type: Link
        item3:
          value: ''
          tags:
            class: 'fa fa-linkedin'
          type: Link
      tags:
        class: 'list-inline social-buttons'
      type: Menu
      
## MemberTeamCollection Definition

This block is a collection of MemberTeam blocks, you can edit them as explained above. To add a new item just click the button on the inline editor. 