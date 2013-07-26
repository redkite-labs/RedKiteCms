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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Validator;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidator;

/**
 * AlParametersValidator
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlParametersValidatorTest extends TestCase
{
    private $validator;

    protected function setUp()
    {
        $this->validator = new AlParametersValidator();
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     * @expectedExceptionMessage Any parameter has been given
     */
    public function testCheckEmptyParamsThrownAnExceptionWhenTheParameterIsEmpty()
    {
        $this->validator->checkEmptyParams(array());
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     * @expectedExceptionMessage Empty array
     */
    public function testCheckEmptyParamsThrownAnExceptionWhenTheParameterIsEmptyWithCustomErrorMessage()
    {
        $this->validator->checkEmptyParams(array(), "Empty array");
    }

    public function testCheckEmptyParamsPassesWhenParameterIsNotEmpty()
    {
        $this->validator->checkEmptyParams(array('fake'));
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     * @expectedExceptionMessage AlValidator cannot check that at least once parameter exists because any "required parameters" has been given
     */
    public function testCheckOnceValidParamExistsThrownAnExceptionWhenRequiredParametersAreEmpty()
    {
        $this->validator->checkOnceValidParamExists(array(), array());
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     * @expectedExceptionMessage AlValidator cannot check that at least once parameter exists because any "value" has been given
     */
    public function testCheckOnceValidParamExistsThrownAnExceptionWhenValuesAreEmpty()
    {
        $this->validator->checkOnceValidParamExists(array('param' => ''), array());
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     * @expectedExceptionMessage {"message":"At least one of those options are required: %required%. The options you gave are %values%","parameters":{"%required%":"param","%values%":"param1"}}
     */
    public function testCheckOnceValidParamExistsThrownAnExceptionWhenAnyOfTheExpectedParamsHasBeenFound()
    {
        $this->validator->checkOnceValidParamExists(array('param' => ''), array('param1' => 'value'));
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     * @expectedExceptionMessage Any espected parameter has been given
     */
    public function testCheckOnceValidParamExistsThrownAnExceptionWhenAnyOfTheExpectedParamsHasBeenFoundWithCustomErrorMessage()
    {
        $this->validator->checkOnceValidParamExists(array('param' => ''), array('param1' => 'value'), 'Any espected parameter has been given');
    }

    public function testCheckOnceValidParamExistsPasses()
    {
        $this->validator->checkOnceValidParamExists(array('param' => ''), array('param' => 'value', 'param1' => 'value'));
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     * @expectedExceptionMessage AlValidator cannot check that all the required parameters exist because any "required parameters" has been given
     */
    public function testCheckRequiredParamsThrownAnExceptionWhenRequiredParametersAreEmpty()
    {
        $this->validator->checkRequiredParamsExists(array(), array());
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     * @expectedExceptionMessage AlValidator cannot check that all the required parameters exist because any "value" has been given
     */
    public function testCheckRequiredParamsThrownAnExceptionWhenValuesAreEmpty()
    {
        $this->validator->checkRequiredParamsExists(array('param' => ''), array());
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     * @expectedExceptionMessage The following options are required: %required%. The options you gave are %values%
     */
    public function testCheckRequiredParamsThrownAnExceptionWhenAnyOfTheExpectedParamsHasBeenFoundWithCustomErroraMessage()
    {
        $this->validator->checkRequiredParamsExists(array('param' => '', 'param1' => 'value'), array('param' => 'value'));
    }

    /**
     * @expectedException \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     * @expectedExceptionMessage The espected parameters are missing
     */
    public function testCheckRequiredParamsThrownAnExceptionWhenAnyOfTheExpectedParamsHasBeenFoundWithCustomErroraMessageWithCustomErrorMessage()
    {
        $this->validator->checkRequiredParamsExists(array('param' => '', 'param1' => 'value'), array('param' => 'value'), 'The espected parameters are missing');
    }

    public function testCheckRequiredParamsPasses()
    {
        $this->validator->checkRequiredParamsExists(array('param' => '', 'param1' => 'value'), array('param' => 'value', 'param1' => 'value', 'param2' => 'value'));
    }
}
