# AlphaLemon CMS User Manual (Part 1)
AlphaLemon CMS is a Content Management System application, built on top of Symfony2 Framework, really easy to use.

In this chapter you will learn how the CMS is configured, which are the predefined content types provided with the CMS and how to manage them.

# Environments
AlphaLemon CMS introduces some new environments in addition to the ones that comes with Symfony2, and they are:

- alcms
- alstage

Both have their respective _devs environments, so AlphaLemon comes with four addictional environments.

The Symfony2's **app** environment is the one where your application frontend runs, the **alcms** one could be considered as the backend where the website can be edited, while the **stage** is the one where the website can be tested before go to production. This last one is under development at the moment, and it is not ready anymore.

## The app environment
The app environment doesn't require to spend many words: it is the place where your application runs, so it can be considered as the frontend of your application. It works as proposed by Symfony2 so you can work with it as you usually do, with a standard Symfony2 application.

## The alcms environment
The alcms environment is the backend of your website. Here, you can edit the contents of your website, add new pages, languages and themes, in other words, it is the place where you can manage and control your website. This environment is totally decoupled from the frontend so every change you made will not affect the website until you deploy it.

## The stage environment
The stage environment is the place where you can check and test your website before the final deployment. It is placed between the backend and the frontend environments.

# Authentication
The backend and the stage environments are protected zones, so only authenticated users can enter. AlphaLemon wants to be as simple as possible, so it
doesn't implement natively complex user's credentials and roles, but it requires a simply authentication. This job will be implemented in a separate bundle, so only people who really need to deal with that kind of scenario will use it.

# Editing the website
When a user has been authenticated himself, he can start to edit the website. The editor is very simple and lives at the top of the webpage, as
a toolbar menu. Here are placed all the commands you need to manage the whole website. Under it, the website is rendered exactly as it will be deployed.

# Moving through the pages
Moving through the website's pages is simply as clicking the link you want to reach, in fact, the site is explorable exactly as in the production environment. So if you have a link called **features**, you just click it and enter in the feature's page. However it could happen that a page is not linked on the website's menu, so you can reach it by clicking the page you want in the navigation menu at the top-right corner of the toolbar menu.

# Add, edit and remove contents
All those operations are made directly on the web page and every change you made is displayed in real time on the page. When you are inside the page you want to edit, just click the **Edit** button on the toolbar, and the editable contents are immediaty surrounded by a red dotted border rectangle. Contents editing is made interacting with those squares, let's see how.

## Add a content
To add a new content you must right click on an existing content or if any content exists on a slot, a message takes place into the empty slot that lets you interact with it. This opens the contextual menu that lets you manage the content itself. Now you must choose the content to add, by clicking the content type from the Add content submenu.

AlphaLemon CMS, in its basic configuration, comes with five standard content types which are the base content required to build a website, but more can be added to improve your own environment. The standard contents you can choose from, are:

1. **Text** - A standard text content which can be attached by several formatted styles.
2. **Media** - A media content, so an image, a flash movie and so on.
3. **Menu** - A list of links on one row or column
4. **Javascript** - A javascript content. It could be an external tool, with its own stylesheets and javascripts
5. **Languages menu** - A menu which has the languages of your website.

A content is always added under the one you clicked.

## Edit a content
To start edit a content you could click the **Edit content entry** on the contextual menu, or simply click inside the rectangle, to open the right editor. Each content has a dedicated editor to manage it. Where it was possible, the same editor has been reused, to implement the less numbers of interfaces and simplify the learning curve. For example, the Text and the Menu contents have the same editor. Each content editor will be explained separately next above.

## Remove a content
To remove a content, just click the **Remove content** entry from the contextual menu.

# Standard Content Types
As you have learned in the previous paragraph, AlphaLemon has five base content types which are enough to build the whole website. Let's see in detail.

## Text
This is the standard textual content, you can use to enter the text and format it, to create links, to add media files like images, flash movies and so on. It is very similar to a word processor, so it is quite easy to use and understand, because you probably are already familiar with its interface.

## Media
This content represents a single media file of each type, so it could be an image, a flash movie, a pdf file and so on. The interface is the same used by the media library, but when it is used in content mode, it let you add files to the page, simply double clicking them.

## Menu
This content is the one used to create the navigation menu to link the pages of the website. A menu is made by an unordered list of links, so the interface is the same used by the Text content, but has just the commands to manage the unordered list and to create or remove the links.

## Script
This is the most powerful content type you get, because it lets you enter an entire javascript tool in an easy way. It is made by five fields:

- Html Code
- External Javascripts
- Internal Javascript
- External Stylesheets
- Internal Stylesheet

As you might guess, you can add a piece of html code, external and internal stylesheets and javascripts. There is a [dedicated chapter](how-to-add-an-external-javascript-tool) that explains how to use a javascript gallery on your website.

Go to next chapter [AlphaLemon CMS User Manual (Part 12)](user-manual-part-2)
