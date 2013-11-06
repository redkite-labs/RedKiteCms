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

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockCollectionBase;

/**
 * Defines the Block Manager to handle the Bootstrap Navbar
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapNavbarBlock extends AlBlockManagerJsonBlockCollectionBase
{    
    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {
        $value = '
            {
                "position": "navbar-fixed-top",
                "inverted": "",
                "items": {
                    "0": {
                        "blockType" : "BootstrapNavbarMenuBlock"
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
        $navbar = $items = $this->decodeJsonContent($this->alBlock->getContent());
        
        // Backward compatibility
        if ( ! array_key_exists('items', $navbar)) {
            $navbar = array(
                "position" => "navbar-fixed-top",
                "inverted" => "",
                "items" => array(
                    array(
                        "blockType" => "BootstrapNavbarMenuBlock",
                    )
                ),
            );
        }
        
        $bootstrapVersion = $this->container->get('red_kite_cms.active_theme')->getThemeBootstrapVersion(); 
        $template = sprintf('TwitterBootstrapBundle:Content:Navbar/Navbar/%s/navbar.html.twig', $bootstrapVersion);
        
        return array('RenderView' => array(
            'view' => $template,
            'options' => array(
                'navbar' => $navbar, 
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
        
        $formService = $this->container->get('bootstrap_navbar.form');
        $form = $this->container->get('form.factory')->create($formService, $parameters);
        
        return array(
            "template" => 'TwitterBootstrapBundle:Editor:Navbar/Navbar/navbar_editor.html.twig',
            "title" => "Navbar editor",
            "form" => $form->createView(),
        );
    }
    
    /**
     * {@inheritdoc}
     *
     * Extends the base edit method to manage a json collection of objects
     */
    protected function edit(array $values)
    {
        if ( ! array_key_exists('Content', $values)){
            return parent::edit($values);
        }
        
        $content = $this->decodeJsonContent($this->alBlock);
        
        $parameters = array();
        $serializedData = $values['Content'];            
        parse_str($serializedData, $parameters); 
        
        $isSavingAttributes = array_key_exists('al_json_block', $parameters);        
        if ($isSavingAttributes) {
            $newValues = $this->saveAttributes($parameters["al_json_block"], $content);
        }
        
        if ( ! $isSavingAttributes) {
            $newValues = $this->saveItems($values, $content);
        }
        
        return parent::edit(array("Content" => json_encode($newValues)));
    }
    
    private function saveAttributes(array $values, $content)
    {
        return array_merge($content, $values);
    }
    
    private function saveItems(array $values, $content)
    {   
        $updatedValues = $this->manageCollection($values, $content["items"]);
        $updatedValues["items"] = json_decode($updatedValues["Content"], true);
        
        return array_merge($content, $updatedValues);
    }
}