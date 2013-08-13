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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Thumbnail;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;

/**
 * Defines the Block Manager to handle the Bootstrap Thumbnail
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapThumbnailBlock extends AlBlockManagerJsonBlockContainer
{
    protected $blockTemplate = 'TwitterBootstrapBundle:Content:Thumbnail/thumbnail.html.twig';
    protected $editorTemplate = 'TwitterBootstrapBundle:Editor:Thumbnail/editor.html.twig';
    
    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {
        $value = '
            {
                "0" : {
                    "width": "span3"
                }
            }';
        
        return array('Content' => $value);
    }

    /**
     * {@inheritdoc}
     */
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];
        
        return array('RenderView' => array(
            'view' => $this->blockTemplate,
            'options' => array(
                'thumbnail' => $item,
            ),
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function editorParameters()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];
        
        $formClass = $this->container->get('bootstrap_thumbnail.form');
        $form = $this->container->get('form.factory')->create($formClass, $item);
        
        return array(
            "template" => $this->editorTemplate,
            "title" => "Thumbnail editor",
            "form" => $form->createView(),
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getIsInternalBlock()
    {
        return true;
    }
}
