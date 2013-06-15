<?php
/**
 * This file is part of the BusinessCarouselBundle and it is distributed
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
 * Renders the editor to manipulate a Json list of items
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @deprecated since 1.1.0
 * @codeCoverageIgnore
 */
abstract class RenderingListEditorListener extends BaseRenderingEditorListener
{
    protected $alBlockManager = null;
    protected $container;

    /**
     * {@inheritdoc}
     */
    protected function renderEditor(BlockEditorRenderingEvent $event, array $params)
    {
        try {
            $this->alBlockManager = $event->getBlockManager();
            if ($this->alBlockManager instanceof $params['blockClass']) {
                $editor = $this->doRenderEditor($event);
                $event->setEditor($editor);
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Renders the editor
     *
     * @param  \AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\Block\BlockEditorRenderingEvent $event
     * @return string                                                                             The rendered editor
     */
    protected function doRenderEditor(BlockEditorRenderingEvent $event)
    {
        $this->container = $event->getContainer();

        $block = $this->alBlockManager->get();
        $className =$block->getType();
        $items = json_decode($block->getContent(), true);
        $form = $this->setUpForm($block->getId(), -1); //, $request->get('itemId')
        $template = sprintf('%sBundle:Block:%s_list.html.twig', $className, strtolower($className));

        return $event->getContainer()->get('templating')->render($template,
                array(
                    "items" => $items,
                    "block_manager" => $this->alBlockManager,
                    "block_id" => $block->getId(),
                    'form' => $form->createView()
                )
        );
    }

    /**
     * Sets up the form that manages the json item
     *
     * @param int The block id
     * @param int The item id
     * @return Form
     */
    protected function setUpForm($blockId, $itemId)
    {
        $item = null;
        $block = $this->fetchBlock($blockId);
        if ($itemId != -1) {
            $content = json_decode($block->getContent(), true);

            if (!array_key_exists($itemId, $content)) {
                throw new \InvalidArgumentException('It seems that the item requested does not exist anymore');
            }

            $item = $content[$itemId];
            $item['id'] = $itemId;
        }

        $formName = sprintf('%s.form', strtolower($block->getType()));
        $formClass = $this->container->get($formName);

        return $this->container->get('form.factory')->create($formClass, $item);
    }

    /**
     * Retrieves the block
     *
     * @param int The block id
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface
     */
    protected function fetchBlock($blockId)
    {
        $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $repository = $factoryRepository->createRepository('Block');
        $block = $repository->fromPk($blockId);

        if (null == $block) {
            throw new \InvalidArgumentException('It seems that the block to edit does not exist anymore');
        }

        return $block;
    }
}
