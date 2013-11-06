<?php
/*
 * This file is part of the TwitterBootstrapBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright 
 * notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 * 
 * @license    MIT LICENSE
 * 
 */

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Navbar;

/**
 * Defines the Block Manager to handle a Bootstrap navbar menu
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapNavbarMenuBlock extends AlBlockManagerBootstrapNavbarBlock
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {
        $value = '
            {
                "alignment" : "none",
                "items": {
                    "0": {
                        "blockType" : "Link"
                    },
                    "1": {
                        "blockType" : "Link"
                    }
                }
            }';
            
        return array('Content' => $value);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function renderHtml()
    {
        $menu = $this->decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:Navbar/Menu/navbar_menu.html.twig',
            'options' => array(
                'menu' => $menu, 
            ),
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function editorParameters()
    {
        $parameters = $this->decodeJsonContent($this->alBlock);
        unset($parameters["items"]);
        
        $formService = $this->container->get('bootstrap_navbar_menu.form');
        $form = $this->container->get('form.factory')->create($formService, $parameters);
        
        return array(
            "template" => 'TwitterBootstrapBundle:Editor:Navbar/Menu/navbar_menu_editor.html.twig',
            "title" => "Navbar menu editor",
            "form" => $form->createView(),
        );
    }
}