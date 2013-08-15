<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Translator;

/**
 * Defines the base interface to translate a message to another language
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
interface AlTranslatorInterface
{
    /**
     * Translates the message when the translator has been set or returns the message
     * when null
     *
     * @param string $message
     * @param array  $parameters
     * @param string $domain
     * @param string $locale
     *
     * @return string
     *
     * @api
     */
    public function translate($message, array $parameters = array(), $domain = 'messages', $locale = null);
}
