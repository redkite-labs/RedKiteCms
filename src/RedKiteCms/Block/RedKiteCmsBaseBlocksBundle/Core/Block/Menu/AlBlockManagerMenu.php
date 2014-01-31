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

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockCollection;

/**
 * AlBlockManagerMenu handles a menu block
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockManagerMenu extends AlBlockManagerJsonBlockCollection
{
    protected $blocksTemplate = 'RedKiteCmsBaseBlocksBundle:Content:Menu/menu.html.twig';

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
        $items = $this->decodeJsonContent($this->alBlock->getContent());
                
        return array('RenderView' => array(
            'view' => $this->blocksTemplate,
            'options' => array(
                'items' => $items,
                'blockOptions' => $this->blockExtraOptions(),
            ),
        ));
    }
    
    private function getActivePage()
    {
        $permalink = "";
        $pageTree = $this->container->get('red_kite_cms.page_tree');
        $seo = $pageTree->getAlSeo();
        if (null !== $seo) {
            $permalink = $seo->getPermalink();
        }
        
        return $permalink;
    }
}
