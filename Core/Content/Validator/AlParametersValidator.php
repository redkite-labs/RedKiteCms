<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator;

use Symfony\Component\Translation\TranslatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;

/**
 * AlParametersValidator
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlParametersValidator implements AlParametersValidatorInterface
{
    protected $translator;
    
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


    public function checkEmptyParams(array $values)
    {
        if(empty($values)) {
            throw new General\EmptyParametersException($this->translator->trans('Any parameter has been given.'));
        }
    }
    
    public function checkOnceValidParamExists(array $requiredParams, array $values)
    {
        /*
        $diff = array_diff_key($requiredParams, $values); 
        if(count($diff) != 0 && count($diff) != count($values)) {
            throw new General\AnyValidParameterGivenException($this->translator->trans('The following parameters are required: %required%. You must give %diff% which is/are missing', array('%required%' => $this->doImplode($requiredParams), '%diff%' => $this->doImplode($diff))));
        }*/
        
        $diff = array_intersect_key($requiredParams, $values);
        if(empty($diff)) {
            throw new General\ParameterExpectedException($this->translator->trans('The following parameters are required: %required%. You must give %diff% which is/are missing', array('%required%' => $this->doImplode($requiredParams), '%diff%' => $this->doImplode($diff))));
        }
    }
    
    public function checkRequiredParamsExists(array $requiredParams, array $values)
    {
        $diff = array_intersect_key($requiredParams, $values);
        if($diff != $requiredParams) {
            throw new General\ParameterExpectedException($this->translator->trans('The following parameters are required: %required%. You must give %diff% which is/are missing', array('%required%' => $this->doImplode($requiredParams), '%diff%' => $this->doImplode($diff))));
        }
    }
    
    protected function doImplode(array $params)
    {
        return implode(',', array_keys($params));
    }
}