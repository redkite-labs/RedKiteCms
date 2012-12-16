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

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * AlTwigDeployer extends the base deployer class to deploy the website for stage environment
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
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

        $this->urlManager = $this->container->get('alpha_lemon_cms.url_manager_stage');
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getTemplatesFolder()
    {
        return $this->container->getParameter('alpha_lemon_theme_engine.deploy.stage_templates_folder');
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getRoutesPrefix()
    {
        return 'stage';
    }
}