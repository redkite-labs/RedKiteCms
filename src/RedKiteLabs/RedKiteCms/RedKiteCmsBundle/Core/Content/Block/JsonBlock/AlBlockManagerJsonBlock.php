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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock;

/**
 * AlBlockManagerJson is the base object deputated to handle a json content
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
abstract class AlBlockManagerJsonBlock extends AlBlockManagerJsonBase
{
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
            
            if ( ! array_key_exists("al_json_block", $unserializedData)) {
                $exception = array(
                    'message' => 'There is a configuration error in the form that manages this block: you must call that form "al_json_block". The best way to add a block which uses the json format to manage its data, is extending the form "RedKiteLabs\RedKiteCmsBundle\Core\Form\JsonBlock\JsonBlockType" which already handles this configuration for you',
                    'parameters' => array(
                        '%className%' => get_class($this),
                    ),
                );
                throw new Exception\InvalidFormConfigurationException(json_encode($exception));
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
        if ( ! array_key_exists($itemId, $content)) {
            $exception = array(
                'message' => 'It seems that the item requested does not exist anymore',
                'domain' => 'exceptions',
            );
                
            throw new Exception\InvalidItemException(json_encode($exception));
        }
    }
}
