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

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\Page\PagesForm;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\PageAttributes\PageAttributesForm;
use Symfony\Component\HttpFoundation\Response;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory;
use Symfony\Component\Finder\Finder;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use AlphaLemon\AlValumUploaderBundle\Core\Options\AlValumUploaderOptionsBuilder;

use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\BlockEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Actions\Block;

/**
 * Implements the actions to manage the blocks on a slot's page
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class BlocksController extends Controller
{
    public function showExternalFilesManagerAction()
    {
        return $this->render(sprintf('AlphaLemonCmsBundle:Block:%s_media_library.html.twig', $this->getRequest()->get('key')));
    }

    public function showBlocksEditorAction()
    {
        try
        {
            $request = $this->getRequest();
            $block = AlBlockQuery::create()->findPK($request->get('idBlock'));
            if ($block != null)
            {
                $editorSettingsParamName = sprintf('%s_editor_settings', strtolower($block->getClassName())); 
                $editorSettings = ($this->container->hasParameter($editorSettingsParamName)) ? $this->container->getParameter($editorSettingsParamName) : array();
                $template = sprintf('%sBundle:Block:%s_editor.html.twig', $block->getClassName(), strtolower($block->getClassName()));
                
                $alBlockManager = AlBlockManagerFactory::createBlock($this->container, $block);      
                
                $dispatcher = $this->container->get('event_dispatcher');
                if(null !== $dispatcher)
                {
                    $event = new Block\BlockEditorRenderingEvent($this->container, $request, $alBlockManager);
                    $dispatcher->dispatch(BlockEvents::BLOCK_EDITOR_RENDERING, $event);
                    $editor = $event->getEditor();
                }
                
                if(null === $editor)
                {
                    $editor = $this->container->get('templating')->render($template, array("alContent" => $alBlockManager,
                                                                                           "jsFiles" => explode(",", $block->getExternalJavascript()),
                                                                                           "cssFiles" => explode(",", $block->getExternalStylesheet()),
                                                                                           "language" => $request->get('language'),
                                                                                           "page" => $request->get('page'),
                                                                                           "editor_settings" => $editorSettings));
                }
                
                $values[] = array("key" => "editor",
                                  "value" => $editor);
                $response = $this->buildJSonResponse($values);
                
                if(null !== $dispatcher)
                {
                    $event = new Block\BlockEditorRenderedEvent($response, $alBlockManager);
                    $dispatcher->dispatch(BlockEvents::BLOCK_EDITOR_RENDERED, $event);
                    $response = $event->getResponse();
                }
                
                return $response;
            }
            else
            {
                throw new \RuntimeException($this->container->get('translator')->trans('The content does not exist anymore or the slot has any content inside'));
            }
        }
        catch(\Exception $e)
        {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    public function addBlockAction()
    {
        try
        {
            $request = $this->get('request');
            $contentType = ($request->get('contentType') != null) ? $request->get('contentType') : 'Text';
            
            $templateManager = new AlTemplateManager($this->container);
            $slotManager = $templateManager->getSlotManager($request->get('slotName')); 
            $res = $slotManager->addBlock($contentType, $request->get('idBlock'));
            
            $message = ($res) ? $this->get('translator')->trans('The content has been successfully added') : $this->get('translator')->trans('The content has not been added');
           
            $values = array();
            if($message != null) $values[] = array("key" => "message", "value" => $message);
        
            $values[] = array("key" => "add-block",
                              "insertAfter" => "block_" . $request->get('idBlock'),
                              "slotName" => 'al_' . $request->get('slotName'),
                              "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Cms:render_block.html.twig', array("add" => "AA", "block" => $slotManager->lastAdded()->toArray())));
        
            return $this->buildJSonResponse($values);
        }
        catch(\Exception $e)
        {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    public function editBlockAction()
    {
        try
        {
            $request = $this->get('request');
            
            $value = urldecode($request->get('value'));            
            $values = array($request->get('key') => $value); 
            if(null !== $request->get('options') && is_array($request->get('options'))) $values = array_merge($values, $request->get('options'));
            
            $templateManager = new AlTemplateManager($this->container);
            $slotManager = $templateManager->getSlotManager($request->get('slotName'));
            
            $blockManager = $slotManager->getContentManager($request->get('idBlock'));
            $res = $slotManager->editBlock($request->get('idBlock'), $values);            
            if(null !== $res)
            {
                $message = ($res) ? $this->get('translator')->trans('The content has successfully edited') : $this->get('translator')->trans('The content has not edited');
                $blockManager = $slotManager->getContentManager($request->get('idBlock'));
                
                $dispatcher = $this->container->get('event_dispatcher');
                if(null !== $dispatcher)
                {
                    $event = new Block\BlockEditedEvent($request, $blockManager);
                    $dispatcher->dispatch(BlockEvents::BLOCK_EDITED, $event);
                    $response = $event->getResponse();
                    $blockManager = $event->getBlockManager();
                }
                
                if(null === $response) {
                    $values = array();
                    if($message != null) $values[] = array("key" => "message", "value" => $message);

                    $values[] = array("key" => "edit-block",
                                      "blockName" => "block_" . $blockManager->get()->getId(),
                                      "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Cms:render_block.html.twig', array("block" => $blockManager->toArray())));
                }
                
                return $this->buildJSonResponse($values);
            }
            else 
            {
                throw new \RuntimeException($this->container->get('translator')->trans('The content you tried to edit does not exist anymore in the website'));                
            }
        }
        catch(\Exception $e)
        {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
        }
    }
    
    public function deleteBlockAction()
    {
        try
        {
            $request = $this->get('request');
            
            $templateManager = new AlTemplateManager($this->container);            
            $slotManager = $templateManager->getSlotManager($request->get('slotName'));
            $res = $slotManager->deleteBlock($request->get('idBlock'));
            if(null !== $res)
            {
                $message = ($res) ? $this->get('translator')->trans('The content has been successfully removed') : $this->get('translator')->trans('The content has not been removed');
                
                $values = array();
                if($message != null) $values[] = array("key" => "message", "value" => $message);

                if($slotManager->length() > 0) {
                    $values[] = array("key" => "remove-block",
                                  "blockName" => "block_" . $request->get('idBlock'));
                }
                else {
                    $this->get('al_page_tree')->setContents(array($request->get('slotName') => array()), true); 
                    $values[] = array("key" => "redraw-slot", 
                          "slotName" => 'al_' . $request->get('slotName'),
                          "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Cms:slot_contents.html.twig', array("slotName" => $request->get('slotName'))));
                }
                 
                return $this->buildJSonResponse($values);
            }
            else 
            {
                throw new \RuntimeException($this->container->get('translator')->trans('The content you tried to remove does not exist anymore in the website'));                
            }
        }
        catch(\Exception $e)
        {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    public function addExternalFileAction()
    {
        try
        {
            $request = $this->get('request');
            
            $file = urldecode($request->get('file'));  
            if(null === $file || $file == '')
            {
                throw new \Exception("Any file has been selected");
            }
            
            $file = preg_replace('/^[\w]+\//', '', $file);
            $field = $request->get('field');
            $values = array($field => $file); 

            $templateManager = new AlTemplateManager($this->container);
            $slotManager = $templateManager->getSlotManager($request->get('slotName'));
            $alBlock = $slotManager->getContentManager($request->get('idBlock'))->get();

            $arrayBlock = $alBlock->toArray();

            $savedValues = array_intersect_key($arrayBlock, $values);
            $savedValues = explode(',', $savedValues[$field]);
            if(in_array($file, $savedValues))
            {
                throw new \Exception("The file is already added");
            }
            $savedValues[] = $file;
            $values[$field] = implode(',', $savedValues);

            $res = $slotManager->editBlock($request->get('idBlock'), $values);   

            $section = $this->getSectionFromKeyParam();
            $template = $this->container->get('templating')->render('AlphaLemonCmsBundle:Block:external_files_renderer.html.twig', array("value" => $file, "files" => $savedValues, 'section' => $section));
            
            return $this->buildJSonResponse(array("key" => "externalAssets", "value" => $template, "section" => $section));
        }
        catch(\Exception $e)
        {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
        }
    }
        
    public function removeExternalFileAction()
    {
        try
        {
            $request = $this->get('request');
            $alBlock = AlBlockQuery::create()->findPK($request->get('idBlock'));  
            if ($alBlock != null)
            {
                $editorContents = null;
                
                $field = $request->get('field');
                $externalFiles =  $alBlock->{'get' . $field}();
                
                if($request->get('file') != null || $externalFiles == "")
                {
                    $bundleFolder = AlToolkit::retrieveBundleWebFolder($this->container, 'AlphaLemonCmsBundle');
                    $filePath = $this->container->getParameter('kernel.root_dir') . '/../' . $this->container->getParameter('alcms.web_folder_name') . '/' . $bundleFolder . '/' . $this->container->getParameter('alcms.upload_assets_dir') . '/' . $this->container->getParameter('al.deploy_bundle_js_folder') . '/';
                    $file = $filePath . $request->get('file');
                    
                    @unlink($file);
                    $files = array_flip(explode(",", $externalFiles));                    
                    unset($files[$request->get('file')]);  
                    $files = array_flip($files);
                    $value = implode(",", $files);
                    
                    $values = array($field => $value); 
                    $alBlockManager = AlBlockManagerFactory::createBlock($this->container, $alBlock);
                    $res = $alBlockManager->save($values); 
                            
                    $section = $this->getSectionFromKeyParam();
                    $template = $this->container->get('templating')->render('AlphaLemonCmsBundle:Block:external_files_renderer.html.twig', array("value" => $value, "files" => $files, 'section' => $section));
                    return $this->buildJSonResponse(array("key" => "externalAssets", "value" => $template, 'section' => $section));
                }
                
                return $this->buildJSonResponse(array("key" => "message", "value" => "Any file has been selected"));
            }
            else
            {
                throw new \RuntimeException($this->container->get('translator')->trans('The content you tried to edit does not exist anymore in the website')); 
            }
        }
        catch(\Exception $e)
        {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    protected function buildJSonResponse($values)
    {
        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    private function getSectionFromKeyParam()
    {
        return str_replace('External', '', $this->get('request')->get('field'));
    }
}

