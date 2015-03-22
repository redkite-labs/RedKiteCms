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

namespace RedKiteCms\Rendering\Controller\Theme;

use RedKiteCms\Configuration\SiteBuilder;
use RedKiteCms\Rendering\Controller\BaseController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Implements the actions to manage the blocks on a slot's page
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class StartFromThemeController extends BaseController
{
    public function start(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);

        $siteDir = $this->options["configuration_handler"]->siteDir();
        $fs = new Filesystem();
        $fs->remove($siteDir);

        $themeName = $options["request"]->get('theme');
        $siteBuilder = new SiteBuilder(
            $this->options["configuration_handler"]->rootDir(),
            $this->options["configuration_handler"]->siteName()
        );
        if ($this->options["configuration_handler"]->isTheme()) {
            $siteBuilder->handleTheme();
        }

        $siteBuilder
            ->theme($themeName)
            ->build();

        return $this->buildJSonResponse(array());
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            array(
                'request',
                'configuration_handler',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'request' => 'Symfony\Component\HttpFoundation\Request',
                'configuration_handler' => '\RedKiteCms\Configuration\ConfigurationHandler',
            )
        );
    }
}