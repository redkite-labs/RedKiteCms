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

use RedKiteCms\Content\BlockManager\BlockManagerRestore;
use RedKiteCms\FilesystemEntity\SlotParser;
use RedKiteCms\Tools\JsonTools;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RestoreBlockController is the object deputed to implement the action to restore a
 * block on a page
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Block
 */
abstract class RestoreBlockController extends BaseBlockController
{
    /**
     * Implements the action to restore a block
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restore(array $options)
    {
        $request = $options["request"];
        $serializer = $options["serializer"];
        $configurationHandler = $options["red_kite_cms_config"];

        $slotName = $request->get('slot');
        $blockName = $this->getBlockName($request);
        $editOptions = array(
            'page' => $request->get('page'),
            'language' => $request->get('language'),
            'country' => $request->get('country'),
            'slot' => $slotName,
            'blockname' => $blockName,
        );

        $blockManager = new BlockManagerRestore($serializer, $options["block_factory"], new OptionsResolver());
        $blockManager->restore(
            $configurationHandler->siteDir(),
            $editOptions,
            $options["username"],
            $request->get('archiveFile')
        );

        $slotParser = new SlotParser($serializer);
        $blocks = $slotParser->fetchArchivedBlocks($blockManager->getArchiveDir() . '/' . $blockName, $slotName);

        $blocks = array_map(
            function ($block) use ($serializer) {
                $serializedBlock = JsonTools::toJson($serializer, $block);

                return json_decode($serializedBlock, true);
            },
            $blocks
        );

        return $this->buildJSonResponse($blocks);
    }
}