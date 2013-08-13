<?php
/*
 * This file is part of the TwitterBootstrapBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright 
 * notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 * 
 * @license    MIT LICENSE
 * 
 */

namespace RedKiteCms\Block\TwitterBootstrapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlock;

/**
 * Renders the menu items editor
 */
class JstreeDropdownButtonController extends Controller
{
    /**
     * Displays the editor to manage the Dropdown button itmes
     */
    public function showAction()
    {
        $request = $this->container->get('request');
        
        $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $blocksRepository = $factoryRepository->createRepository('Block');
        $block = $blocksRepository->fromPk($request->get('idBlock'));
        
        $items = AlBlockManagerJsonBlock::decodeJsonContent($block->getContent());
        $item = $items[0];
        $attributes = $item["items"]; 
        
        $seoRepository = $factoryRepository->createRepository('Seo');
        
        $options = array(               
            'attributes' => $attributes,                 
            'jstree_nodes' => json_encode($attributes), 
            'attributes_form' => 'TwitterBootstrapBundle:Editor:DropdownButton/Jstree/_jstree_attribute.html.twig',                
            'pages' => ChoiceValues::getPermalinks($seoRepository, $request->get('languageId')),
        );
        
        return $this->container->get('templating')->renderResponse('JstreeBundle:Jstree:_jstree.html.twig', $options);
    }
}
