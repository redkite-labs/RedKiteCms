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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\Factory;

use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;

/**
 * Used by the Slots converter factory to create the appropriate converter to change the
 * repeated status of a slot to another one
 *
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface AlSlotsConverterFactoryInterface
{ 
    /**
     * Creates the appropriate conver using the given parameter
     * 
     * @param string $newRepeatedStatus  The new repeated status the slot must get
     */
    public function createConverter(AlSlot $slot, $newRepeatedStatus);
}