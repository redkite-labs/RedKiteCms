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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\ImagesBlock;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\ImagesBlock\AlBlockManagerImages;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\Block\BlockEditorRenderingEvent;

/**
 * Renders the editor to manipulate a Json item
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
abstract class BaseImagesBlockEditorListener implements ImagesListenerInterface
{
    /**
     * {@inheritdoc}
     */
    public function onBlockEditorRendering(BlockEditorRenderingEvent $event)
    {
        $alBlockManager = $event->getBlockManager();
        $blockType = $alBlockManager->get()->getType();
        if ($blockType == $this->getManagedBlockType()) {
            $container = $event->getContainer();
            $request = $container->get('request');
            $template = sprintf('%sBundle:Block:%s_editor.html.twig', $blockType, strtolower($blockType));

            $editorSettingsParamName = sprintf('%s.editor_settings', strtolower($blockType));
            $editorSettings = ($container->hasParameter($editorSettingsParamName)) ? $container->getParameter($editorSettingsParamName) : array();
            $parameters = array(
                "alContent" => $alBlockManager,
                "language" => $request->get('languageId'),
                "page" => $request->get('pageId'),
                "editor_settings" => $editorSettings,
            );

            $options = $this->configure();
            if (array_key_exists('images_editor_template', $options)) {
                $parameters['images_editor_template'] = $options['images_editor_template'];
            }

            $editor = $container->get('templating')->render($template, $parameters);

            $event->setEditor($editor);
        }
    }
}
