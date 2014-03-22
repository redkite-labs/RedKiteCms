<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\Factory;

use RedKiteLabs\ThemeEngineBundle\Core\ThemeSlots\Slot;

/**
 * Used by the Slots converter factory to create the appropriate converter to change the
 * repeated status of a slot to another one
 *
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
interface SlotsConverterFactoryInterface
{
    /**
     * Creates the appropriate conver using the given parameter
     *
     * @param  Slot                                                                                      $slot
     * @param  string                                                                                      $newRepeatedStatus
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\SlotConverterInterface
     */
    public function createConverter(Slot $slot, $newRepeatedStatus);
}
