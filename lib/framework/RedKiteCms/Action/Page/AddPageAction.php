<?php
/**
 * Created by PhpStorm.
 * User: alphalemon
 * Date: 13/04/15
 * Time: 5.24
 */

namespace RedKiteCms\Action\Page;


use RedKiteCms\Action\BaseAction;
use RedKiteCms\Content\BlockManager\BlockManagerAdd;
use RedKiteCms\Content\Page\PageManager;
use Silex\Application;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPageAction extends BaseAction
{
    public function execute(array $options, $username)
    {
        $values = $options["data"];
        $pageManager = $this->app["red_kite_cms.page_collection_manager"];
        $pageManager
            ->contributor($username)
            ->add($this->app["red_kite_cms.theme"], $values)
        ;
    }
}