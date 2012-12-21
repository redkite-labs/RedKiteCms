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
 * 
 * @api
 */
abstract class AlTwigDeployer extends AlDeployer
{
    protected $urlManager;
    protected $blockManagerFactory;

    /**
     * Constructor
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * 
     * @api
     */
    public function  __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->urlManager = $this->container->get('alpha_lemon_cms.url_manager');
        $this->blockManagerFactory = $this->container->get('alpha_lemon_cms.block_manager_factory');
        $this->viewsDir = $this->deployBundleAsset->getRealPath() . '/' . $this->container->getParameter('alpha_lemon_cms.deploy_bundle.views_dir') . '/' . $this->deployFolder;
    }

    /**
     * {@inheritdoc}
     */
    protected function checkTargetFolders()
    {
        parent::checkTargetFolders();

        $this->fileSystem->mkdir($this->viewsDir);
    }

    /**
     * @inheritDoc
     */
    protected function save(AlPageTree $pageTree)
    {
        $viewsRenderer = $this->container->get('alpha_lemon_cms.view_renderer');
        $imagesPath = array(
            'backendPath' => $this->uploadAssetsAbsolutePath,
            'prodPath' => $this->deployBundleAsset->getAbsolutePath()
        );
        
        $twigTemplateWriter = new AlTwigTemplateWriter($pageTree, $this->blockManagerFactory, $this->urlManager, $viewsRenderer, $imagesPath);

        return $twigTemplateWriter->writeTemplate($this->viewsDir);
    }
}