<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Rendering\Controller\Block;

use RedKiteCms\Content\BlockManager\BlockManagerRemove;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RemoveBlockController is the object deputed to implement the action to remove a
 * blocks from a page
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Block
 */
abstract class RemoveBlockController extends BaseBlockController
{
    /**
     * Implements the action to remove a block
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove(array $options)
    {
        $request = $options["request"];
        $serializer = $options["serializer"];
        $configurationHandler = $options["red_kite_cms_config"];
        $removeOptions = array(
            'page' => $request->get('page'),
            'language' => $request->get('language'),
            'country' => $request->get('country'),
            'slot' => $request->get('slot'),
            'blockname' => $this->getBlockName($request),
        );


        $blockManager = new BlockManagerRemove($serializer, $options["block_factory"], new OptionsResolver());
        $blockManager->remove($configurationHandler->siteDir(), $removeOptions, $options["username"]);
    }
}