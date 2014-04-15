<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\Navbar;

use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\Button\BlockManagerBootstrapButtonBlock;

/**
 * Defines the Block Manager to handle a Bootstrap navbar button
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapNavbarButtonBlock extends BlockManagerBootstrapButtonBlock
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
        $form = $bootstrapFormFactory->createForm('Navbar\Button', 'NavbarButtonType', $item);

        return array(
            "template" => "TwitterBootstrapBundle:Editor:Button/button_editor.html.twig",
            "title" => $this->translator->translate('button_block_editor_title', array(), 'TwitterBootstrapBundle'),
            "form" => $form->createView(),
        );
    }
}
