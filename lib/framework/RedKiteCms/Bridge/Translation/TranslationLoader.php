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

use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * This object extends the XliffFileLoader object to register RedKite CMS translation resources
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Bridge\Translation
 */
class TranslationLoader extends XliffFileLoader
{
    /**
     * Register the translation resources read from the given folders
     *
     * @param TranslatorInterface $translator
     * @param array               $dirs
     */
    public function registerResources(TranslatorInterface $translator, array $dirs)
    {
        $finder = new Finder();
        $files = $finder->files()->depth(0)->ignoreUnreadableDirs()->in($dirs);
        foreach ($files as $file) {
            $file = (string)$file;
            preg_match_all('/[^.]+/', basename($file), $match);

            $translator->addResource('xliff', $file, $match[0][1], "RedKiteCms");
        }
    }
} 