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
use RedKiteCms\Content\BlockManager\BlockManagerEdit;
use RedKiteCms\Content\BlockManager\BlockManagerRestore;
use Silex\Application;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestoreBlockAction extends BaseAction
{
    public function execute(array $options, $username)
    {
        $data = $options["data"];
        $restoreOptions = array(
            'page' => $data['page'],
            'language' => $data['language'],
            'country' => $data['country'],
            'slot' => $data['slot'],
            'blockname' => $data['name'],
        );

        $blockManager = new BlockManagerRestore($this->app["jms.serializer"], $this->app["red_kite_cms.block_factory"], new OptionsResolver());
        $blockManager->restore(
            $this->app["red_kite_cms.configuration_handler"]->siteDir(),
            $restoreOptions,
            $username,
            $data['archiveFile']
        );

        /*
        $data = $options["data"];
        $editOptions = array(
            'page' => $data['page'],
            'language' => $data['language'],
            'country' => $data['country'],
            'slot' => $data['slot'],
            'blockname' => $data['name'],
        );

        $blockManager = new BlockManagerEdit($this->app["jms.serializer"], $this->app["red_kite_cms.block_factory"], new OptionsResolver());
        $blockManager->edit(
            $this->app["red_kite_cms.configuration_handler"]->siteDir(),
            $editOptions,
            $username,
            $data['data']
        );*/
    }
}