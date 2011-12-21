# The ThemeEngine web interface
ThemeEngine has a really simple web interface to manage the themes. It is made by two columns, where the left column contains the active
theme used by the website, while the right one contains all the available themes you may activate. In the bottom of this column it is placed
the button you can use to upload a new theme.

## Themes folder
Themes are store into a folder called Themes, that lives by default inside the bundle's root folder. You may change this folder, configuring 
the base_dir parameter in the config.yml file. If you do so, don't forget to change the Themes namespace as well. 

The available themes are retrieved by ThemeEngine by reading the *Bundle folders from that directory. To add a new theme you can start a new 
bundle into the themes folder or upload a valid archive into it, using the Upload Button or manually copying the archive inside it. It is not
important how you add a new theme, but when you manually add a new theme, you must import it by clicking the Import button placed under the theme
itself, while this operation is automatically made when you upload the theme with the Upload button.

## Activate a theme
When a theme is imported, it can be activated, which means that it becomes the theme used by the website, simply by clicking the Activate button
placed under the theme itself. This operation puts the activated theme on the left column and the previous active theme returns between the available
themes.

## Remove a theme
To remove a theme you just need to click the remove button placed under the theme itself
