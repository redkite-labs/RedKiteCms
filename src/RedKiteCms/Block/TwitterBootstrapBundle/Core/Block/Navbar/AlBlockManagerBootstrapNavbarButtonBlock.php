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

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Button\AlBlockManagerBootstrapButtonBlock;

/**
 * Defines the Block Manager to handle a Bootstrap navbar button
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapNavbarButtonBlock extends AlBlockManagerBootstrapButtonBlock
{
    protected $blockTemplate = 'TwitterBootstrapBundle:Content:Navbar/Button/navbar_button.html.twig';
    
    /**
     * Defines the App-Block's default value
     *
     * @return array
     */
    public function getDefaultValue()
    {
        $value = '
            {
                "0" : {
                    "button_text": "Button 1",
                    "button_type": "",
                    "button_attribute": "",
                    "button_block": "",
                    "button_enabled": "",
                    "alignment": "navbar-left"
                }
            }
        ';
        
        return array('Content' => $value);
    }
    
    /**
     * Defines the parameters passed to the App-Block's editor
     *
     * @return array
     */
    public function editorParameters()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];
        
        $bootstrapFormFactory = $this->container->get('twitter_bootstrap.bootstrap_form_factory');
        $form = $bootstrapFormFactory->createForm('Navbar\Button', 'AlNavbarButtonType', $item);
        
        return array(
            "template" => "TwitterBootstrapBundle:Editor:Button/button_editor.html.twig",
            "title" => $this->translator->translate('button_block_editor_title', array(), 'TwitterBootstrapBundle'),
            "form" => $form->createView(),
        );
    }
}