<?php
/**
 * This file is part of the RedKiteCmsBaseBlocksBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Menu;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock\BlockManagerJsonBlockCollection;

/**
 * BlockManagerMenu handles a menu block
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlockManagerMenu extends BlockManagerJsonBlockCollection
{
    protected $blocksTemplate = 'RedKiteCmsBaseBlocksBundle:Content:Menu/menu.html.twig';
    protected $listClass = "nav nav-pills";

    /**
     * Defines the App-Block's default value
     *
     * @return array
     */
    public function getDefaultValue()
    {
        $value = '
            {
                "0": {
                    "blockType" : "Link"
                },
                "1": {
                    "blockType" : "Link"
                }
            }';

        return array("Content" => $value);
    }
    
    /**
     * {@inheritdoc}
     */
    public function blockExtraOptions()
    {
        return array(                    
            'active_page' => $this->getActivePage(),
            'wrapper_tag' => 'li',
        );
    }

    /**
     * Renders the App-Block's content view
     *
     * @return string|array
     */
    protected function renderHtml()
    {
        $menuLinks = array();
        $extraOptions = $this->blockExtraOptions();
        $factoryRepository = $this->container->get('red_kite_cms.factory_repository');
        $urlManager = $this->container->get('red_kite_cms.url_manager');
        $blocksRepository = $factoryRepository->createRepository('Block');
        $links = $blocksRepository->retrieveContentsBySlotName($this->alBlock->getId() . "%");
        foreach($links as $link) {
            $content = json_decode($link->getContent(), true);
            $url = $content[0]["href"];
            $value = $content[0]["value"];
            
            $twigCode = "";
            $noLinkCode = sprintf("<li><a href=\"%s\">%s</a></li>", $url, $value);
            $urlManager->fromUrl($url);
            $productionRoute = $urlManager->getProductionRoute();
            if (null !== $productionRoute) {
                $twigCode = sprintf("{%% if path('%s') == app.request.getBaseUrl ~ app.request.getPathInfo %%}class=\"active\"{%% endif %%}", $productionRoute);
                $noLinkCode = sprintf("<li {%% if path('%s') == app.request.getBaseUrl ~ app.request.getPathInfo %%}<li><span>%s</span></li>{%% else %%}%s{%% endif %%}", $productionRoute, $value, $noLinkCode);
            }
            
            $menuLinks[] = (array_key_exists("no_link_when_active", $extraOptions) && $extraOptions["no_link_when_active"]) 
                    ? 
                $noLinkCode
                    : 
                sprintf('<li %s><a href="%s">%s</a></li>', $twigCode, $url, $value)
            ;
        }
        
        return sprintf('<ol class="%s">%s</ol>', $this->listClass, implode("\n", $menuLinks));
    }
    
    /**
     * Implements a method to let the derived class override it to format the content
     * to display when the Cms is active
     *
     * @return string|null
     */
    protected function replaceHtmlCmsActive()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => $this->blocksTemplate,
            'options' => array(
                'items' => $items,
                'block_manager' => $this,
                'blockOptions' => $this->blockExtraOptions(),
            ),
        ));
    }
    
    private function getActivePage()
    {
        $permalink = "";
        $pageTree = $this->container->get('red_kite_cms.page_tree');
        $seo = $pageTree->getSeo();
        if (null !== $seo) {
            $permalink = $seo->getPermalink();
        }
        
        return $permalink;
    }
}
