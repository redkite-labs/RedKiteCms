<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy;

use RedKiteLabs\ThemeEngineBundle\Core\Theme\Theme;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\PageTree;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\RoutingGenerator\RoutingGeneratorInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\SitemapGenerator\SitemapGeneratorInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\TwigTemplateWriter\TwigTemplateWriter;

/**
 * TwigDeployer extends the base deployer class to save a PageTree to a twig template
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class TwigDeployer extends Deployer
{
    /** @var TwigTemplateWriter */
    protected $twigTemplateWriter;

    /**
     * Constructor
     *
     * @param TwigTemplateWriter        $twigTemplateWriter
     * @param RoutingGeneratorInterface $routingGenerator
     * @param SitemapGeneratorInterface $sitemapGenerator
     * @param EventDispatcherInterface  $dispatcher
     */
    public function __construct(TwigTemplateWriter $twigTemplateWriter, RoutingGeneratorInterface $routingGenerator, SitemapGeneratorInterface $sitemapGenerator = null, EventDispatcherInterface $dispatcher = null)
    {
        parent::__construct($routingGenerator, $sitemapGenerator, $dispatcher);

        $this->twigTemplateWriter = $twigTemplateWriter;
    }

    /**
     * @inheritdoc
     */
    protected function save(PageTree $pageTree, Theme $theme, array $options)
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
