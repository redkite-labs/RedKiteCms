<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

/**
 * AlParametersValidator
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
interface AlParametersValidatorInterface
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
