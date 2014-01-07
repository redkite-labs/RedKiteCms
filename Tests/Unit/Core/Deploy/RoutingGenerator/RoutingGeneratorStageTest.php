<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Deploy\RoutingGenerator;

use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\RoutingGenerator\RoutingGeneratorStage;

/**
 * Description of RoutingGeneratorProductionTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class RoutingGeneratorStageTest extends RoutingGeneratorBase
{
    protected function getRoutingGenerator($pageTreeCollection)
    {
        return new RoutingGeneratorStage($pageTreeCollection);
    }
    
    protected function getExpectedFilename()
    {
        return 'site_routing_stage.yml';
    }
    
    protected function getPrefix()
    {
        return '_stage';
    }
}