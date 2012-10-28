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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Repository\Propel;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlUserRepositoryPropel;

/**
 * AlUserRepositoryTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlUserRepositoryTest extends TestCase
{
    private $roleRepository;
    private $pdo;

    protected function setUp()
    {
        parent::setUp();

        $this->pdo = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Repository\Propel\Pdo\MockPDO');
        $this->roleRepository = new AlUserRepositoryPropel($this->pdo);
    }

    public function testGetRepositoryObjectClassName()
    {
        $this->assertEquals('\AlphaLemon\AlphaLemonCmsBundle\Model\AlUser', $this->roleRepository->getRepositoryObjectClassName());
    }
    
    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     * @expectedExceptionMessage AlUserRepositoryPropel accepts only AlUser propel objects
     */
    public function testModelObjectInjectedBySettersIsInvalid()
    {
        $modelObject = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $this->roleRepository->setRepositoryObject($modelObject);
    }

    public function testModelObjectInjectedBySetters()
    {
        $modelObject = $this->getMock('\AlphaLemon\AlphaLemonCmsBundle\Model\AlUser');
        $this->assertEquals($this->roleRepository, $this->roleRepository->setRepositoryObject($modelObject));
        $this->assertEquals($modelObject, $this->roleRepository->getModelObject());
    }
}