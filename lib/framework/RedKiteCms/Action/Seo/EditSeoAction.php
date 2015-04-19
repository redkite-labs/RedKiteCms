<?php
/**
 * Created by PhpStorm.
 * User: alphalemon
 * Date: 13/04/15
 * Time: 5.24
 */

namespace RedKiteCms\Action\Seo;


use RedKiteCms\Action\BaseAction;
use RedKiteCms\Content\BlockManager\BlockManagerAdd;
use RedKiteCms\Content\Page\PageManager;
use Silex\Application;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditSeoAction extends BaseAction
{
    public function execute(array $options, $username)
    {
        $data = $options["data"];
        $pageName = $data["pageName"];
        $seoData = $data["seoData"];
        $pageManager = $this->app["red_kite_cms.page_manager"];
        $pageManager
            ->contributor($username)
            ->edit($pageName, $seoData)
        ;
    }
}