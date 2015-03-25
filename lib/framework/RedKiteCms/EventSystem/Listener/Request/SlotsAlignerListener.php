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


use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Content\PageCollection\PagesCollectionParser;
use RedKiteCms\Content\Theme\ThemeSlotsManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class SlotsAlignerListener listens to Kernel Request to update the site slots according to changes made with the theme
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\EventSystem\Listener\Request
 */
class SlotsAlignerListener
{
    /**
     * @type \RedKiteCms\Configuration\ConfigurationHandler
     */
    private $configurationHandler;
    /**
     * @type \RedKiteCms\Content\PageCollection\PagesCollectionParser
     */
    private $pagesCollectionParser;
    /**
     * @type \Symfony\Component\Security\Core\SecurityContext
     */
    private $securityContext;

    /**
     * Constructor
     *
     * @param \RedKiteCms\Configuration\ConfigurationHandler $configurationHandler
     * @param \RedKiteCms\Content\PageCollection\PagesCollectionParser $pagesCollectionParser
     * @param \Symfony\Component\Security\Core\SecurityContext $securityContext
     * @param \RedKiteCms\Content\Theme\ThemeSlotsManager $templateSlotsManager
     */
    public function __construct(ConfigurationHandler $configurationHandler, PagesCollectionParser $pagesCollectionParser, SecurityContext $securityContext, ThemeSlotsManager $templateSlotsManager)
    {
        $this->configurationHandler = $configurationHandler;
        $this->pagesCollectionParser = $pagesCollectionParser;
        $this->securityContext = $securityContext;
        $this->templateSlotsManager = $templateSlotsManager;
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
        
        $this->pagesCollectionParser
            ->contributor($username)
            ->parse()
        ;

        $this->templateSlotsManager->align($this->pagesCollectionParser);
    }
} 