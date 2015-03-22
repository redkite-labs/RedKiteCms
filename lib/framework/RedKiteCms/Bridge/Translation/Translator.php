<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Bridge\Translation;


use Symfony\Component\Translation\TranslatorInterface;

/**
 * This object statically handles the Symfony translator object
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Bridge\Translation
 */
class Translator
{
    /**
     * @type null|TranslatorInterface
     */
    private static $translator = null;

    /**
     * Sets the translator
     *
     * @param TranslatorInterface $translator
     */
    public static function setTranslator(TranslatorInterface $translator)
    {
        self::$translator = $translator;
    }

    /**
     * Translates the given message
     *
     * @param        $message
     * @param array  $parameters
     * @param string $domain
     * @param null   $locale
     * @return string
     */
    public static function translate($message, $parameters = array(), $domain = "RedKiteCms", $locale = null)
    {
        if (null === self::$translator) {
            return;
        }

        return self::$translator->trans($message, $parameters, $domain, $locale);
    }
}