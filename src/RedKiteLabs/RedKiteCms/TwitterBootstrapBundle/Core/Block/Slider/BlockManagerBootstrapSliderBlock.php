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

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\Slider;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\ImagesBlock\BlockManagerImages;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock\BlockManagerJsonBase;

/**
 * Defines the Block Manager to handle a Bootstrap Carousel slider
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapSliderBlock extends BlockManagerImages
{
    /**
     * Defines the App-Block's default value
     *
     * @return array
     */
    public function getDefaultValue()
    {
        $defaultValue =
        '{
            "0" : {
                "src": "",
                "data_src" : "holder.js/400x280",
                "title" : "Sample title",
                "alt" : "Sample alt",
                "caption_title" : "First Thumbnail label",
                "caption_body" : "Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus."
            },
            "1" : {
                "src": "",
                "data_src" : "holder.js/400x280",
                "title" : "Sample title",
                "alt" : "Sample alt",
                "caption_title" : "Second Thumbnail label",
                "caption_body" : "Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus."
            },
            "2" : {
                "src": "",
                "data_src" : "holder.js/400x280",
                "title" : "Sample title",
                "alt" : "Sample alt",
                "caption_title" : "Third Thumbnail label",
                "caption_body" : "Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus."
            }
        }';

        return array(
            'Content' => $defaultValue,
        );
    }

    /**
     * Defines the parameters passed to the App-Block's editor
     *
     * @return array
     */
    public function editorParameters()
    {
        $items = array();
        $values = BlockManagerJsonBase::decodeJsonContent($this->alBlock->getContent());
        /*while ( ! empty($values)) {
            $items[] = array_splice($values, 0, 3);
        }*/

        $formClass = $this->container->get('bootstrapsliderblock.form');
        $form = $this->container->get('form.factory')->create($formClass);

        return array(
            "template" => 'TwitterBootstrapBundle:Editor:Slider/editor.html.twig',
            "title" => $this->translator->translate('slider_editor_title', array(), 'TwitterBootstrapBundle'),
            "form" => $form->createView(),
            'form_name' => $form->getName(),
            'items' => $values,
        );
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

        $images = BlockManagerJsonBase::decodeJsonContent($this->alBlock);

        $bootstrapVersion = $this->container->get('red_kite_cms.active_theme')->getThemeBootstrapVersion();
        $template = sprintf('TwitterBootstrapBundle:Content:Slider/%s/content.html.twig', $bootstrapVersion);

        return array(
            "RenderView" => array(
                "view" => $template,
                "options" => array(
                    "items" => $images,
                )
            )
        );
    }

    /**
     * Edits the current block object
     *
     * @param  array
     * @return boolean
     * @codeCoverageIgnore
     */
    protected function edit(array $values)
    {
        $values = $this->removeFormNameReference($values);
        $values["Content"] = urldecode($values["Content"]);

        return parent::edit($values);
    }

    /**
     * Removes the form name from the images' attributes given back from the attributes
     * form
     *
     * @param  array $values
     * @return array
     */
    protected function removeFormNameReference(array $values)
    {
        if (array_key_exists('Content', $values)) {
            $formClass = $this->container->get('bootstrapsliderblock.form');
            $buttonForm = $this->container->get('form.factory')->create($formClass);

            $formName = $buttonForm->getName() . "_";
            $values["Content"] = str_replace($formName, "", $values["Content"]);
        }

        return $values;
    }
}
