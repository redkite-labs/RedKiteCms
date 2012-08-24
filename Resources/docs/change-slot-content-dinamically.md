# Change the contents of a slot dinamically

AlphaLemon CMS lets you add the website contents directly on the web page you are editing in the backend. Those contents are written as
twig templates when are deployed for the production environment.

Sometimes you may need to add a content that displays some data retrieved by a database, or a contact form that must display the form itself to let the
website visitor adding the required information, then process those data and, at last, display a proper message based on the form process result. 

Those tasks cannot be achieved simply using the AlphaLemon CMS editor, so you whould have needed something to add dinamic data to your page. This task can
be achieved implementig a listener. Let's see how.

## The rendering process
When AlphaLemon CMS has processed the user request and it's ready to return the response, before returnig that response dispatches a 
**BeforePageRenderingEvent** event, in this way you just need to implement a listener that listen to that event to change a content on a slot.

More deeply, that event is dispatched three times, following the schema proposed below:

1. **page_renderer.before_page_rendering**
2. **page_renderer.before_[current language]_rendering**
3. **page_renderer.before_[current page]_rendering**

This architecture gives you a great set of possibilities to manage your contents exactly when you need. In fact a listerner responding to the 
**page_renderer.before_page_rendering** event is executed for every page of the website, reponding to the 
**page_renderer.before_[current language]_rendering** event means that the listener is executed for every page that belongs a specific language 
and, at last, reponding to the **page_renderer.before_[current page]_rendering** event means that the listener is executed only for that specific page.

Events are called in the order proposed so a specific page listener might override a content rendered at language or at site level.

## The listener
Implementing the listener is quite simple. First of all you must create the class that executes the contents replacements, so under your 
deploy bundle simply add the following class:

    Acme/WebSiteBundle/Listener/IndexRenderingListener.php

    use AlphaLemon\ThemeEngineBundle\Core\Rendering\Listener\BasePageRenderingListener;

    class IndexRenderingListener extends BasePageRenderingListener
    {
    }

As you may notice the class extends the **BasePageRenderingListener** which contains all the logic required to respond the event and to 
change the slot's content. The only thing you have to do is to tell which slots to edit and the content to add or replace.

This task is achieved implementing the required protected function **renderSlotContents**, so change your class as follows:

    Acme/WebSiteBundle/Listener/IndexRenderingListener.php
    use AlphaLemon\ThemeEngineBundle\Core\Rendering\Listener\BasePageRenderingListener;

    class IndexRenderingListener extends BasePageRenderingListener
    {
        protected function renderSlotContents()
        {
            $slotContent = new AlSlotContent();
            $slotContent->setContent("My great replaced content")
                        ->setSlotName('top_section_2')
                        ->replace();

            return array($slotContent);
        }
    }

In the example above an AlSlotContent is instantiated then both the content and the slot name are setted and, at last, the replace method is called.
The slot is transformed into an array and returned to the base class which will replace the content contained into the **top_section_2** slot 
with the one defined in the AlSlotContent object.

Sometimes it could be useful to inject a content into a slot instead replacing the existing one; in this case just call the **inject()** method instead
the **replace()** one.

## Configure the listener in the DIC
This listener to be callable must be declared into the Dipendency Container Injector, so add the following code to your services.xml file:

    Acme/WebSiteBundle/Resources/config/services.xml
    <parameters>
        <parameter key="acme_web_site.index_listener.class">Acme\WebSiteBundle\Listeners\IndexRenderingListener</parameter>
    </parameters>

    <services>
        <service id="acme_web_site.index_listener" class="%acme_web_site.index_listener.class%">
            <tag name="alphalemon_theme_engine.event_listener" event="page_renderer.before_index_rendering" method="onPageRendering" priority="0" />
            <argument type="service" id="service_container" />
        </service>
    </services>

Declaring a service in the DIC should be a known operation, otherwise you may read about it in the Symfony2 documentation, so here, only the 
**custom tag** declaration will be explained. As you noticed, the service exposes the following tag:

    <tag name="alphalemon_theme_engine.event_listener" event="page_renderer.before_index_rendering" method="onPageRendering" priority="0" />

The **name** option identifies the kind of event and it must be **alphalemon_theme_engine.event_listener**. The called method is **onPageRendering**
and it is defined in the parent class of your listener.

More interesting is the event option, which, in this case, is **page_renderer.before_index_rendering**: this means that this listener will be
called only for the index page, as explained in the **The rendering process** paragraph. 

To have this listener work for all the site pages, the option would be **page_renderer.before_page_rendering**, while having it working for a specific 
language it would be **page_renderer.before_en_rendering** for the english language.

> When you declare a listener for a page, you must use the page name and not its permalink.
