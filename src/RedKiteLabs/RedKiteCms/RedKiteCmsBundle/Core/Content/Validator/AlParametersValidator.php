<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageModelation, please view the LICENSE
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
 * AlParametersValidator
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlParametersValidator extends AlTranslator implements AlParametersValidatorInterface
{
    public function checkEmptyParams(array $values)
    {
        if(empty($values)) {
            throw new General\EmptyParametersException($this->translate('Any parameter has been given.'));
        }
    }

    public function checkOnceValidParamExists(array $requiredParams, array $values)
    {
        $diff = array_intersect_key($requiredParams, $values);
        if(empty($diff)) {
            throw new General\ParameterExpectedException($this->translate('The following parameters are required: %required%. You must give %diff% which is/are missing', array('%required%' => $this->doImplode($requiredParams), '%diff%' => $this->doImplode($diff))));
        }
    }

    public function checkRequiredParamsExists(array $requiredParams, array $values)
    {
        $diff = array_intersect_key($requiredParams, $values);
        if($diff != $requiredParams) {
            throw new General\ParameterExpectedException($this->translate('The following parameters are required: %required%. The parameters you gave are %values%', array('%required%' => $this->doImplode($requiredParams), '%values%' => $this->doImplode($values))));
        }
    }

    protected function doImplode(array $params)
    {
        return implode(',', array_keys($params));
    }
}