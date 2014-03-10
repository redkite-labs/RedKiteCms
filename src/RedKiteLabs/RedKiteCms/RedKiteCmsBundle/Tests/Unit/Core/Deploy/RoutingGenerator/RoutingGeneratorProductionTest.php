<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Deploy\RoutingGenerator;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\RoutingGenerator\RoutingGeneratorProduction;

/**
 * Description of RoutingGeneratorProductionTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class RoutingGeneratorProductionTest extends RoutingGeneratorBase
{
    protected function getRoutingGenerator($pageTreeCollection)
    {
        return new RoutingGeneratorProduction($pageTreeCollection);
    }
    
    protected function getExpectedFilename()
    {
        return 'site_routing.yml';
    }
    
    protected function getPrefix()
    {
        return '';
    }
}