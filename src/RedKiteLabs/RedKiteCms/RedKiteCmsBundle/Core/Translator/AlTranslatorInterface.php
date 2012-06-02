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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Translator;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * AlTranslatorInterface
 * 
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface AlTranslatorInterface
{    
    /**
     * Translates the message when the translator has been set or returns the message when null
     * 
     * @param string $message
     * @param array $parameters
     * @param string $domain
     * @param string $locale
     * 
     * @return string 
     */
    public function translate($message, array $parameters = array(), $domain = 'messages', $locale = null);
}