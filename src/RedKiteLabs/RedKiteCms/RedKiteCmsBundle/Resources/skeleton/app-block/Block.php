<?php
/**
 * A RedKiteCms Block
 */

namespace {{ namespace }}\Core\Block;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock\BlockManagerJsonBlockContainer;

/**
 * Description of BlockManager{{ bundle_basename }}
 */
class BlockManager{{ bundle_basename }} extends BlockManagerJsonBlockContainer
{
    public function getDefaultValue()
    {
        $value =
            '
                {
                    "0" : {
                        "block_text": "Default value"
                    }
                }
            ';

        return array('Content' => $value);
    }

    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());

        return array('RenderView' => array(
            'view' => '{{ bundle }}:Content:{{type_lowercase}}.html.twig',
            'options' => array('item' => $items[0]),
        ));
    }

    public function editorParameters()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];

        $formClass = $this->container->get('{{type_lowercase}}.form');
        $form = $this->container->get('form.factory')->create($formClass, $item);

        return array(
            "template" => '{{ bundle }}:Editor:{{type_lowercase}}.html.twig',
            "title" => "My awesome App-Block",
            "form" => $form->createView(),
        );
    }
}
