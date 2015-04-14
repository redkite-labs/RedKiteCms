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
use RedKiteCms\Content\BlockManager\BlockManagerRemove;
use Silex\Application;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemoveBlockAction extends BaseAction
{
    public function execute(array $options, $username)
    {
        $data = $options["data"];
        $removeOptions = array(
            'page' => $data['page'],
            'language' => $data['language'],
            'country' => $data['country'],
            'slot' => $data['slot'],
            'blockname' => $data['name'],
        );

        $blockManager = new BlockManagerRemove($this->app["jms.serializer"], $this->app["red_kite_cms.block_factory"], new OptionsResolver());

        return $blockManager->remove($this->app["red_kite_cms.configuration_handler"]->siteDir(), $removeOptions, $username);
    }
}