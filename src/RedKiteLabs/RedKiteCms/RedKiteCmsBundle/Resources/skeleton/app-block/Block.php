<?php
/**
 * An AlphaLemonCms Block
 */

namespace {{ namespace }}\Core\Block;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;

/**
 * Description of AlBlockManager{{ bundle_basename }}
 */
class AlBlockManager{{ bundle_basename }} extends AlBlockManagerJsonBlockContainer
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