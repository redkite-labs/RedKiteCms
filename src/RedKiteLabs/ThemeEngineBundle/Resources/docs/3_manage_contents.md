## Managing repeated contents 
This chapter covers how ThemeEngine manages the contents.

## Template base contents 
Every template is defined by an object where are declared the slots it uses. Each slot must define the text added by default into the slot itself and how 
the content is repeated through the site's pages as detailed explained into the "create_a_custom_theme tutorial" of this documentation.

A ready-to-use theme declares all the contents exactly as the template author has added to the template, so when you add a new theme, it is displayed 
exactly as projected. Obviously you will add you own contents to the page and, at this point, you must think about on how a content is repeated through
the pages.

For example the site's logo usually is repeated for all the pages, while the navigation menu is repeated for the same language. The chapter "render_a_page"
has been explained that each page has its own action where contents must be added to the PageTree object which passes them to the template for rendering. 
The contents that must be repeated should be reported on each page and this is really a redundancy of code.

ThemeEngineBundle uses an external yml file called slotContents.custom.yml to save this particula kind of contents.


## Configuring the slotContents.custom.yml
The slotContents.custom.yml fileis not active by default because it must be placed inside the application folder that manages the website to avoid undesidered
overridens when the ThemeEngineBundle is updated. To enable this file you must declare the folder where it is saved into your config.yml as follows:

alpha_lemon_theme_engine:
    slot_contents_dir: src/[Company]/[Bundle]/Resources/slotContents

## The slotContents.custom.yml
A tipycal slotContents.custom.yml file is made as follows:

    slots:
      search_box:
        0: |
          Search <input type="text" value="" size="10" /> <input type="submit" />

      logo:
        0: |
          The company logo

      nav_menu:
        0: |
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contacts</a></li>
          </ul>
      
      ...

It must start with the "slots:" tag and under it you start to add the slots to override.

Pay attention when you change a theme and the slotContents.custom.yml is defined that you must adapt it to the new design!