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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Deploy;

use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme;

/**
 * AlTwigDeployer extends the base deployer class to deploy the website for stage environment
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @deprecated since 1.1.0
 */
class AlTwigDeployerStage extends AlTwigDeployer
{
    /**
     * @codeCoverageIgnore
     */
    protected function save(AlPageTree $pageTree, AlTheme $theme, array $options)
    {
        return $this->twigTemplateWriter
            ->generateTemplate($pageTree, $theme, $options)
            ->writeTemplate($options["deployDir"])
        ;
    }
}
