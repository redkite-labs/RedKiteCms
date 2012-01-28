<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 * 
 */

namespace AlphaLemon\ThemeEngineBundle\Tests\tools;


class AlphaLemonDataPopulator
{   
    public static function depopulate($con = null)
    {
        $peerClasses = array(
            '\AlphaLemon\ThemeEngineBundle\Model\AlThemePeer',
        );
        // free the memory from existing objects
        foreach ($peerClasses as $peerClass) {
                foreach ($peerClass::$instances as $o) {
                        $o->clearAllReferences();
                }
        }
        // delete records from the database
        if($con === null) {
                $con = \Propel::getConnection();
        }
        $con->beginTransaction();
        foreach ($peerClasses as $peerClass) {
                $peerClass::doDeleteAll($con);
        }
        
        $con->commit();
    }
}
