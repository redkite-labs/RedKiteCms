<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBaseBlocksBundle\Core\Block\Link;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock\BlockManagerJsonBlockContainer;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;

/**
 * BlockManagerLink handles a link block
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlockManagerLink extends BlockManagerJsonBlockContainer
{
    protected $blockTemplate = 'RedKiteCmsBaseBlocksBundle:Content:Link/link.html.twig';

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
                    "0" : {
                        "href": "#",
                        "value": "This is a link"
                    }
                }
            ';

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
        $link = $items[0];
        
        return array('RenderView' => array(
            'view' => $this->blockTemplate,
            'options' => array_merge($this->extraOptions, array(
                'link' => $link,
                'block_manager' => $this,
            )),
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

        $formClass = $this->container->get('bootstrap_link.form');
        $form = $this->container->get('form.factory')->create($formClass, $item);

        $seoRepository = $this->factoryRepository->createRepository('Seo');
        $request = $this->container->get('request');

        return array(
            "template" => "RedKiteCmsBaseBlocksBundle:Editor:Link/editor.html.twig",
            "title" => $this->translator->translate('link_block_editor_title', array(), 'RedKiteCmsBaseBlocksBundle'),
            "form" => $form->createView(),
            'pages' => ChoiceValues::getPermalinks($seoRepository, $request->get('_locale')),
        );
    }
}
