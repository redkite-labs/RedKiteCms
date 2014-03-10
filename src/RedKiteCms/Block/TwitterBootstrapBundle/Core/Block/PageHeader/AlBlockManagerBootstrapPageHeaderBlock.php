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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\PageHeader;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;

/**
 * Defines the Block Manager to handle the Bootstrap Page Header component
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapPageHeaderBlock extends AlBlockManagerJsonBlockContainer
{
    /**
     * Defines the App-Block's default value
     *
     * @return array
     */
    public function getDefaultValue()
    {
        $value =
            '
                {
                    "0": {
                        "page_header_title": "Page Header",
                        "page_header_subtitle": "An awesome component",
                        "page_header_tag": "h1"
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
        $item = $this->decodeJsonContent($this->alBlock->getContent());

        return array('RenderView' => array(
            'view' => 'TwitterBootstrapBundle:Content:PageHeader/page_header.html.twig',
            'options' => array('data' => $item[0]),
        ));
    }

    /**
     * Defines the parameters passed to the App-Block's editor
     *
     * @return array
     */
    public function editorParameters()
    {
        $item = $this->decodeJsonContent($this->alBlock->getContent());
        
        $formClass = $this->container->get('bootstrap_page_header_block.form');
        $form = $this->container->get('form.factory')->create($formClass, $item[0]);

        return array(
            "template" => "TwitterBootstrapBundle:Editor:PageHeader/page_header_editor.html.twig",
            "title" => $this->translator->translate('page_header_editor_title', array(), 'TwitterBootstrapBundle'),
            "form" => $form->createView(),
        );
    }
}
