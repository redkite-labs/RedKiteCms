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

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\DropdownButton\AlBlockManagerBootstrapDropdownButtonBlock;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlock;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;

/**
 * Defines the Block Manager to handle a Bootstrap navbar dropdown button
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapNavbarDropdownBlock extends AlBlockManagerBootstrapDropdownButtonBlock
{
    protected $blockTemplate = 'TwitterBootstrapBundle:Content:Navbar/Dropdown/navbar_dropdown_button.html.twig';

    /**
     * Defines the App-Block's default value
     *
     * @return array
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
            }';

        return array('Content' => $value);
    }

    /**
     * Defines the parameters passed to the App-Block's editor
     *
     * @return array
     */
    public function editorParameters()
    {
        $dropdown = AlBlockManagerJsonBlock::decodeJsonContent($this->alBlock->getContent());
        $item = $dropdown[0];
        $items = $item["items"];
        unset($item["items"]);

        $formClass = $this->container->get('bootstrap_navbar_dropbown.form');
        $form = $this->container->get('form.factory')->create($formClass, $item);

        $seoRepository = $this->factoryRepository->createRepository('Seo');
        $request = $this->container->get('request');
        
        return array(
            "template" => $this->editorTemplate,
            "title" =>  $this->translator->translate('dropdown_button_editor_title', array(), 'TwitterBootstrapBundle'),
            "form" => $form->createView(),
            'items' => $items,
            'permalinks' => ChoiceValues::getPermalinks($seoRepository, $request->get('_locale')),
        );
    }
}
