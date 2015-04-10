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

namespace RedKiteCms\Content\Theme;


use Symfony\Component\Filesystem\Filesystem;

class ThemeDeployer extends ThemeBase
{
    public function deploy()
    {
        $fs = new Filesystem();
        $fs->remove($this->baseThemeDir);
        $fs->mirror($this->themeDir, $this->baseThemeDir, null, array("override" => true));
    }
} 