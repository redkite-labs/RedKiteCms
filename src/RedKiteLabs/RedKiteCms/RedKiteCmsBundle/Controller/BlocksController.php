<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
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

namespace AlphaLemon\AlphaLemonCmsBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use AlphaLemon\ThemeEngineBundle\Core\Asset\AlAsset;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\BlockEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\Block;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Implements the actions to manage the blocks on a slot's page
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class BlocksController extends ContainerAware
{
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
                    $editorSettingsParamName = sprintf('%s_editor_settings', strtolower($block->getClassName()));
                    $editorSettings = ($this->container->hasParameter($editorSettingsParamName)) ? $this->container->getParameter($editorSettingsParamName) : array();
                    $template = sprintf('%sBundle:Block:%s_editor.html.twig', $block->getClassName(), strtolower($block->getClassName()));

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
                throw new \RuntimeException($this->container->get('translator')->trans('The content does not exist anymore or the slot has any content inside'));
            }
        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode('404');

            return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    public function addBlockAction()
    {
        try {
            $this->checkPageIsValid();

            $request = $this->container->get('request');
            $slotManager = $this->fetchSlotManager($request);

            $contentType = ($request->get('contentType') != null) ? $request->get('contentType') : 'Text';
            $res = $slotManager->addBlock($request->get('languageId'), $request->get('pageId'), $contentType, $request->get('idBlock'));
            if (!$res) {
                throw new \RuntimeException('The content has not been added because something goes wrong during the operation');
            }

            $idBlock = (null !== $request->get('idBlock')) ? $request->get('idBlock') : 0;
            $values = array(
                array(
                    "key" => "message",
                    "value" => $this->container->get('translator')->trans('The content has been successfully added')
                ),
                array(
                    "key" => "add-block",
                    "insertAfter" => "block_" . $idBlock,
                    "slotName" => 'al_' . $request->get('slotName'),
                    "value" => $this->container->get('templating')->render(
                            'AlphaLemonCmsBundle:Cms:render_block.html.twig',
                            array("block" => $slotManager->lastAdded()->toArray(), 'add' => true)
                        )
                    )
                );

            return $this->buildJSonResponse($values);
        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode('404');

            return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    public function editBlockAction()
    {
        try {
            $this->checkPageIsValid();

            $request = $this->container->get('request');
            $slotManager = $this->fetchSlotManager($request);

            $value = urldecode($request->get('value'));
            $values = array($request->get('key') => $value);
            if(null !== $request->get('options') && is_array($request->get('options'))) $values = array_merge($values, $request->get('options'));
            $result = $slotManager->editBlock($request->get('idBlock'), $values);
            if (false === $result) {
                throw new \RuntimeException('The content has not been edited because something goes wrong during the operation');
            }

            if (null === $result) {
                throw new \RuntimeException('It seems that anything has changed with the values you entered or the block you tried to edit does not exist anymore. Nothing has been made');
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
                $values = array(
                    array("key" => "message", "value" => "The content has been successfully edited"),
                    array("key" => "edit-block",
                          "blockName" => "block_" . $blockManager->get()->getId(),
                          "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Cms:render_block.html.twig', array("block" => $blockManager->toArray()))));

                return $this->buildJSonResponse($values);
            }

            return $response;
        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode('404');

            return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    public function deleteBlockAction()
    {
        try {
            $this->checkPageIsValid();

            $request = $this->container->get('request');
            $slotManager = $this->fetchSlotManager($request);

            $res = $slotManager->deleteBlock($request->get('idBlock'));
            if (null !== $res) {
                $message = ($res) ? $this->container->get('translator')->trans('The content has been successfully removed') : $this->container->get('translator')->trans('The content has not been removed');

                $values = array();
                if($message != null) $values[] = array("key" => "message", "value" => $message);

                if ($slotManager->length() > 0) {
                    $values[] = array("key" => "remove-block",
                                  "blockName" => "block_" . $request->get('idBlock'));
                } else {
                    $values[] = array("key" => "redraw-slot",
                          "slotName" => 'al_' . $request->get('slotName'),
                          "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Cms:slot_contents.html.twig', array("slotName" => $request->get('slotName'))));
                }

                return $this->buildJSonResponse($values);
            } else {
                throw new \RuntimeException('The content you tried to remove does not exist anymore in the website');
            }
        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode('404');

            return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    public function showExternalFilesManagerAction()
    {
        try {
            $key = $this->container->get('request')->get('key');
            if (empty($key)) {
                throw new \RuntimeException($this->container->get('translator')->trans('The key param is mandatory to open the right file manager'));
            }

            return $this->container->get('templating')->renderResponse(sprintf('AlphaLemonCmsBundle:Block:%s_media_library.html.twig', $key), array('enable_yui_compressor' => $this->container->getParameter('alpha_lemon_cms.enable_yui_compressor')));
        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode('404');

            return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    public function addExternalFileAction()
    {
        try {
            $request = $this->container->get('request');

            $file = urldecode($request->get('file'));
            if (null === $file || $file == '') {
                throw new \Exception("External file cannot be added because any file has been given");
            }

            $field = urldecode($request->get('field'));
            if (null === $field || $field == '') {
                throw new \Exception("External file cannot be added because any valid field name has been given");
            }

            $slotManager = $this->fetchSlotManager($request);
            $blockManager = $slotManager->getBlockManager($request->get('idBlock'));
            if (null !== $blockManager) {
                $file = preg_replace('/^[\w]+\//', '', $file);
                $field = $request->get('field');
                $values = array($field => $file);
                $alBlock = $blockManager->get();

                $files = array();
                $externalFiles =  $alBlock->{'get' . $field}();
                if (!empty($externalFiles)) {
                    $externalFiles = explode(',', $externalFiles);
                    if (in_array($file, $externalFiles)) {
                        throw new \Exception("The block has already assigned the external file you are trying to add");
                    }
                    $files = $externalFiles;
                }
                $files[] = $file;
                $values[$field] = implode(',', $files);

                $res = $slotManager->editBlock($request->get('idBlock'), $values);
                if ($res) {
                    $section = $this->getSectionFromKeyParam();
                    $template = $this->container->get('templating')->render('AlphaLemonCmsBundle:Block:external_files_renderer.html.twig', array("value" => $file, "files" => $files, 'section' => $section));

                    return $this->buildJSonResponse(array("key" => "externalAssets", "value" => $template, "section" => $section));
                } else {
                    throw new \RuntimeException('Something goes wrong when saving the external file reference to database. Operation aborted');
                }
            } else {
                throw new \RuntimeException('You are trying to add an external file on a content that doesn\'t exist anymore');
            }
        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode('404');

            return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    public function removeExternalFileAction()
    {
        try {
            $request = $this->container->get('request');

            $file = urldecode($request->get('file'));
            if (null === $file || $file == '') {
                throw new \Exception("External file cannot be removed because any file has been given");
            }

            $field = urldecode($request->get('field'));
            if (null === $field || $field == '') {
                throw new \Exception("External file cannot be removed because any valid field name has been given");
            }

            $slotManager = $this->fetchSlotManager($request);
            $blockManager = $slotManager->getBlockManager($request->get('idBlock'));
            if (null !== $blockManager) {
                $field = $request->get('field');
                $externalFiles =  $blockManager->get()->{'get' . $field}();

                if ($request->get('file') != null) {
                    $asset = new AlAsset($this->container->get('kernel'), '@AlphaLemonCmsBundle');
                    $bundleFolder = $asset->getAbsolutePath();
                    $filePath = $this->container->getParameter('kernel.root_dir') . '/../' . $this->container->getParameter('alpha_lemon_cms.web_folder') . '/' . $bundleFolder . '/' . $this->container->getParameter('alpha_lemon_cms.upload_assets_dir') . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.js_dir') . '/';
                    $file = $filePath . $request->get('file');
                    @unlink($file);

                    if (!empty($externalFiles)) {
                        $files = array_flip(explode(",", $externalFiles));
                        if (array_key_exists($request->get('file'), $files)) {
                            unset($files[$request->get('file')]);
                            $files = array_flip($files);
                            $value = implode(",", $files);

                            $values = array($field => $value);
                            $result = $slotManager->editBlock($request->get('idBlock'), $values);
                            if ($result) {
                                $section = $this->getSectionFromKeyParam();
                                $template = $this->container->get('templating')->render('AlphaLemonCmsBundle:Block:external_files_renderer.html.twig', array("value" => $value, "files" => $files, 'section' => $section));

                                return $this->buildJSonResponse(array("key" => "externalAssets", "value" => $template, 'section' => $section));
                            } else {
                                throw new \RuntimeException('Something goes wrong when saving the external file reference to database. Operation aborted');
                            }
                        }
                    }

                    return $this->buildJSonResponse(array("key" => "message", "value" => "The file has been removed"));
                } else {
                    return $this->buildJSonResponse(array("key" => "message", "value" => "Any file has been selected"));
                }
            } else {
                throw new \RuntimeException('You are trying to delete an external file from a content that doesn\'t exist anymore');
            }
        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode('404');

            return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $e->getMessage()), $response);
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
            throw new \RuntimeException('The page you are trying to edit does not exist');
        }
    }

    private function fetchSlotManager(Request $request = null)
    {
        if(null === $request) $request = $this->container->get('request');

        $slotManager = $this->container->get('alpha_lemon_cms.template_manager')->getSlotManager($request->get('slotName'));
        if (null === $slotManager) {
            throw new \RuntimeException('You are trying to add a new block on a slot that does not exist on this page, or the slot name is empty');
        }

        return $slotManager;
    }

    private function getSectionFromKeyParam()
    {
        return str_replace('External', '', $this->container->get('request')->get('field'));
    }
}
