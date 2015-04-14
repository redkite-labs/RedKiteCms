<?php
/**
 * Created by PhpStorm.
 * User: alphalemon
 * Date: 13/04/15
 * Time: 5.24
 */

namespace RedKiteCms\Action\Block;


use RedKiteCms\Action\BaseAction;
use RedKiteCms\Content\BlockManager\BlockManagerAdd;
use RedKiteCms\Content\BlockManager\BlockManagerMove;
use RedKiteCms\Content\BlockManager\BlockManagerRemove;
use Silex\Application;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoveBlockAction extends BaseAction
{
    public function execute(array $options, $username)
    {
        $data = $options["data"];
        $moveOptions = array(
            'page' => $data['page'],
            'language' => $data['language'],
            'country' => $data['country'],
            'sourceSlot' => $data['sourceSlot'],
            'blockname' => $data['name'],
            'position' => $data['position'],
        );

        if (null !== $data['targetSlot']) {
            $moveOptions['targetSlot'] = $data['targetSlot'];
        }


        $blockManager = new BlockManagerMove($this->app["jms.serializer"], $this->app["red_kite_cms.block_factory"], new OptionsResolver());

        return $blockManager->move($this->app["red_kite_cms.configuration_handler"]->siteDir(), $moveOptions, $username);
    }
}