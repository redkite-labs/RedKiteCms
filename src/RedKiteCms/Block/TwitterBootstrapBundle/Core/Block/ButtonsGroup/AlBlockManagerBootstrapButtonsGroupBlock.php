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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\ButtonsGroup;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockCollection;

/**
 * Defines the Block Manager to handle a Bootstrap Buttons group
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapButtonsGroupBlock extends AlBlockManagerJsonBlockCollection
{
    private $visibleColumns = array('button_text');

    /**
     * Defines the App-Block's default value
     *
     * @return array
     */
    public function getDefaultValue()
    {
        $value =
        '{
            "0" : {
                "type": "BootstrapButtonBlock"
            },
            "1" : {
                "type": "BootstrapButtonBlock"
            },
            "2" : {
                "type": "BootstrapButtonBlock"
            }
        }';

        return array('Content' => $value);
    }

    /**
     * Renders the App-Block's content view
     *
     * @return string|array
     */
    protected function renderHtml()
    {
        if (null === $this->alBlock) {
            return "";
        }

        $buttons = $this->decodeJsonContent($this->alBlock->getContent(), true);

        return array(
            "RenderView" => array(
                "view" => "TwitterBootstrapBundle:Content:ButtonsGroup/buttons_group.html.twig",
                "options" => array(
                    "buttons" => $buttons,
                )
            )
        );
    }
}
