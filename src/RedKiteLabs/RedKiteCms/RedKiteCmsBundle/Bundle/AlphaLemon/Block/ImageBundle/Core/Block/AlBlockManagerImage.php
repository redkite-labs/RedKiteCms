<?php
/**
 * An AlphaLemonCms Block
 */

namespace AlphaLemon\Block\ImageBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;

/**
 * Description of AlBlockManagerImage
 */
class AlBlockManagerImage extends AlBlockManagerJsonBlockContainer
{
    protected $blockTemplate = 'ImageBundle:Image:image.html.twig';  
    protected $editorTemplate = 'ImageBundle:Editor:_editor.html.twig';
     
    public function getDefaultValue()
    {
        $value = 
            '
                {
                    "0" : {
                        "src": "",
                        "data_src": "holder.js/260x180",
                        "title" : "Sample title",
                        "alt" : "Sample alt"
                    }
                }
            ';
        
        return array('Content' => $value);
    }
    
    public function getHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => $this->blockTemplate,
            'options' => array(
                'image' => $items[0],
            ),
        ));
    }
    
    public function editorParameters()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];
        
        $formClass = $this->container->get('image.form');
        $form = $this->container->get('form.factory')->create($formClass, $item);
        
        return array(
            "template" => $this->editorTemplate,
            "title" => "Image editor",
            "form" => $form->createView(),
        );
    }
}
