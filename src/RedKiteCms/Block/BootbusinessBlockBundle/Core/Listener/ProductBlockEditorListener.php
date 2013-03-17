<?php
/**
 * This file is part of the BusinessDropCapBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\Block\BootbusinessProductBlockBundle\Core\Listener;

use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\Block\BlockEditorRenderingEvent;

/**
 * Renders the editor to manipulate a Json item
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
class ProductBlockEditorListener
{
    public function onBlockEditorRendering(BlockEditorRenderingEvent $event)
    {
        $alBlockManager = $event->getBlockManager();
        $blockType = $alBlockManager->get()->getType();
        if ($blockType == 'BootbusinessProductBlock') {
            $this->container = $event->getContainer();
            $request = $this->container->get('request');
            $template = sprintf('%sBundle:Block:%s_editor.html.twig', $blockType, strtolower($blockType));
            
            $editorSettingsParamName = sprintf('%s.editor_settings', strtolower($blockType));
            $editorSettings = ($this->container->hasParameter($editorSettingsParamName)) ? $this->container->getParameter($editorSettingsParamName) : array();
            $blockId = $alBlockManager->get()->getId();
            
            $imagesFormClass = $this->container->get('bootstrapthumbnailblock.form');
            $imagesForm = $this->container->get('form.factory')->create($imagesFormClass);
            
            $buttonsFormClass = $this->container->get('bootbusinessproductblock.form');
            $buttonsForm = $this->container->get('form.factory')->create($buttonsFormClass);
            
            $parameters = array(
                "alContent" => $alBlockManager,
                "language" => $request->get('languageId'),
                "page" => $request->get('pageId'),
                "editor_settings" => $editorSettings,
                "images_form" => $imagesForm->createView(),                
                "buttons_form" => $buttonsForm->createView(),
                "block_manager" => $alBlockManager, 
                "block_id" => $blockId, 
            );
            
            $parameters['images_editor_template'] = 'BootbusinessProductBlockBundle:Images:images_list.html.twig';

            $editor = $this->container->get('templating')->render($template, $parameters);

            $event->setEditor($editor);
        }
    }
}
