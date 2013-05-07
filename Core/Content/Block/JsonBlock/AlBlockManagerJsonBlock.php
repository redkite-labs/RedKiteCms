<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;

/**
 * AlBlockManagerJson is the base object deputated to handle a json content
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
abstract class AlBlockManagerJsonBlock extends AlBlockManager
{
    /**
     * Decodes a json content
     *
     * @param  string|AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock $block
     * @param  boolean  $assoc
     * @return array|object depends on assoc param
     * @throws Exception\InvalidJsonFormatException
     * 
     * @api
     */
    public static function decodeJsonContent($block, $assoc = true)
    {
        $content = $block;
        $blockType = null;
        if (is_object($block)) {
            $content = $block->getContent();
            $blockType = $block->getType();
        }
        
        $content = json_decode($content, $assoc);
        if (null === $content) {
            $blockTypeInfo = (null !== $blockType) ? ' for the block ' . $blockType . ' ' : '';

            throw new Exception\InvalidJsonFormatException(sprintf('The content format %sis wrong. You should remove that block and add it again.', $blockTypeInfo));
        }

        return $content;
    }

    /**
     * {@inheritdoc}
     *
     * Extends the base edit method to manage a json content
     * 
     * @api
     */
    protected function edit(array $values)
    {
        if (array_key_exists('Content', $values)) {
            $unserializedData = array();
            $serializedData = $values['Content'];
            parse_str($serializedData, $unserializedData); 
            
            $commonMessageText = 'The best way to add a block which uses json to manage its data, is extending the form "AlphaLemon\AlphaLemonCmsBundle\Core\Form\JsonBlock\JsonBlockType" which already handles this configuration for you';
            if (!array_key_exists("al_json_block", $unserializedData)) {
                throw new Exception\InvalidFormConfigurationException('There is a configuration error in the form that manages this content: you must name that form "al_json_block". ' . $commonMessageText);
            }

            $item = $unserializedData["al_json_block"];

            $content = $this->decodeJsonContent($this->alBlock);
            $content[0] = $item;

            $values['Content'] = json_encode($content);
        }

        if (array_key_exists('RemoveItem', $values)) {
            $itemId = $values['RemoveItem'];
            $content = $this->decodeJsonContent($this->alBlock);
            $this->checkValidItemId($itemId, $content);
            unset($content[$itemId]);
            $content = array_values($content);

            $values['Content'] = json_encode($content);
        }
        
        return parent::edit($values);
    }

    private function checkValidItemId($itemId, $content)
    {
        if (!array_key_exists($itemId, $content)) {
            throw new Exception\InvalidItemException('It seems that the item requested does not exist anymore');
        }
    }
}
