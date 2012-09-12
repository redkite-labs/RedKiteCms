<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * JsonBlockController manages the elements of a block based on json
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class JsonBlockController extends ContainerAware
{
    /**
     * Lists the elements of a json object
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listJsonItemsAction()
    {
        try {
            $request = $this->container->get('request');
            $block = $this->fetchBlock($request->get('blockId'));

            $items = json_decode($block->getHtmlContent(), true);
            $template = sprintf('%sBundle:Block:%s_list.html.twig', $block->getClassName(), strtolower($block->getClassName()));

            return $this->container->get('templating')->renderResponse($template, array("items" => $items, "block_id" => $block->getId()));
        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode('404');

            return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    /**
     * Displays a form to manage an item of the json object
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showJsonItemAction()
    {
        try {
            $request = $this->container->get('request');
            $form = $this->setUpForm($request->get('blockId'), $request->get('itemId'));
            $formView = array('key' => 'editor', 'value' => $this->renderForm($request->get('blockId'), $form));

            return $this->buildJSonResponse(array($formView));
        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode('404');

            return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $e->getMessage()), $response);
        }
    }

    /**
     * Removes an item of the json object
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteJsonItemAction()
    {
        try {
            $request = $this->container->get('request');
            $block = $this->fetchBlock($request->get('blockId'));

            $blockManagerFactory = $this->container->get('alpha_lemon_cms.block_manager_factory');
            $blockManager = $blockManagerFactory->createBlockManager($block);
            $blockManager->save(array('RemoveItem' => $request->get('RemoveItem')));

            $items = json_decode($block->getHtmlContent(), true);
            $template = sprintf('%sBundle:Block:%s_list.html.twig', $block->getClassName(), strtolower($block->getClassName()));

            $responseValues = array(
                array('key' => 'content',
                    'id' => $request->get('blockId'),
                    'value' => $this->container->get('templating')->render('AlphaLemonCmsBundle:Cms:render_block.html.twig', array("block" => $blockManager->toArray()))),
                array('key' => 'list',
                    'value' => $this->container->get('templating')->render($template, array("items" => $items, "block_id" => $block->getId()))),
            );

            return $this->buildJSonResponse($responseValues);
        } catch (\Exception $e) {
            $response = new Response();
            $response->setStatusCode('404');

            return $this->container->get('templating')->renderResponse('AlphaLemonCmsBundle:Dialog:dialog.html.twig', array('message' => $e->getMessage()), $response);
        }
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
            $content = json_decode($block->getHtmlContent(), true);

            if (!array_key_exists($itemId, $content)) {
                throw new \InvalidArgumentException('It seems that the item requested does not exist anymore');
            }

            $item = $content[$itemId];
            $item['id'] = $itemId;
        }

        $formName = sprintf('%s.form', strtolower($block->getClassName()));
        $formClass = $this->container->get($formName);

        return $this->container->get('form.factory')->create($formClass, $item);
    }

    /**
     * Renders the form
     *
     * @param  int                                        $blockId
     * @param  Form                                       $form
     * @param  null|array                                 $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderForm($blockId, $form, $errors = null)
    {
        $block = $this->fetchBlock($blockId);
        $template = sprintf('%sBundle:Block:%s_item.html.twig', $block->getClassName(), strtolower($block->getClassName()));

        return $this->container->get('templating')->render($template, array(
            'block_id' => $blockId,
            'form' => $form->createView(),
            'errors' => $errors,
        ));
    }

    /**
     * Builds a json response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildJSonResponse(array $values)
    {
        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
