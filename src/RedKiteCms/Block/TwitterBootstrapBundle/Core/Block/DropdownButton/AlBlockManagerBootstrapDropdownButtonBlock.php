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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\DropdownButton;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerContainer;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBase;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;

/**
 * Defines the Block Manager to handle a Bootstrap Dropdown Button
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapDropdownButtonBlock extends AlBlockManagerContainer
{
    protected $blockTemplate = 'TwitterBootstrapBundle:Content:DropdownButton/dropdown_button.html.twig';  
    protected $editorTemplate = 'TwitterBootstrapBundle:Editor:DropdownButton/dropdown_editor.html.twig';
    
    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {
        $value = '
            {
                "0": {
                    "button_text": "Dropdown Button 1",
                    "button_type": "",
                    "button_attribute": "",
                    "button_dropup" : "none",
                    "items": [
                        {
                            "data" : "Item 1", 
                            "metadata" : {  
                                "type": "link",
                                "href": "#"
                            }
                        },
                        { 
                            "data" : "Item 2", 
                            "metadata" : {  
                                "type": "link",
                                "href": "#"
                            }
                        },
                        { 
                            "data" : "Item 3", 
                            "metadata" : {  
                                "type": "link",
                                "href": "#"
                            }
                        }
                    ]
                }
            }
            ';
        
        return array('Content' => $value);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function renderHtml()
    {
        $items = AlBlockManagerJsonBase::decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => $this->blockTemplate,
            'options' => array('data' => $items[0]),
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function editorParameters()
    {
        $items = AlBlockManagerJsonBase::decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];
        $items = $item["items"];  
        unset($item["items"]);
        
        $bootstrapFormFactory = $this->container->get('twitter_bootstrap.bootstrap_form_factory');
        $form = $bootstrapFormFactory->createForm('DropdownButton', 'AlDropdownButtonType', $item);
        
        $seoRepository = $this->factoryRepository->createRepository('Seo');        
        $request = $this->container->get('request');
        
        return array(
            "template" => $this->editorTemplate,
            "title" => $this->translator->translate('dropdown_button_editor_title', array(), 'TwitterBootstrapBundle'),
            "form" => $form->createView(),
            'items' => $items,  
            'permalinks' => ChoiceValues::getPermalinks($seoRepository, $request->get('_locale')),
        );
    }
    
    /**
     * {@inheritdoc}
     */
    protected function edit(array $values)
    {
        $values = $this->saveDropdownItems($values);

        return parent::edit($values); 
    }
    
    /**
     * Saves the dropdown items
     *
     * @param array
     * @return array
     */
    protected function saveDropdownItems(array $values)
    { 
        if (array_key_exists('Content', $values)) {
            $unserializedData = array();
            $serializedData = $values['Content'];
            parse_str($serializedData, $unserializedData);
            
            $buttonValues = $unserializedData["al_json_block"];
            if (array_key_exists("dropdown_items_form", $unserializedData)) {
                $buttonValues += array('items' => $unserializedData["dropdown_items_form"]);
            }
            
            $values['Content'] = json_encode(array($buttonValues));
        }
        
        return $values;
    }
}