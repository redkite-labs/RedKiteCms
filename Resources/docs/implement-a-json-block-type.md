# Implement a json block type
When you need to add a block made by items and each items has two or more properties, you might be tempted to add a new table to the database. This will
work for sure but a better solution could take place: using a json content to achieve that task.

AlphaLemon provides an abstract block manager that manages that special content and an editor that can be used to add, edit and remove the items for that 
block.

> This chapter implements a custom block, so you should refere the add-custom-block.md chapter for more details on how implement a custom app-block
before go over with this one.

## A real example
The BusinessCarousel, that comes with the BusinessWebsiteTheme is based on the JsonBlock type, in fact it manages a serie of items and each item 
has several properties:

    {
        "0" : {
            "name" : "John",
            "surname" : "Doe",
            "role" : "Ceo",
            "comment" : "Working with AlphaLemon Cms is amazing!"
        },
        "1" : {
            "name" : "Jane",
            "surname" : "Doe",
            "role" : "Art director",
            "comment" : "This web application is really cool!"
        }
    }

This content has two item, and each item has four properties: the name, surname, role and comment.

## The Block Manager
The Block Manager object will inherit from the AlBlockManagerJsonBlock, as follows:

    AlphaLemon/Block/BusinessCarouselBundle/Core/Block/AlBlockManagerBusinessCarousel.php
    namespace AlphaLemon\Block\BusinessCarouselBundle\Core\Block;

    class AlBlockManagerBusinessCarousel extends AlBlockManagerJsonBlock
    {
        public function getDefaultValue()
        {
            $value =
            '{
                "0" : {
                    "name" : "John",
                    "surname" : "Doe",
                    "role" : "Ceo",
                    "comment" : "This web application is really cool!"
                }
            }';

            return array('HtmlContent' => $value,
                        'InternalJavascript' => '$(".carousel").startCarousel();');
        }
    }

The default value will add only an item. To have your block returning the desidered output, the **getHtmlContentForDeploy** method has been 
redefined as follows:

    AlphaLemon/Block/BusinessCarouselBundle/Core/Block/AlBlockManagerBusinessCarousel.php
    namespace AlphaLemon\Block\BusinessCarouselBundle\Core\Block;

    class AlBlockManagerBusinessCarousel extends AlBlockManagerJsonBlock
    {
        [...]
        public function getHtmlContentForDeploy()
        {
            $carousel = '';
            $elements = array();

            // retrives the items from the json block
            $items = json_decode($this->alBlock->getHtmlContent());

            // Builds the html for each item
            foreach($items as $item) {
                $elements[] = sprintf('<li><div>%s</div><span><strong class="color1">%s %s,</strong> <br />%s</span></li>', $item->comment, $item->name, $item->surname, $item->role);
            }

            if (!empty($elements)) {

                // Prepares the carousel
                $carousel = '<div class="carousel_container">';
                $carousel .= '<div class="carousel">';
                $carousel .= sprintf('<ul>%s</ul>', implode("\n", $elements));
                $carousel .= '</div>';
                $carousel .= '<a href="#" class="up"></a>';
                $carousel .= '<a href="#" class="down"></a>';
                $carousel .= '</div>';
            }
            else
            {
                $carousel = '<p>Any item has been added</p>';
            }

            return $carousel;
        }
    }

The code is quite simple and self explained in the code, however it fetches the block content, decodes it into an array of items and
prepares the html output as the carousel wants.

## The editor
AlphaLemon CMS provides a base editor to mananage that kind of content, which is made by two twig templates, called **list** and **item**. 

The names are quite esplicative, however the **list** displays the content items as a list and the **item** represent a single item and
can be used to add and edit an item.

So to manage your json block you just need to add two new templates which extends those base twig templates.

## The BusinessCarousel list editor
A new twig template called **businesscarousel_list.html.twig** has been created under the **views/Block** bundle's folder, then the following code
has been added:

    {% extends "AlphaLemonCmsBundle:Block:Json/list.html.twig" %}

The name is mandatory and must be made as follows: **[bundle name in lower case]_list.html.twig**

## Display the editor
AlphaLemon CMS looks for a **[app_block_name_in_lower_case]_editor.html.twig** as default template when a content must be edited but in this case
we need to open a different editor instead of the default one. This task is achieved implementing a listener that listen to the 
**actions.block_editor_rendering** event.

AlphaLemon provides two pre-configured listeners: the **RenderingListEditorListener** to render the editor which displays the items list and the
**RenderingItemEditorListener** to render the editor which displays the single item.

This block requires to display the items as a list so the following class has been added:

    AlphaLemon/Block/BusinessCarouselBundle/Core/Listener/RenderingEditorListener.php
    namespace AlphaLemon\Block\BusinessCarouselBundle\Core\Listener;

    use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\JsonBlock\RenderingListEditorListener;

    class RenderingEditorListener extends RenderingListEditorListener
    {
        protected function configure()
        {
            return array('blockClass' => '\AlphaLemon\Block\BusinessCarouselBundle\Core\Block\AlBlockManagerBusinessCarousel');
        }
    }

The listenr implements the required method **configure** which returns an array where is defined the Block Manager class, identified by 
the **blockClass** option. 

### Render the editor for the item as base editor
If you need to render the **RenderingItemEditorListener** you must specify the form class as follows:

    protected function configure()
    {
        return array(
            'blockClass' => '\AlphaLemon\Block\BusinessDropCapBundle\Core\Block\AlBlockManagerBusinessDropCap',
            'formClass' => '\AlphaLemon\Block\BusinessDropCapBundle\Core\Form\Editor\DropCapType',
        );
    }

### Adding the listener to the Dependency Injector Container    
To have the listener working, it must be declared in the DIC:

    <parameter key="businesscarouseleditor_rendering.class">AlphaLemon\Block\BusinessCarouselBundle\Core\Listener\RenderingEditorListener</parameter>

    <services>
        <service id="businesscarouseleditor_rendering.class" class="%businesscarouseleditor_rendering.class%">
            <tag name="alcms.event_listener" event="actions.block_editor_rendering" method="onBlockEditorRendering" priority="0" />
        </service>
    </services>

## The form to edit an item
The last step to have a JsonBlock working is to declare a form to manage the single item. AlphaLemon CMS provides a pre-configured form
that implements the common required functionalities, so we just extend that class as follows:

    AlphaLemon/Block/BusinessCarouselBundle/Core/Form/BusinessCarouselType.php
    namespace AlphaLemon\Block\BusinessCarouselBundle\Core\Form;

    use AlphaLemon\AlphaLemonCmsBundle\Core\Form\JsonBlock\JsonBlockType;
    use Symfony\Component\Form\FormBuilderInterface;

    class BusinessCarouselType extends JsonBlockType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            parent::buildForm($builder, $options);

            $builder->add('name');
            $builder->add('surname');
            $builder->add('role');
            $builder->add('comment', 'textarea');
        }
    }

As you see, the only required thing is to instantiate the form's fields extending the **buildForm** method. Don't forget to call the parent's
method where a required field is instantiated.

### The item's editor
As we did for the list editor, we have to implement an editor for the item, so a new twig template called **businesscarousel_item.html.twig** 
has been created under the **views/Block** bundle's folder, then the following code has been added:

    {% extends "AlphaLemonCmsBundle:Block:Json/item_and_list.html.twig" %}

This editor extends the base **item.html.twig** and adds a button to return back to list editor.