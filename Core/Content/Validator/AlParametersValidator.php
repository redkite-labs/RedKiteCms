<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator;

use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General;

/**
 * AlParametersValidator validates consistence of array parameters
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlParametersValidator implements AlParametersValidatorInterface
{
    /**
     * {@inheritdoc}
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
     *
     * @api
     */
    public function checkEmptyParams(array $values, $message = null)
    {
        if (empty($values)) {
            if (null === $message) {
                $message = 'Any parameter has been given';
            }

            throw new General\EmptyArgumentsException($message);
        }
    }

    /**
     * {@inheritdoc}
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     *
     * @api
     */
    public function checkOnceValidParamExists(array $requiredParams, array $values, $message = null)
    {
        $this->checkEmptyParams($requiredParams, 'exception_any_parameter_given_to_AlValidatorn');
        $this->checkEmptyParams($values, 'exception_any_value_to_check_when_only_a_param_exists');

        $diff = array_intersect_key($requiredParams, $values);
        if (empty($diff)) {
            if (null === $message) {
                $message = array(
                    'message' => 'exception_any_valid_option_provided',
                    'parameters' => array(
                        '%required%' => $this->doImplode($requiredParams),
                        '%values%' => $this->doImplode($values),
                    ),
                );

                $message = json_encode($message);
            }

            throw new General\ArgumentExpectedException($message);
        }
    }

    /**
     * {@inheritdoc}
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     *
     * @api
     */
    public function checkRequiredParamsExists(array $requiredParams, array $values, $message = null)
    {
        $this->checkEmptyParams($requiredParams, 'exception_any_param_to_check_when_all_params_exist');
        $this->checkEmptyParams($values, 'exception_any_value_provided_to_check_when_all_params_exist');

        $diff = array_intersect_key($requiredParams, $values);
        if ($diff != $requiredParams) {
            if (null === $message) {
                $message = array(
                    'message' => 'exception_some_required_options_are_not_provided',
                    array(
                        '%required%' => $this->doImplode($requiredParams),
                        '%values%' => $this->doImplode($values)
                    ),
                );

                $message = json_encode($message);
            }

            throw new General\ArgumentExpectedException($message);
        }
    }

    /**
     * Implodes the given array
     *
     * @param  array $params
     * @return array
     */
    protected function doImplode(array $params)
    {
        $options = array_keys($params);
        sort($options);

        return implode(',', $options);
    }
}
