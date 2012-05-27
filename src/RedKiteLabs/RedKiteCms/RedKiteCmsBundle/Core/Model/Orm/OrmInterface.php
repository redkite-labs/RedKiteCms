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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm;

/**
 * AlPageContentsContainerInterface
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface OrmInterface {
    public function setConnection($connection);
    public function getConnection();
    public function startTransaction();
    public function commit();
    public function rollBack();
    public function save(array $values, $modelObject = null);
    public function delete($modelObject = null);
    public function getAffectedRecords();
}