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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Controller;

/**
 * AlDeployControllerProductionTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlDeployControllerStageTest extends BaseDeployControllerTest
{
    protected $deployerServiceName = 'alpha_lemon_cms.stage_deployer';
    
    protected function executeAction()
    {
        return $this->controller->stageAction();
    }
}