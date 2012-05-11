<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator;

/**
 * AlParametersValidator
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface AlParametersValidatorInterface
{
    public function checkEmptyParams(array $values);
    
    public function checkOnceValidParamExists(array $requiredParams, array $values);
    
    public function checkRequiredParamsExists(array $requiredParams, array $values);
}