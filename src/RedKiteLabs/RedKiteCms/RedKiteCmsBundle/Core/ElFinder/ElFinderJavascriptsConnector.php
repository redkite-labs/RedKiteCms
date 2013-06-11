<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder;

use AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\Base\ElFinderBaseConnector;

/**
 * Configures the ElFinder library to manage javascripts files
 */
class ElFinderJavascriptsConnector extends ElFinderBaseConnector
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $javascripsFolder = $this->container->getParameter('alpha_lemon_cms.deploy_bundle.js_dir') ;

        return $this->generateOptions($javascripsFolder, 'Javascripts');
    }
}
