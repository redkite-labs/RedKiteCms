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
            'view' => 'ImageBundle:Image:image.html.twig',
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
        $buttonForm = $this->container->get('form.factory')->create($formClass, $item);
        
        return array(
            "template" => "ImageBundle:Editor:_editor.html.twig",
            "title" => "Image editor",
            "form" => $buttonForm->createView(),
        );
    }
    /*
    protected function replaceHtmlCmsActive()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];
        
        $formClass = $this->container->get('image.form');
        $buttonForm = $this->container->get('form.factory')->create($formClass, $item);
        
        return array('RenderView' => array(
            'view' => 'ImageBundle:Editor:image_editor.html.twig',
            'options' => array(
                'image' => $item, 
                'form' => $buttonForm->createView(),
            ),
        ));
    }*/
}
