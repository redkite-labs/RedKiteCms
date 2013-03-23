<?php
/**
 * An AlphaLemonCms Block
 */

namespace AlphaLemon\Block\LinkBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;

/**
 * Description of AlBlockManagerLink
 */
class AlBlockManagerLink extends AlBlockManagerJsonBlockContainer
{
    public function getDefaultValue()
    {
        $value = 
            '
                {
                    "0" : {
                        "href": "#",
                        "value": "Link"
                    }
                }
            ';
        
        return array('Content' => $value);
    }
    
    public function getHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => 'LinkBundle:Content:link.html.twig',
            'options' => array('data' => $items[0]),
        ));
    }
    
    protected function replaceHtmlCmsActive()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];
        
        $formClass = $this->container->get('bootstrap_link.form');
        $form = $this->container->get('form.factory')->create($formClass, $item);
        
        $pagesRepository = $this->container->get('alpha_lemon_cms.factory_repository')->createRepository('Page');
                
        return array('RenderView' => array(
            'view' => 'LinkBundle:Editor:link_editor.html.twig',
            'options' => array(
                'link' => $item, 
                'form' => $form->createView(),
                'pages' => ChoiceValues::getPages($pagesRepository),
            ),
        ));
    }
}
