<?php
/**
 * This file is part of the RedKiteCmsBaseBlocksBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;
use RedKiteLabs\RedKiteCmsBundle\Core\AssetsPath\AlAssetsPath;

/**
 * AlBlockManagerImage handles an image block
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockManagerImage extends AlBlockManagerJsonBlockContainer
{
    protected $blockTemplate = 'RedKiteCmsBaseBlocksBundle:Content:Image/image.html.twig';
    protected $editorTemplate = 'RedKiteCmsBaseBlocksBundle:Editor:Image/editor.html.twig';

    /**
     * Defines the App-Block's default value
     *
     * @return array
     */
    public function getDefaultValue()
    {
        $value = sprintf(
            '
                {
                    "0" : {
                        "src": "",
                        "data_src": "holder.js/260x180",
                        "title" : "%s",
                        "alt" : "%s"
                    }
                }
            ',
            $this->translator->translate("image_block_title_attribute", array(), 'RedKiteCmsBaseBlocksBundle'),
            $this->translator->translate("image_block_alt_attribute", array(), 'RedKiteCmsBaseBlocksBundle'));

        return array('Content' => $value);
    }

    /**
     * Renders the App-Block's content view
     *
     * @return string|array
     */
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];

        return array('RenderView' => array(
            'view' => $this->blockTemplate,
            'options' => array(
                'image' => $item,
                'folder' => AlAssetsPath::getUploadFolder($this->container),
            ),
        ));
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

        $formClass = $this->container->get('image.form');
        $form = $this->container->get('form.factory')->create($formClass, $item);

        return array(
            "template" => $this->editorTemplate,
            "title" => $this->translator->translate("image_block_editor_title", array(), 'RedKiteCmsBaseBlocksBundle'),
            "form" => $form->createView(),
        );
    }
}
