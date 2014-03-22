<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\UrlManager;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\UrlManager\UrlManagerStage;
use Symfony\Component\HttpKernel\KernelInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface;

/**
 * UrlManagerStageTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class UrlManagerStageTest extends BaseUrlManager
{
    protected $routePrefix = '_stage';
    
    protected function getUrlManager(KernelInterface $kernel, FactoryRepositoryInterface $factoryRepository) {
        return new UrlManagerStage($kernel, $factoryRepository);
    }
}
