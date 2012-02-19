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
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlContentQuery;
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
            $alContent = AlContentQuery::create()->findPK($request->get('idBlock'));
            if ($alContent != null)
            {
                $editorSettingsParamName = sprintf('al_%s_editor_settings', strtolower($alContent->getClassName())); 
                $editorSettings = ($this->container->hasParameter($editorSettingsParamName)) ? $this->container->getParameter($editorSettingsParamName) : array();
                $controller = sprintf('Al%sBundle:Block:%s_editor.html.twig', $alContent->getClassName(), strtolower($alContent->getClassName()));
                
                
                $editor = $this->container->get('templating')->render($controller, array("alContent" => $alContent,
                                                                                         "jsFiles" => explode(",", $alContent->getExternalJavascript()),
                                                                                         "cssFiles" => explode(",", $alContent->getExternalStylesheet()),
                                                                                         "language" => $request->get('language'),
                                                                                         "page" => $request->get('page'),
                                                                                         "editor_settings" => $editorSettings));
                $values[] = array("key" => "editor",
                                  "value" => $editor);
                $response = $this->buildJSonResponse($values);
                
                $dispatcher = $this->container->get('event_dispatcher');
                if(null !== $dispatcher)
                {
                    $event = new Block\BlockEditorRenderedEvent($response, $alContent);
                    $dispatcher->dispatch(BlockEvents::BLOCK_EDITOR_RENDERED, $event);
                    $response = $event->getResponse();
                }
                
                return $response;
                //return $this->buildJSonResponse($values);
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

            return $this->buildJSonHeader($message, $request->get('slotName'));
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
            
            $editorContents = null;
            $value = urldecode($request->get('value'));
            if($request->get('fileName') != null)
            {
                $fileName = basename($request->get('fileName'));
                $files = explode(",", $value);                    
                if(in_array($fileName, $files))
                {
                    return $this->buildJSonResponse(array());
                }

                $files[] = $fileName;
                $value = implode(",", $files);
                
                $editorContents = $this->container->get('templating')->render('AlphaLemonCmsBundle:Block:external_files_renderer.html.twig', array("value" => $value, "files" => $files, 'section' => $this->getSectionFromKeyParam()));
            }
            
            $values = array($request->get('key') => $value); 
            $templateManager = new AlTemplateManager($this->container);
            $slotManager = $templateManager->getSlotManager($request->get('slotName'));
            $res = $slotManager->editBlock($request->get('idBlock'), $values);            
            if(null !== $res)
            {
                $message = ($res) ? $this->get('translator')->trans('The content has successfully edited') : $this->get('translator')->trans('The content has not edited');
                return $this->buildJSonHeader($message, $request->get('slotName'), $editorContents);
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
        
    public function removeExternalFileAction()
    {
        try
        {
            $request = $this->get('request');
            $alContent = AlContentQuery::create()->findPK($request->get('idBlock'));  
            if ($alContent != null)
            {
                $editorContents = null;
                $assetsBaseDir = AlToolkit::locateResource($this->container, $this->container->getParameter('al.deploy_bundle_assets_base_dir'));
                if($request->get('key') == 'ExternalJavascript')
                {
                    $externalFiles =  $alContent->getExternalJavascript();
                    $assetsBaseDir .= '/' . $this->container->getParameter('al.deploy_bundle_js_folder');
                }
                else
                {
                    $externalFiles =  $alContent->getExternalStylesheet();
                    $assetsBaseDir .= '/' . $this->container->getParameter('al.deploy_bundle_css_folder');
                }
                
                if($request->get('fileName') != null || $externalFiles == "")
                {
                    $files = array_flip(explode(",", $externalFiles));
                    unset($files[$request->get('fileName')]);  
                    $files = array_flip($files);
                    $value = implode(",", $files);
                    
                    $values = array($request->get('key') => $value); 
                    $alBlockManager = AlBlockManagerFactory::createBlock($this->container, $alContent, $request->get('slotName'));
                    $res = $alBlockManager->save($values); 
                                        
                    $assetsBaseDir = AlToolkit::locateResource($this->container, $this->container->getParameter('al.deploy_bundle_assets_base_dir'));
                    $editorContents = $this->container->get('templating')->render('AlphaLemonCmsBundle:Block:external_files_renderer.html.twig', array("value" => $value, "files" => $files, 'section' => $this->getSectionFromKeyParam()));
                }
                else
                {
                    return $this->buildJSonResponse(array());;
                }
                
                $message = ($res) ? $this->get('translator')->trans('The content has successfully edited') : $this->get('translator')->trans('The content has not edited');

                
                return $this->buildJSonHeader(null, $alContent->getSlotName(), $editorContents);
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
                return $this->buildJSonHeader($message, $request->get('slotName'));
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

    protected function buildJSonHeader($message = null, $slotName = null, $editorContents = null)
    {
        $values = array();
        if($message != null) $values[] = array("key" => "message", "value" => $message);
        
        
        $slotManager = new AlSlotManager($this->container, new AlSlot($slotName)); 
        $this->get('al_page_tree')->setContents(array($slotName => $slotManager->toArray()), true);
        $values[] = array("key" => "contents", 
                          "slotName" => $slotName,
                          "value" => $this->container->get('templating')->render('AlphaLemonCmsBundle:Cms:slot_contents.html.twig', array("slotName" => $slotName, 'contents' => $this->get('al_page_tree')->getContents($slotName))));
        
        if($editorContents != null) $values[] = array("key" => "editorContents", "value" => $editorContents, "section" => $this->getSectionFromKeyParam());

        return $this->buildJSonResponse($values);
    }

    protected function buildJSonResponse($values)
    {
        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    private function getSectionFromKeyParam()
    {
        return str_replace('External', '', $this->get('request')->get('key'));
    }
}

