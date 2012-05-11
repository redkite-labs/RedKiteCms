<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm;

/**
 *
 * @author test
 */
interface OrmInterface {
    public function setConnection($connection);
    public function getConnection();
    public function startTransaction();
    public function commit();
    public function rollback();
    public function save(array $values, $modelObject = null);
    public function delete($modelObject = null);
}