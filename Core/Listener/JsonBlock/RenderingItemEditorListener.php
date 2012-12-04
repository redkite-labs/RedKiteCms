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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\JsonBlock;

use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\Block\BlockEditorRenderingEvent;

/**
 * Manipulates the block's editor response when the editor has been rendered
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class RenderingItemEditorListener extends BaseRenderingEditorListener
{
    protected function renderEditor(BlockEditorRenderingEvent $event, array $params)
    {
        if (!array_key_exists('formClass', $params)) {
            throw new \InvalidArgumentException(sprintf('The array returned by the "configure" method of the class "%s" method must contain the "formClass" option', get_class($this)));
        }

        if (!class_exists($params['formClass'])) {
            throw new \InvalidArgumentException(sprintf('The form class "%s" defined in "%s" does not exists', $params['formClass'], get_class($this)));
        }

        try {
            $alBlockManager = $event->getBlockManager();
            if ($alBlockManager instanceof $params['blockClass']) {
                $container = $event->getContainer();
                $block = $alBlockManager->get();
                $className = $block->getType();
                $content = json_decode($block->getContent(), true);      
                $content = $content[0];
                $content = $this->formatContent($content);
                $content['id'] = 0;
                
                $form = $container->get('form.factory')->create(new $params['formClass'](), $content);
                $template = sprintf('%sBundle:Block:%s_item.html.twig', $className, strtolower($className));
                $editor = $container->get('templating')->render($template, array("form" => $form->createView()));
                $event->setEditor($editor);
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * Override this function to format the content in a different way than the saved one
     * 
     * @param type AlBlock $block
     * @return type 
     */
    protected function formatContent($content)
    {
        return $content;
    }
}
