<?php

/*
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

use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlTheme;
use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\RoutingGenerator\RoutingGeneratorInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\SitemapGenerator\SitemapGeneratorInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TwigTemplateWriter\TwigTemplateWriter;

/**
 * AlTwigDeployer extends the base deployer class to save a PageTree to a twig template
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlTwigDeployer extends AlDeployer
{
    protected $twigTemplateWriter;

    /**
     * Constructor
     * 
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TwigTemplateWriter\TwigTemplateWriter $twigTemplateWriter
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Deploy\RoutingGenerator\RoutingGeneratorInterface $routingGenerator
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Deploy\SitemapGenerator\SitemapGeneratorInterface $sitemapGenerator
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function __construct(TwigTemplateWriter $twigTemplateWriter, RoutingGeneratorInterface $routingGenerator, SitemapGeneratorInterface $sitemapGenerator = null, EventDispatcherInterface $dispatcher = null)
    {
        parent::__construct($routingGenerator, $sitemapGenerator, $dispatcher);
        
        $this->twigTemplateWriter = $twigTemplateWriter;
    }
    
    /**
     * @inheritdoc
     */
    protected function save(AlPageTree $pageTree, AlTheme $theme, array $options)
    {
        return $this->twigTemplateWriter
            ->generateTemplate($pageTree, $theme, $options)
            ->writeTemplate($options["deployDir"])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function checkTargetFolders(array $options)
    {
        parent::checkTargetFolders($options);

        $this->fileSystem->mkdir($options["viewsDir"]);
    }
}