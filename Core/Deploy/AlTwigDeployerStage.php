<?php

/*
 * This file is part of the RedKite CMS Application and it is distributed
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
use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TwigTemplateWriter\AlTwigTemplateWriterBase;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TwigTemplateWriter\AlTwigTemplateWriterPages;

/**
 * AlTwigDeployer extends the base deployer class to deploy the website for stage environment
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlTwigDeployerStage extends AlTwigDeployer
{
    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function  __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->assetsDir .= '/stage';
        $this->urlManager = $this->container->get('red_kite_cms.url_manager_stage');
    }

    /**
     * {@inheritdoc}
     */
    protected function getTemplatesFolder()
    {
        return $this->container->getParameter('red_kite_labs_theme_engine.deploy.stage_templates_folder');
    }

    /**
     * {@inheritdoc}
     */
    protected function getRoutesPrefix()
    {
        return 'stage';
    }

    /**
     * @inheritDoc
     */
    protected function save(AlPageTree $pageTree, $type)
    {
        $imagesPath = array(
            'backendPath' => $this->uploadAssetsAbsolutePath,
            'prodPath' => '/' . $this->deployBundleAsset->getAbsolutePath() . '/stage',
        );

        $credits = $this->credits;
        switch ($type) {
            case 'Base':
                $twigTemplateWriter = new AlTwigTemplateWriterBase(
                    $pageTree,
                    $this->blockManagerFactory,
                    $this->urlManager,
                    $this->viewsRenderer,
                    $imagesPath
                );
                break;
            case 'Pages':
                $credits = false;
                $twigTemplateWriter = new AlTwigTemplateWriterPages(
                    $pageTree,
                    $this->blockManagerFactory,
                    $this->urlManager,
                    $this->deployBundle,
                    $this->deployFolder,
                    $this->viewsRenderer,
                    $imagesPath
                );
                break;
        }

        return $twigTemplateWriter
            ->setCredits($credits)
            ->generateTemplate()
            ->writeTemplate($this->viewsDir)
        ;
    }
}
