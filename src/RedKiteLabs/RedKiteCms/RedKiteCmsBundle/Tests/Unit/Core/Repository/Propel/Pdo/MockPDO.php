<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Repository\Propel\Pdo;

/**
 * Wraps PropelPDO class to make it mockable
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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
