<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Content\Validator;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidator;

/**
 * AlParametersValidator
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlParametersValidatorTest extends TestCase
{
    private $validator;

    protected function setUp()
    {
        $this->validator = new AlParametersValidator();
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     * @expectedExceptionMessage Any parameter has been given
     */
    public function testCheckEmptyParamsThrownAnExceptionWhenTheParameterIsEmpty()
    {
        $this->validator->checkEmptyParams(array());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
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
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     * @expectedExceptionMessage exception_any_parameter_given_to_AlValidatorn
     */
    public function testCheckOnceValidParamExistsThrownAnExceptionWhenRequiredParametersAreEmpty()
    {
        $this->validator->checkOnceValidParamExists(array(), array());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     * @expectedExceptionMessage exception_any_value_to_check_when_only_a_param_exists
     */
    public function testCheckOnceValidParamExistsThrownAnExceptionWhenValuesAreEmpty()
    {
        $this->validator->checkOnceValidParamExists(array('param' => ''), array());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     * @expectedExceptionMessage {"message":"exception_any_valid_option_provided","parameters":{"%required%":"param","%values%":"param1"}}
     */
    public function testCheckOnceValidParamExistsThrownAnExceptionWhenAnyOfTheExpectedParamsHasBeenFound()
    {
        $this->validator->checkOnceValidParamExists(array('param' => ''), array('param1' => 'value'));
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
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
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     * @expectedExceptionMessage exception_any_param_to_check_when_all_params_exist
     */
    public function testCheckRequiredParamsThrownAnExceptionWhenRequiredParametersAreEmpty()
    {
        $this->validator->checkRequiredParamsExists(array(), array());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     * @expectedExceptionMessage exception_any_value_provided_to_check_when_all_params_exist
     */
    public function testCheckRequiredParamsThrownAnExceptionWhenValuesAreEmpty()
    {
        $this->validator->checkRequiredParamsExists(array('param' => ''), array());
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     * @expectedExceptionMessage exception_some_required_options_are_not_provided
     */
    public function testCheckRequiredParamsThrownAnExceptionWhenAnyOfTheExpectedParamsHasBeenFoundWithCustomErroraMessage()
    {
        $this->validator->checkRequiredParamsExists(array('param' => '', 'param1' => 'value'), array('param' => 'value'));
    }

    /**
     * @expectedException \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
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
