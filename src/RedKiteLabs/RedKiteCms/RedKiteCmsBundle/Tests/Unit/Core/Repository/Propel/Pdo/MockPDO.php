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

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Repository\Propel\Pdo;

/**
 * Wraps PropelPDO class to make it mockable
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class MockPDO extends \PropelPDO
{
    public function __construct ()
    {}
}

class MockPDOStatement extends \PDOStatement
{
    public function __construct ()
    {}
}
