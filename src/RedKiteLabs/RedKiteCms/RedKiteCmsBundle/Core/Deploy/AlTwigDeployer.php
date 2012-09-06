<?php

/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Deploy;

use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\TwigTemplateWriter\AlTwigTemplateWriter;

/**
 * AlTwigDeployer extends the base deployer class to save the PageTree as a twig template
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlTwigDeployer extends AlDeployer
{
    private $urlManager;
    private $blockManagerFactory;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function  __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->urlManager = $container->get('alphalemon_cms.url_manager');
        $this->blockManagerFactory = $this->container->get('alphalemon_cms.block_manager_factory');
        $this->viewsDir = $this->deployBundleAsset->getRealPath() . '/' . $this->container->getParameter('alphalemon_cms.deploy_bundle.views_dir');
    }

    /**
     * {@inheritdoc}
     */
    protected function checkTargetFolders()
    {
        parent::checkTargetFolders();

        $this->checkFolder($this->viewsDir);
    }

    /**
     * @inheritDoc
     */
    protected function save(AlPageTree $pageTree)
    {
        $imagesPath = array(
            'backendPath' => $this->alphaLemonCmsBundleAsset->getAbsolutePath() . '/' . $this->uploadAssetsDir,
            'prodPath' => $this->deployBundleAsset->getAbsolutePath()
        );
        $twigTemplateWriter = new AlTwigTemplateWriter($pageTree, $this->blockManagerFactory, $this->urlManager, $imagesPath);

        return $twigTemplateWriter->writeTemplate($this->viewsDir);
    }
}
