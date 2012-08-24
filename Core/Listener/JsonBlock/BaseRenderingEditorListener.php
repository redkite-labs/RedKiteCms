<?php
/*
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
abstract class BaseRenderingEditorListener
{
    abstract protected function configure();
    abstract protected function renderEditor(BlockEditorRenderingEvent $event, array $params);

    public function onBlockEditorRendering(BlockEditorRenderingEvent $event)
    {
        try
        {
            $params = $this->configure();

            if (!is_array($params)) {
                throw new \InvalidArgumentException(sprintf('The "configure" method for class "%s" must return an array', get_class($this)));
            }

            if (!array_key_exists('blockClass', $params)) {
                throw new \InvalidArgumentException(sprintf('The array returned by the "configure" method of the class "%s" method must contain the "blockClass" option', get_class($this)));
            }

            if (!class_exists($params['blockClass'])) {
                throw new \InvalidArgumentException(sprintf('The block class "%s" defined in "%s" does not exists', $params['blockClass'], get_class($this)));
            }

            $this->renderEditor($event, $params);
        }
        catch(\Exception $ex)
        {
            throw $ex;
        }
    }
}
