<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use RedKiteLabs\RedKiteCmsBundle\Core\Event\Actions\BlockEvents;
use RedKiteLabs\RedKiteCmsBundle\Core\Event\Actions\Block;
use Symfony\Component\HttpFoundation\Request;
use RedKiteLabs\RedKiteCmsBundle\Core\AssetsPath\AlAssetsPath;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidOperationException;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\RuntimeException;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException;

/**
 * Implements the actions to manage the blocks on a slot's page
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlocksController extends Base\BaseController
{
    public function showAvailableBlocksAction()
    {
        return $this->container->get('templating')->renderResponse('RedKiteCmsBundle:Cms:AvailableBlocks/available_blocks.html.twig', array(
            'blocks' => $this->container->get('alpha_lemon_cms.block_manager_factory')->getBlocks()
        ));
    }

    public function addBlockAction()
    {
        $this->checkPageIsValid();
        
        $request = $this->container->get('request');
        $slotName = $request->get('slotName');  
        $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $blockRepository = $factoryRepository->createRepository('Block');
        
        if(null !== $request->get('included') && count($blockRepository->retrieveContentsBySlotName($slotName)) > 0 && filter_var($request->get('included'), FILTER_VALIDATE_BOOLEAN))
        {
            throw new InvalidOperationException('You can add just one block into an included block');
        }

        $contentType = ($request->get('contentType') != null) ? $request->get('contentType') : 'Text';
        $slotManager = $this->fetchSlotManager($request, false); 
        if (null !== $slotManager) {
            $res = $slotManager->addBlock($request->get('languageId'), $request->get('pageId'), $contentType, $request->get('idBlock'));
            if ( ! $res) {
                // @codeCoverageIgnoreStart
                throw new RuntimeException('The block has not been added because an unespected error has occoured when saving');
                // @codeCoverageIgnoreEnd
            }

            $template = 'RedKiteCmsBundle:Cms:render_block.html.twig';
            $blockManager = $slotManager->lastAdded();
        } else {
            if ( ! $request->get('included')) {
                throw new RuntimeException('You are trying to manage a block on a slot that does not exist on this page, or the slot name is empty');
            }
            $template = 'RedKiteCmsBundle:Cms:render_included_block.html.twig';

            $blockManagerFactory = $this->container->get('alpha_lemon_cms.block_manager_factory');
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

        $cmsLanguage = $this->container->get('alpha_lemon_cms.configuration')->read('language');
        $message = $this->translate('The block has been successfully added'); 

        $idBlock = (null !== $request->get('idBlock')) ? $request->get('idBlock') : 0;
        $values = array(
            array(
                "key" => "message",
                "value" => $message
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
        if (null !== $request->get('options') && is_array($request->get('options'))) {
            $values = array_merge($values, $request->get('options'));
        }

        $result = $slotManager->editBlock($request->get('idBlock'), $values);
        if (false === $result) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('The block has not been edited because an unespected error has occoured when saving');
            // @codeCoverageIgnoreEnd
        }

        if (null === $result) {
            throw new RuntimeException('It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore: nothing has been made');
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
            $template = ($request->get('included')) ? 'RedKiteCmsBundle:Cms:render_included_block.html.twig' :  'RedKiteCmsBundle:Cms:render_block.html.twig';
            $values = array(
                array("key" => "message", "value" => $this->translate("The block has been successfully edited")),
                array("key" => "edit-block",
                      "blockName" => "block_" . $blockManager->get()->getId(),
                      "value" => $this->container->get('templating')->render($template, array("blockManager" => $blockManager)),
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
            $cmsLanguage = $this->container->get('alpha_lemon_cms.configuration')->read('language');
            $message = ($res) 
            ? 
                $this->translate('The block has been successfully removed')
            : 
                $this->translate('The block has not been removed')
            ;

            $values = array();
            if($message != null) $values[] = array("key" => "message", "value" => $message);

            if ($slotManager->length() > 0) {
                $values[] = array(
                    "key" => "remove-block",
                    "blockName" => "block_" . $request->get('idBlock')
                );
            } else {
                $values[] = array(
                    "key" => "redraw-slot",
                    "slotName" => $request->get('slotName'),
                    "blockId" => 'block_' . $request->get('idBlock'),
                    "value" => $this->container->get('templating')->render('RedKiteCmsBundle:Cms:slot_contents.html.twig', array("slotName" => $request->get('slotName'), "included" => filter_var($request->get('included'), FILTER_VALIDATE_BOOLEAN)))
                );
            }

            return $this->buildJSonResponse($values);
        } else {
            throw new RuntimeException('The block you tried to remove does not exist anymore in the website');
        }
    }
    
    protected function buildJSonResponse($values)
    {
        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function checkPageIsValid()
    {
        $pageTree = $this->container->get('alpha_lemon_cms.page_tree');
        if (!$pageTree->isValid()) {
            throw new RuntimeException("The page you are trying to edit does not exist");
        }
    }

    private function fetchSlotManager(Request $request = null, $throwExceptionWhenNull = true)
    {
        if (null === $request) {
            $request = $this->container->get('request');
        }
        
        $slotManager = $this->container->get('alpha_lemon_cms.template_manager')->getSlotManager($request->get('slotName'));
        if ($throwExceptionWhenNull && null === $slotManager) {
            throw new RuntimeException("You are trying to manage a block on a slot that does not exist on this page, or the slot name is empty");
        }

        return $slotManager;
    }

    private function getSectionFromKeyParam()
    {
        return str_replace('External', '', $this->container->get('request')->get('field'));
    }
    
    
    /**
     * @deprecated since 1.1.0
     * @codeCoverageIgnore
     */
    public function showBlocksEditorAction()
    {
        try {
            $request = $this->container->get('request');
            $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
            $blockRepository = $factoryRepository->createRepository('Block');
            $block = $blockRepository->fromPK($request->get('idBlock'));
            if ($block != null) {
                $alBlockManager = $this->container->get('alpha_lemon_cms.block_manager_factory')->createBlockManager($block);
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
                $cmsLanguage = $this->container->get('alpha_lemon_cms.configuration')->read('language');
                
                throw new RuntimeException('The block does not exist anymore or the slot has any block inside');
            }
        } catch (\Exception $e) {
            return $this->renderDialogMessage($e->getMessage());
        }
    }
}
