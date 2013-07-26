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

/**
 * AlParametersValidator validates consistence of array parameters
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
class AlParametersValidator implements AlParametersValidatorInterface
{
    /**
     * {@inheritdoc}
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\EmptyArgumentsException
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
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     * 
     * @api
     */
    public function checkOnceValidParamExists(array $requiredParams, array $values, $message = null)
    {
        $this->checkEmptyParams($requiredParams, 'AlValidator cannot check that at least once parameter exists because any "required parameters" has been given');
        $this->checkEmptyParams($values, 'AlValidator cannot check that at least once parameter exists because any "value" has been given');

        $diff = array_intersect_key($requiredParams, $values);
        if (empty($diff)) {
            if (null === $message) {
                $message = array(
                    'message' => 'At least one of those options are required: %required%. The options you gave are %values%',
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
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ArgumentExpectedException
     * 
     * @api
     */
    public function checkRequiredParamsExists(array $requiredParams, array $values, $message = null)
    {
        $this->checkEmptyParams($requiredParams, 'AlValidator cannot check that all the required parameters exist because any "required parameters" has been given');
        $this->checkEmptyParams($values, 'AlValidator cannot check that all the required parameters exist because any "value" has been given');

        $diff = array_intersect_key($requiredParams, $values);
        if ($diff != $requiredParams) {
            if (null === $message) {
                $message = array(
                    'message' => 'The following options are required: %required%. The options you gave are %values%',
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
