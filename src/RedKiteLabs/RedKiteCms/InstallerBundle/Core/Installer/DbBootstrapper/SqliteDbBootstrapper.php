<?php
/*
 * This file is part of the RedKite CMS InstallerBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Core\Installer\DbBootstrapper;

/**
 * Implements the Sqlite object to bootstrap the database
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class SqliteDbBootstrapper extends Base\BaseDbBootstrapper
{
    /**
     * {@inheritdoc}
     */
    public function createDatabase()
    {
        try
        {
            if (is_file($this->database)) {
                unlink($this->database);
            }
            
            $this->setUpOrm($this->dsnBuilder->getBaseDsn());            
        }
        catch(\Exception $ex)
        {
            throw $ex;
        }
    }
}