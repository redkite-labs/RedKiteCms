<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\OrmDriver;

/**
 *
 * @author test
 */
interface OrmManagerInterface 
{
    public function doSave(array $values);
    public function doDelete();
}