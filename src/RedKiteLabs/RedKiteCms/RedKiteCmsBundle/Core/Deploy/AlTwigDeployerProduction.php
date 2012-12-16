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

/**
 * AlTwigDeployer extends the base deployer class to deploy the website for production
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 * 
 * @api
 */
class AlTwigDeployerProduction extends AlTwigDeployer
{
    
    /**
     * {@inheritdoc}
     */
    
    
    protected function getTemplatesFolder()
    {
        return $this->container->getParameter('alpha_lemon_theme_engine.deploy.templates_folder');
    }
    
    /**
     * {@inheritdoc}
     */    
    protected function getRoutesPrefix()
    {
        return '';
    }
}