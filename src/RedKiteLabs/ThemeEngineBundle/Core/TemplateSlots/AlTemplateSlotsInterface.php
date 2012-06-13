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
 */

namespace AlphaLemon\ThemeEngineBundle\Core\TemplateSlots;

/**
 * Defines the template slots methods
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface AlTemplateSlotsInterface
{
    /**
     * Configures the slots
     *
     * @return array
     */
    public function configure();

    /**
     * Return the template's slots
     *
     * @return array
     */
    public function getSlots();
}
