# Render the page

## The PageTree object
The PageTree object is the class deputated to store and manage the information that defines a web page. This object in initialized into the Dependency 
Injector Container anf stores several information about the page itself, which are:

    Theme Name
    Template Name
    External stylesheets
    External javascripts
    Internal stylesheets
    Internal javascripts
    Slots
    Contents
    SEO metatags (title, description, keywords)

## The TemplateController
ThemeEngineBundle provides a base controller called TemplateController, that directly inherits from the Symfony's Controller object, which contains 
the methods to fill up the PageTree object and to render the page, giving to the twig engine the required information it needs to correcly process the 
page rendering. To properly render a page that uses a ThemeEngine's template, your controllers should inherit from this one.

    /path/to/application/bundle/Controller/WebSiteController.php
    
    use AlphaLemon\ThemeEngineBundle\Controller\TemplateController;

    class WebSiteController extends TemplateController
    {
        /**
         * @Route("/hello")
         */
        public function helloAction()
        {
            $this->setUpPageTree('home', 'custom-dictionary');
        }
    }

The setUpPageTree() method exposes two parameters: the first mandatory argument is the name of the template the action must render and the second not 
mandatory is the name of the dictionary used for the translations. This method simply initializes the PageTree object within the template you want to render.
When the object is bootstrapped, you can retrive and manage it as follows:
    
    /path/to/your/application/bundle/Controller/WebSiteController.php
    public function testAction()
    {
        $this->setUpPageTree('home', 'test');

        // Retrieve the pageTree object from the container
        $pageTree = $this->container->get('al_page_tree');

        // Adds a new content to the slogan_box slot
        $pageTree->addContent('slogan_box', array('HtmlContent' => '<h1>Welcome to AlphaLemon ThemeBundle</h1>')); 

        // Renders the template
        return $this->doRender();
    }
    
To render the template, the inherited doRender() method is called: it takes all the required information from the PageTree object and passes them 
to the template is being rendered.

## Managing contents with the PageTree object
The PageTree object has several methods to manage the contents. In the example above a new content has been added to the slogan_box slot by the 
addContent method, which simply adds the given content to an array. This method can be used to overidde an existing content in the array, passing
the third parameter, which is an integer that idenfies the content. For example if there are three contents saved, to replace the second content
you may call the methos as follows:

    $pageTree->addContent('slogan_box', array('HtmlContent' => 'Ne content'), 1); 

### Adding more contents 
The setContents method adds more contents to a slot with a single call:

    $pageTree->setContents(array('screenshots_box' => array(array('HtmlContent' => $content), array('HtmlContent' => 'Another content'))));
    
This method accepts as third parameter a boolean value that when is setted to true, cleans the contests saved on the slot and adds the ones given:

    $pageTree->setContents(array('screenshots_box' => array(array('HtmlContent' => $content), array('HtmlContent' => 'Another content'))), true);
    
### Rendering a twig template
Sometimes it could be useful to render a twig view instead of giving the html code. This job is made changing the HtmlContent key to RenderView and
giving as value the view to render:

    $pageTree->addContent('screenshots_box', array('RenderView' => 'twig/view));

## The BeforePageRenderingEvent
When the PageTree object is initialized, the setUpPageTree raises an event called BeforePageRenderingEvent which lets you manage the contents before
they are rendered. This could be useful when you want to render a twig template for a repeated content. 

You can find a full example that explains how to add a listener for this event on the [alphalemon website](http://alphalemon.com/how-to-change-a-content-at-runtime)