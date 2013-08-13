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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Label;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;

/**
 * Defines the Block Manager to handle the Bootstrap Label
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapLabelBlock extends AlBlockManagerJsonBlockContainer
{
    protected $formParam = 'bootstraplabelblock.form';    
    protected $blockTemplate = 'TwitterBootstrapBundle:Content:Label/label.html.twig';    
    protected $editorTemplate = 'TwitterBootstrapBundle:Editor:Label/label_editor.html.twig';

    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {
        $value = 
            '
                {
                    "0" : {
                        "label_text": "Label 1",
                        "label_type": ""
                    }
                }
            ';
        
        return array('Content' => $value);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => $this->blockTemplate,
            'options' => array('data' => $items[0]),
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function editorParameters()
    {        
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];
        
        $formClass = $this->container->get($this->formParam);
        $form = $this->container->get('form.factory')->create($formClass, $item);
        
        return array(
            "template" => $this->editorTemplate,
            "title" => "Bootstrap label editor",
            "form" => $form->createView(),
        );
    }
}
