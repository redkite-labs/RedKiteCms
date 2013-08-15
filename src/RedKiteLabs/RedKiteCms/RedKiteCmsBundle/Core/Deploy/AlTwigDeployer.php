<?php

/*
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Deploy;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * AlTwigDeployer extends the base deployer class to save the PageTree as a twig template
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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

        $this->urlManager = $this->container->get('red_kite_cms.url_manager');
        $this->blockManagerFactory = $this->container->get('red_kite_cms.block_manager_factory');
        $this->viewsDir = $this->deployBundleAsset->getRealPath() . '/' . $this->container->getParameter('red_kite_cms.deploy_bundle.views_dir') . '/' . $this->deployFolder;
    }

    /**
     * {@inheritdoc}
     */
    protected function checkTargetFolders()
    {
        parent::checkTargetFolders();

        $this->fileSystem->mkdir($this->viewsDir);
    }
}
