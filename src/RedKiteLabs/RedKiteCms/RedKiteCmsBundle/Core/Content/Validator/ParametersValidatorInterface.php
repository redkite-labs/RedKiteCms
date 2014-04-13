<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator;

/**
 * ParametersValidator
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
interface ParametersValidatorInterface
{
    /**
     * Checks when the given array is empty or not
     *
     * @param array  $values
     * @param string $message Overrides the default exception message
     *
     * @api
     */
    public function checkEmptyParams(array $values, $message = null);

    /**
     * Computes the difference between the keys of required params and the values and checks that this last one
     * contains at least one of the required keys
     *
     * @param array  $requiredParams
     * @param array  $values
     * @param string $message        Overrides the default exception message
     *
     * @api
     */
    public function checkOnceValidParamExists(array $requiredParams, array $values, $message = null);

    /**
     * Computes the difference between the keys of required params and the values and checks that this last one
     * contains all the required keys
     *
     * @param array  $requiredParams
     * @param array  $values
     * @param string $message        Overrides the default exception message
     *
     * @api
     */
    public function checkRequiredParamsExists(array $requiredParams, array $values, $message = null);
}
