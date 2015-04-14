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

namespace RedKiteCms\EventSystem\Listener\Request;


use JMS\Serializer\Serializer;
use RedKiteCms\Action\FactoryAction;
use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Content\Block\BlockFactory;
use RedKiteCms\Content\BlockManager\BlockManagerAdd;
use RedKiteCms\Content\BlockManager\BlockManagerEdit;
use RedKiteCms\Content\PageCollection\PagesCollectionParser;
use RedKiteCms\Content\Theme\ThemeSlotsGenerator;
use RedKiteCms\Content\Theme\ThemeAligner;
use RedKiteCms\Content\Theme\ThemeGenerator;
use RedKiteCms\FilesystemEntity\Page;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class QueueListener listens to Kernel Request to execute the queue actions to align the backend with the frontend
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Listener\Request
 */
class QueueListener
{
    /**
     * @type \RedKiteCms\Configuration\ConfigurationHandler
     */
    private $configurationHandler;
    /**
     * @var \RedKiteCms\Action\FactoryAction
     */
    private $factoryAction;
    /**
     * @type \Symfony\Component\Security\Core\SecurityContext
     */
    private $securityContext;


    public function __construct(ConfigurationHandler $configurationHandler, FactoryAction $factoryAction, SecurityContext $securityContext)
    {
        $this->configurationHandler = $configurationHandler;
        $this->factoryAction = $factoryAction;
        $this->securityContext = $securityContext;
    }

    /**
     * Aligns the site slots
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $token = $this->securityContext->getToken();
        if (null === $token) {
            return;
        }

        $username = null;
        if ( ! $this->configurationHandler->isTheme()) {
            $username = $token->getUser()->getUsername();
        }

        $queueFile = $this->configurationHandler->siteDir() . '/queue';
        $finder = new Finder();
        $files = $finder->files()->depth(0)->name('*.json')->in($queueFile);
        foreach($files  as $file) {
            $queue = json_decode(FilesystemTools::readFile($file), true);
            foreach($queue as $operation) {
                $action = $this->factoryAction->create($operation["entity"], $operation["action"]);
                $action->execute($operation, $username);
            }

            unlink($file);
        }
    }
} 