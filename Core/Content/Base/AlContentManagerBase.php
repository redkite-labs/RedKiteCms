<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    GPL LICENSE Version 2.0
 * 
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;

/**
 * The base class that defines a content manager object
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlContentManagerBase
{
    protected $dispatcher;
    protected $translator;
    protected $connection;

    /**
     * Constructor
     * 
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface $translator
     * @param \PropelPDO $connection 
     */
    public function __construct(EventDispatcherInterface $dispatcher, TranslatorInterface $translator, \PropelPDO $connection = null)
    {
        $this->dispatcher = $dispatcher;
        $this->translator = $translator;
        $this->connection = (null === $connection) ? \Propel::getConnection() : $connection;
    }
    
    /**
     * Returns the current PropelConnection 
     * 
     * @return PropelPDO 
     */
    public function getConnection()
    {
        return $this->connection;
    }

    protected function checkEmptyParams(array $values)
    {
        if(empty($values)) {
            throw new \InvalidArgumentException($this->translator->trans('save() method requires at least one valid parameter, any one has been given', array(), 'exceptions'));
        }
    }
    
    protected function checkOnceValidParamExists(array $requiredParams, array $values)
    {
        $diff = array_diff_key($requiredParams, $values); 
        if(count($diff) != 0 && count($diff) != count($values)) {
            throw new \InvalidArgumentException($this->translator->trans('save() method requires the following parameters: %required%. You must give %diff% which is/are missing', array('%required%' => $this->doImplode($requiredParams), '%diff%' => $this->doImplode($diff))));
        }
    }
    
    protected function checkRequiredParamsExists(array $requiredParams, array $values)
    {
        $diff = array_diff_key($requiredParams, $values);
        if(count($diff) == count($requiredParams) || count($diff) > 0) {
            throw new \InvalidArgumentException($this->translator->trans('save() method requires the following parameters: %required%. You must give %diff% which is/are missing', array('%required%' => $this->doImplode($requiredParams), '%diff%' => $this->doImplode($diff))));
        }
    }
    
    private function doImplode(array $params)
    {
        return implode(',', array_keys($params));
    }
}