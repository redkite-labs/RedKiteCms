<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use RedKiteLabs\RedKiteCmsBundle\Core\Event\Actions\BlockEvents;
use RedKiteLabs\RedKiteCmsBundle\Core\Event\Actions\Block;
use Symfony\Component\HttpFoundation\Request;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidOperationException;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\RuntimeException;

/**
 * Implements the actions to manage the blocks on a slot's page
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlocksController extends Base\BaseController
{
    public function addBlockAction()
    {
        $this->checkPageIsValid();

        $request = $this->container->get('request');
        $slotName = $request->get('slotName');
        $factoryRepository = $this->container->get('red_kite_cms.factory_repository');
        $blockRepository = $factoryRepository->createRepository('Block');

        if (null !== $request->get('included') && count($blockRepository->retrieveContentsBySlotName($slotName)) > 0 && filter_var($request->get('included'), FILTER_VALIDATE_BOOLEAN)) {
            throw new InvalidOperationException('blocks_controller_included_blocks_accept_only_a_block');
        }

        $contentType = ($request->get('contentType') != null) ? $request->get('contentType') : 'Text';
        $slotManager = $this->fetchSlotManager($request, false);
        if (null !== $slotManager) {
            $res = $slotManager->addBlock($request->get('languageId'), $request->get('pageId'), $contentType, $request->get('idBlock'));
            if (! $res) {
                // @codeCoverageIgnoreStart
                throw new RuntimeException('blocks_controller_block_not_added_due_to_unespected_exception');
                // @codeCoverageIgnoreEnd
            }

            $template = 'RedKiteCmsBundle:Slot:Render/_block.html.twig';
            $blockManager = $slotManager->lastAdded();
        } else {
            if ( ! $request->get('included')) {
                throw new RuntimeException('blocks_controller_invalid_or_empty_slot');
            }
            $template = 'RedKiteCmsBundle:Slot:Render/_included_block.html.twig';

            $blockManagerFactory = $this->container->get('red_kite_cms.block_manager_factory');
            $blockManager = $blockManagerFactory->createBlockManager($contentType);

            $values = array(
                "PageId"          => $request->get('pageId'),
                "LanguageId"      => $request->get('languageId'),
                "SlotName"        => $slotName,
                "Type"            => $contentType,
                "ContentPosition" => 1,
                'CreatedAt'       => date("Y-m-d H:i:s")
            );
            $blockManager->save($values);
        }

        $idBlock = 0;
        if (null !== $request->get('idBlock')) {
            $idBlock = $request->get('idBlock');
        }

        $values = array(
            array(
                "key" => "message",
                "value" => $this->translate('blocks_controller_block_added')
            ),
            array(
                "key" => "add-block",
                "insertAfter" => "block_" . $idBlock,
                "blockId" => "block_" . $blockManager->get()->getId(),
                "slotName" => $blockManager->get()->getSlotName(),
                "value" => $this->container->get('templating')->render(
                    $template,
                    array("blockManager" => $blockManager, 'add' => true)
                )
            )
        );

        return $this->buildJSonResponse($values);
    }

    public function editBlockAction()
    {
        $this->checkPageIsValid();

        $request = $this->container->get('request');
        $slotManager = $this->fetchSlotManager($request);

        $value = urldecode($request->get('value'));
        $values = array($request->get('key') => $value);

        // @codeCoverageIgnoreStart
        if (null !== $request->get('options') && is_array($request->get('options'))) {
            $values = array_merge($values, $request->get('options'));
        }
        // @codeCoverageIgnoreEnd

        $result = $slotManager->editBlock($request->get('idBlock'), $values);
        // @codeCoverageIgnoreStart
        if (false === $result) {
            throw new RuntimeException('blocks_controller_block_editing_error');
        }
        // @codeCoverageIgnoreEnd

        if (null === $result) {
            throw new RuntimeException('blocks_controller_nothing_changed_with_these_values');
        }

        $blockManager = $slotManager->getBlockManager($request->get('idBlock'));

        $response = null;
        $dispatcher = $this->container->get('event_dispatcher');
        if (null !== $dispatcher) {
            $event = new Block\BlockEditedEvent($request, $blockManager);
            $dispatcher->dispatch(BlockEvents::BLOCK_EDITED, $event);
            $response = $event->getResponse();
            $blockManager = $event->getBlockManager();
        }

        if (null === $response) {
            $template = ($request->get('included')) ? 'RedKiteCmsBundle:Slot:Render/_included_block.html.twig' :  'RedKiteCmsBundle:Slot:Render/_block.html.twig';
            $values = array(
                array("key" => "message", "value" => $this->translate("blocks_controller_block_edited")),
                array("key" => "edit-block",
                      "blockName" => "block_" . $blockManager->get()->getId(),
                      "value" => $this->container->get('templating')->render($template, array("blockManager" => $blockManager, 'item' => $request->get('item'))),
                ),
            );

            $response = $this->buildJSonResponse($values);
        }

        return $response;
    }

    public function deleteBlockAction()
    {
        $this->checkPageIsValid();

        $request = $this->container->get('request');
        $slotManager = $this->fetchSlotManager($request);
        $res = $slotManager->deleteBlock($request->get('idBlock'));
        if (null !== $res) {
            $message = 'blocks_controller_block_removed';
            // @codeCoverageIgnoreStart
            if (! $res) {
                'blocks_controller_block_not_removed';
            }
            // @codeCoverageIgnoreEnd

            $values = array(
                array("key" => "message", "value" => $this->translate($message))
            );

            if ($slotManager->length() > 0) {
                $values[] = array(
                    "key" => "remove-block",
                    "blockName" => "block_" . $request->get('idBlock')
                );

                return $this->buildJSonResponse($values);
            }

            $values[] = array(
                "key" => "redraw-slot",
                "slotName" => $request->get('slotName'),
                "blockId" => 'block_' . $request->get('idBlock'),
                "value" => $this->container->get('templating')->render('RedKiteCmsBundle:Slot:Render/_slot.html.twig', array("slotName" => $request->get('slotName'), "included" => filter_var($request->get('included'), FILTER_VALIDATE_BOOLEAN)))
            );

            return $this->buildJSonResponse($values);
        }

        throw new RuntimeException('blocks_controller_block_does_not_exists');
    }

    protected function buildJSonResponse($values)
    {
        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function checkPageIsValid()
    {
        $pageTree = $this->container->get('red_kite_cms.page_tree');
        if ( ! $pageTree->isValid()) {
            throw new RuntimeException("blocks_controller_page_does_not_exists");
        }
    }

    private function fetchSlotManager(Request $request, $throwExceptionWhenNull = true)
    {
        $slotManager = $this->container->get('red_kite_cms.template_manager')->getSlotManager($request->get('slotName'));
        if ($throwExceptionWhenNull && null === $slotManager) {
            throw new RuntimeException("blocks_controller_invalid_or_empty_slot");
        }

        return $slotManager;
    }


    /**
     * @deprecated since 1.1.0
     * @codeCoverageIgnore
     */
    public function showBlocksEditorAction()
    {
        try {
            $request = $this->container->get('request');
            $factoryRepository = $this->container->get('red_kite_cms.factory_repository');
            $blockRepository = $factoryRepository->createRepository('Block');
            $block = $blockRepository->fromPK($request->get('idBlock'));
            if ($block != null) {
                $alBlockManager = $this->container->get('red_kite_cms.block_manager_factory')->createBlockManager($block);
                $dispatcher = $this->container->get('event_dispatcher');
                if (null !== $dispatcher) {
                    $event = new Block\BlockEditorRenderingEvent($this->container, $request, $alBlockManager);
                    $dispatcher->dispatch(BlockEvents::BLOCK_EDITOR_RENDERING, $event);
                    $editor = $event->getEditor();
                }

                if (null === $editor) {
                    $editorSettingsParamName = sprintf('%s.editor_settings', strtolower($block->getType()));
                    $editorSettings = ($this->container->hasParameter($editorSettingsParamName)) ? $this->container->getParameter($editorSettingsParamName) : array();
                    $template = sprintf('%sBundle:Block:%s_editor.html.twig', $block->getType(), strtolower($block->getType()));

                    $editor = $this->container->get('templating')->render($template, array("alContent" => $alBlockManager,
                                                                                           "jsFiles" => explode(",", $block->getExternalJavascript()),
                                                                                           "cssFiles" => explode(",", $block->getExternalStylesheet()),
                                                                                           "language" => $request->get('languageId'),
                                                                                           "page" => $request->get('pageId'),
                                                                                           "editor_settings" => $editorSettings));
                }

                $values[] = array("key" => "editor",
                                  "value" => $editor);
                $response = $this->buildJSonResponse($values);
                if (null !== $dispatcher) {
                    $event = new Block\BlockEditorRenderedEvent($response, $alBlockManager);
                    $dispatcher->dispatch(BlockEvents::BLOCK_EDITOR_RENDERED, $event);
                    $response = $event->getResponse();
                }

                return $response;
            } else {
                throw new RuntimeException('blocks_controller_block_not_found');
            }
        } catch (\Exception $e) {
            return $this->renderDialogMessage($e->getMessage());
        }
    }
}
