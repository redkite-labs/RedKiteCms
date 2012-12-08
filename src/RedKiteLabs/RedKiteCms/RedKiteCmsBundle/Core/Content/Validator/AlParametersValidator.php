<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator;

use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\AlphaLemonCmsBundle\Core\Translator\AlTranslator;

/**
 * AlParametersValidator validates consistence of array parameters
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
class AlParametersValidator extends AlTranslator implements AlParametersValidatorInterface
{
    /**
     * {@inheritdoc}
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyParametersException
     * 
     * @api
     */
    public function checkEmptyParams(array $values, $message = null)
    {
        if (empty($values)) {
            if (null === $message) {
                $message = 'Any parameter has been given';
            }

            throw new General\EmptyParametersException($this->translate($message));
        }
    }

    /**
     * {@inheritdoc}
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterExpectedException
     * 
     * @api
     */
    public function checkOnceValidParamExists(array $requiredParams, array $values, $message = null)
    {
        $this->checkEmptyParams($requiredParams, 'Checking that at least a valid parameter exist cannot validate nothing when any required parameters has been given');
        $this->checkEmptyParams($values, 'Checking that at least a valid parameter exist cannot validate nothing when any value has been given');

        $diff = array_intersect_key($requiredParams, $values);
        if (empty($diff)) {
            if (null === $message) {
                $message = $this->translate('At least one of those options are required: %required%. The options you gave are %values%', array('%required%' => $this->doImplode($requiredParams), '%values%' => $this->doImplode($values)));
            }

            throw new General\ParameterExpectedException($message);
        }
    }
    
    /**
     * {@inheritdoc}
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterExpectedException
     * 
     * @api
     */
    public function checkRequiredParamsExists(array $requiredParams, array $values, $message = null)
    {
        $this->checkEmptyParams($requiredParams, 'Checking that all the required parameters exist cannot validate nothing when any required parameters has been given');
        $this->checkEmptyParams($values, 'Checking that all the required parameters exist cannot validate nothing when any value has been given');

        $diff = array_intersect_key($requiredParams, $values);
        if ($diff != $requiredParams) {
            if (null === $message) {
                $message = $this->translate('The following options are required: %required%. The options you gave are %values%', array('%required%' => $this->doImplode($requiredParams), '%values%' => $this->doImplode($values)));
            }

            throw new General\ParameterExpectedException($message);
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
