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
use RedKiteCms\Content\BlockManager\BlockManagerArchive;
use RedKiteCms\Content\BlockManager\BlockManagerEdit;
use Silex\Application;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArchiveBlockAction extends BaseAction
{
    public function execute(array $options, $username)
    {
        $data = $options["data"];
        $archiveOptions = array(
            'page' => $data['page'],
            'language' => $data['language'],
            'country' => $data['country'],
            'slot' => $data['slot'],
            'blockname' => $data['name'],
        );

        $blockManager = new BlockManagerArchive($this->app["jms.serializer"], $this->app["red_kite_cms.block_factory"], new OptionsResolver());
        $blockManager->archive(
            $this->app["red_kite_cms.configuration_handler"]->siteDir(),
            $archiveOptions,
            $username,
            $data['data']
        );
    }
}