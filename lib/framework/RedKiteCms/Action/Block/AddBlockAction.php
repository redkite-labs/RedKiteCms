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
use Silex\Application;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddBlockAction extends BaseAction
{
    public function execute(array $options, $username)
    {
        $data = $options["data"];
        $addOptions = array(
            'page' => $data['page'],
            'language' => $data['language'],
            'country' => $data['country'],
            'slot' => $data['slot'],
            'blockname' => $data['name'],
            'direction' => $data['direction'],
            'type' => $data['type'],
            'position' => $data['position'],
        );

        $blockManager = new BlockManagerAdd($this->app["jms.serializer"], $this->app["red_kite_cms.block_factory"], new OptionsResolver());

        return $blockManager->add($this->app["red_kite_cms.configuration_handler"]->siteDir(), $addOptions, $username);
    }
}