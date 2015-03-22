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

use RedKiteCms\Content\BlockManager\BlockManagerAdd;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddBlockController is the object deputed to implement the action to add a
 * block on a page
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Block
 */
abstract class AddBlockController extends BaseBlockController
{
    /**
     * Implements the action to add a block
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(array $options)
    {
        $request = $options["request"];
        $serializer = $options["serializer"];
        $configurationHandler = $options["red_kite_cms_config"];
        $addOptions = array(
            'page' => $request->get('page'),
            'language' => $request->get('language'),
            'country' => $request->get('country'),
            'slot' => $request->get('slot'),
            'blockname' => $request->get('name'),
            'direction' => $request->get('direction'),
            'type' => $request->get('type'),
            'position' => $request->get('position'),
        );

        $blockManager = new BlockManagerAdd($serializer, $options["block_factory"], new OptionsResolver());
        $result = $blockManager->add($configurationHandler->siteDir(), $addOptions, $options["username"]);

        return $this->buildJSonResponse($result);
    }
}