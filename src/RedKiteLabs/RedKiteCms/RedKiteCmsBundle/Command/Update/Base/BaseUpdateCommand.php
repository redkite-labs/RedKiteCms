<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Command\Update\Base;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\Base\AlPropelOrm;
use Propel\PropelBundle\Command\ModelBuildCommand;

/**
 * provides some useful methods to manage the database from commands
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BaseUpdateCommand extends ContainerAwareCommand
{
    protected function executeQueries($connection, $sqlFile)
    {
        $orm = new AlPropelOrm($connection);
        if (is_file($sqlFile)) {
            $updateQueries = file_get_contents($sqlFile);
            
            $queries = explode(';', $updateQueries);
            foreach ($queries as $query) {
                try {
                    $orm->executeQuery($query);
                }
                catch (\PropelException $ex) {echo $ex->getMessage();}
            }
        } else {
            throw new \Exception(sprintf('The file %s has not been found. RedKiteCms provides only the mysql queries required to updated the database. To fix this, please create a %s folder under the %s and adjust the provided queries for your database, then launch the command again.', $sqlFile, $input->getOption('driver'), dirname($sqlFile)));
        }
    }
    
    protected function buildModel($input, $output)
    {
        $modelCommand = new ModelBuildCommand();
        $modelCommand->setApplication($this->getApplication());
        $modelCommand->execute($input, $output);
    }
}
