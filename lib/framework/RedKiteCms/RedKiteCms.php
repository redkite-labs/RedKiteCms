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

namespace RedKiteCms;



use Assetic\Filter\CssRewriteFilter;
use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\SerializerBuilder;
use Monolog\Logger;
use RedKiteCms\Bridge\Assetic\AsseticFactoryBuilder;
use RedKiteCms\Bridge\Dispatcher\Dispatcher;
use RedKiteCms\Bridge\ElFinder\ElFinderFilesConnector;
use RedKiteCms\Bridge\ElFinder\ElFinderMediaConnector;
use RedKiteCms\Bridge\Form\FormFactory;
use RedKiteCms\Bridge\Monolog\DataLogger;
use RedKiteCms\Bridge\Routing\RoutingGenerator;
use RedKiteCms\Bridge\Routing\RoutingProvider;
use RedKiteCms\Bridge\Security\UserProvider;
use RedKiteCms\Bridge\Translation\TranslationLoader;
use RedKiteCms\Bridge\Translation\Translator;
use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Configuration\SiteBuilder;
use RedKiteCms\Content\Block\BlockFactory;
use RedKiteCms\Content\BlockManager\BlockManager;
use RedKiteCms\Content\PageCollection\PageCollectionManager;
use RedKiteCms\Content\PageCollection\PagesCollectionParser;
use RedKiteCms\Content\PageCollection\PermalinkManager;
use RedKiteCms\Content\Page\PageManager;
use RedKiteCms\Content\SlotsManager\SlotsManagerFactory;
use RedKiteCms\Content\Theme\Theme;
use RedKiteCms\Content\Theme\ThemeSlotsManager;
use RedKiteCms\EventSystem\CmsEvents;
use RedKiteCms\EventSystem\Event\Cms\CmsBootedEvent;
use RedKiteCms\EventSystem\Event\Cms\CmsBootingEvent;
use RedKiteCms\EventSystem\Listener\Block\BlockEditingListener;
use RedKiteCms\EventSystem\Listener\Cms\CmsBootingListener;
use RedKiteCms\EventSystem\Listener\Exception\ExceptionListener;
use RedKiteCms\EventSystem\Listener\PageCollection\PageRemovedListener;
use RedKiteCms\EventSystem\Listener\PageCollection\PageSavedListener;
use RedKiteCms\EventSystem\Listener\PageCollection\TemplateChangedListener;
use RedKiteCms\EventSystem\Listener\Page\PermalinkChangedListener;
use RedKiteCms\FilesystemEntity\Page;
use RedKiteCms\FilesystemEntity\SlotParser;
use RedKiteCms\Plugin\PluginManager;
use RedKiteCms\Rendering\PageRenderer\PageRendererBackend;
use RedKiteCms\Rendering\PageRenderer\PageRendererProduction;
use RedKiteCms\Rendering\TemplateAssetsManager\TemplateAssetsManager;
use RedKiteCms\Rendering\Toolbar\ToolbarManager;
use Silex\Application;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RedKiteCms is the object deputed to bootstrap the CMS. This object is the application entry point and it is
 * used in front-controllers.
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms
 */
abstract class RedKiteCms
{
    private $app;
    private $siteName;
    private $siteBuilder = null;

    /**
     * Returns an array of options to change RedKite CMS configuration
     * @return array
     */
    abstract protected function configure();

    /**
     * Registers additional services
     *
     * @param Application $app
     */
    abstract protected function register(Application $app);

    /**
     * Constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Bootstraps the application
     *
     * @param string $rootDir
     * @param string $siteName
     */
    public function bootstrap($rootDir, $siteName)
    {
        $this->app["red_kite_cms.root_dir"] = $rootDir;
        $this->siteName = $siteName;

        $this->initCmsRequiredServices();
        $this->registerProviders();
        $this->registerServices();
        $this->registerListeners();
        $this->register($this->app);
        $this->boot();
        $this->registerRoutes();
    }

    private function initCmsRequiredServices()
    {
        $configurationOptions = $this->configure();
        $this->app["red_kite_cms.configuration_handler"] = new ConfigurationHandler(
            $this->app["red_kite_cms.root_dir"],
            $this->siteName
        );
        $this->app["red_kite_cms.configuration_handler"]->setConfigurationOptions($configurationOptions);
        $siteNameDir = $this->app["red_kite_cms.root_dir"] . '/app/data/' . $this->siteName;
        if (!is_dir($siteNameDir)) {
            $this->siteBuilder = new SiteBuilder($this->app["red_kite_cms.root_dir"], $this->siteName);
            $this->siteBuilder->build();
        }

        $this->app["red_kite_cms.configuration_handler"]->boot();
    }

    private function registerProviders()
    {
        AnnotationRegistry::registerAutoloadNamespace(
            'JMS\Serializer\Annotation',
            $this->app["red_kite_cms.root_dir"] . '/vendor/jms/serializer/src'
        );

        $app = $this->app;
        $siteName = $this->siteName;
        $this->app['security.firewalls'] = array(
            'backend' => array(
                'pattern' => '^/backend',
                'form' => array(
                    'login_path' => '/login',
                    'check_path' => '/backend/login_check',
                ),
                'logout' => array(
                    'logout_path' => '/backend/logout',
                    'target_url' => '/login',
                ),
                'users' => $this->app->share(
                    function () use ($app, $siteName) {
                        return new UserProvider($app["red_kite_cms.root_dir"], $siteName);
                    }
                ),
            ),
        );

        // TODO setup roles dinamically
        $app['security.access_rules'] = array(
            array('^.*$', 'ROLE_USER'),
        );

        $this->app->register(new UrlGeneratorServiceProvider());
        $this->app->register(new SecurityServiceProvider());
        $this->app->register(new SessionServiceProvider());

        $this->app->boot();

        $this->app->register(
            new TranslationServiceProvider(),
            array(
                'locale_fallbacks' => array('en'),
            )
        );
        $this->app->register(new FormServiceProvider());
        $this->app->register(
            new TwigServiceProvider(),
            array(
                'twig.path' => array(
                    $this->app["red_kite_cms.root_dir"] . '/lib/plugins/RedKiteCms/Core',
                    $this->app["red_kite_cms.root_dir"] . '/lib/plugins/RedKiteCms/Block',
                    $this->app["red_kite_cms.root_dir"] . '/lib/plugins/RedKiteCms/Theme',
                    $this->app["red_kite_cms.root_dir"] . '/app/plugins/RedKiteCms/Block',
                    $this->app["red_kite_cms.root_dir"] . '/app/plugins/RedKiteCms/Theme',
                    $this->app["red_kite_cms.root_dir"] . '/src',
                ),
            )
        );

        $this->app['translator'] = $this->app->share(
            $this->app->extend(
                'translator',
                function ($translator, $app) {
                    $resources = array(
                        $app["red_kite_cms.root_dir"] . '/lib/plugins/RedKiteCms/Core/RedKiteCms/Resources/translations',
                        $app["red_kite_cms.root_dir"] . '/lib/plugins/RedKiteCms/Block/*/Resources/translations',
                    );

                    // This is a workaround required because Symfony2 Finder throws an exception when a folder does not exist, so the
                    // path for custom bundles cannot be added arbitrarily.
                    // This code looks for at least once "translations" folder for custom plugins and when it finds one, it adds the
                    // path to find translations for custom plugins
                    foreach ($app["red_kite_cms.plugin_manager"]->getBlockPlugins() as $plugin) {
                        if ($plugin->isCore()) {
                            continue;
                        }

                        if ($plugin->isTranslated()) {
                            $resources[] = $app["red_kite_cms.root_dir"] . '/app/plugins/RedKiteCms/Block/*/Resources/translations';

                            break;
                        }
                    }

                    $translationLoader = new TranslationLoader();
                    $translationLoader->registerResources($translator, $resources);
                    $translator->addLoader('xliff', $translationLoader);

                    return $translator;
                }
            )
        );

        $this->app['twig'] = $this->app->share(
            $this->app->extend(
                'twig',
                function ($twig, $app) {
                    $twig->addGlobal('cms_language', 'en');

                    return $twig;
                }
            )
        );

        $logFileName = $this->siteName;
        if ($this->app["debug"]){
            $logFileName .= '_dev';
        }
        $logPath = sprintf('%s/%s.log', $this->app["red_kite_cms.configuration_handler"]->logDir(), $logFileName);
        $level = $this->app["debug"] ? Logger::DEBUG : Logger::CRITICAL;
        $app->register(
            new MonologServiceProvider(),
            array(
                'monolog.name' => 'RedKiteCms',
                'monolog.logfile' => $logPath,
                'monolog.level' => $level,
            )
        );
    }

    private function registerServices()
    {
        $optionsResolver = new OptionsResolver();

        $this->app["jms.serializer"] = SerializerBuilder::create()->build();
        $this->app["red_kite_cms.plugin_manager"] = new PluginManager($this->app["red_kite_cms.configuration_handler"]);
        $this->app["red_kite_cms.slot_parser"] = new SlotParser($this->app["jms.serializer"]);
        $this->app["red_kite_cms.page"] = new Page(
            $this->app["jms.serializer"],
            clone $optionsResolver,
            $this->app["red_kite_cms.slot_parser"]
        );
        $this->app["red_kite_cms.block_factory"] = new BlockFactory($this->app["red_kite_cms.configuration_handler"]);
        $this->app["red_kite_cms.pages_collection_parser"] = new PagesCollectionParser($this->app["red_kite_cms.configuration_handler"]);
        $this->app["red_kite_cms.form_factory"] = new FormFactory(
            $this->app["red_kite_cms.configuration_handler"],
            $this->app["form.factory"],
            $this->app["red_kite_cms.pages_collection_parser"]
        );
        $this->app["red_kite_cms.assetic"] = new AsseticFactoryBuilder(
            $this->app["red_kite_cms.configuration_handler"]
        );
        $this->app["red_kite_cms.template_assets"] = new TemplateAssetsManager(
            $this->app["red_kite_cms.configuration_handler"], $this->app["red_kite_cms.assetic"]
        );
        $this->app["red_kite_cms.page_renderer_backend"] = new PageRendererBackend(
            $this->app["twig"],
            $this->app["red_kite_cms.pages_collection_parser"]
        );
        $this->app["red_kite_cms.page_renderer_production"] = new PageRendererProduction(
            $this->app["red_kite_cms.configuration_handler"], $this->app["jms.serializer"], $this->app["twig"]
        );
        $this->app["red_kite_cms.block_manager"] = new BlockManager(
            $this->app["jms.serializer"],
            $this->app["red_kite_cms.block_factory"],
            clone $optionsResolver
        );
        $this->app["red_kite_cms.slots_manager_factory"] = new SlotsManagerFactory(
            $this->app["red_kite_cms.configuration_handler"]
        );
        $this->app["red_kite_cms.theme_slot_manager"] = new ThemeSlotsManager(
            $this->app["red_kite_cms.configuration_handler"],
            $this->app["red_kite_cms.slots_manager_factory"],
            $this->app["red_kite_cms.block_manager"]
        );
        $this->app["red_kite_cms.page_collection_manager"] = new PageCollectionManager(
            $this->app["red_kite_cms.configuration_handler"],
            $this->app["red_kite_cms.slots_manager_factory"],
            $this->app["dispatcher"]
        );
        $this->app["red_kite_cms.page_manager"] = new PageManager(
            $this->app["red_kite_cms.configuration_handler"],
            $this->app["dispatcher"]
        );
        $this->app["red_kite_cms.elfinder_media_connector"] = new ElFinderMediaConnector(
            $this->app["red_kite_cms.configuration_handler"]
        );
        $this->app["red_kite_cms.elfinder_files_connector"] = new ElFinderFilesConnector(
            $this->app["red_kite_cms.configuration_handler"]
        );
        $this->app["red_kite_cms.permalink_manager"] = new PermalinkManager(
            $this->app["red_kite_cms.configuration_handler"]
        );
        $this->app["red_kite_cms.theme"] = new Theme(
            $this->app["red_kite_cms.configuration_handler"],
            $this->app["red_kite_cms.slots_manager_factory"]
        );
        $this->app["red_kite_cms.toolbar_manager"] = new ToolbarManager(
            $this->app["red_kite_cms.plugin_manager"],
            $this->app["twig"]
        );
    }

    private function registerListeners()
    {
        $this->app["red_kite_cms.listener.exception"] = new ExceptionListener(
            $this->app["twig"],
            $this->app["translator"],
            $this->app["debug"]
        );
        $this->app["dispatcher"]->addListener(
            'kernel.exception',
            array($this->app["red_kite_cms.listener.exception"], 'onKernelException')
        );
        $this->app["red_kite_cms.listener.cms_booting"] = new CmsBootingListener(
            $this->app["red_kite_cms.plugin_manager"]
        );
        $this->app["dispatcher"]->addListener(
            'cms.booting',
            array($this->app["red_kite_cms.listener.cms_booting"], 'onCmsBooting')
        );
        $this->app["red_kite_cms.listener.block_edited"] = new BlockEditingListener(
            $this->app["red_kite_cms.page_renderer_production"], $this->app["red_kite_cms.permalink_manager"]
        );
        $this->app["dispatcher"]->addListener(
            'block.editing',
            array($this->app["red_kite_cms.listener.block_edited"], 'onBlockEditing')
        );
        $this->app["red_kite_cms.listener.permalink_changed"] = new PermalinkChangedListener(
            $this->app["red_kite_cms.configuration_handler"], $this->app["red_kite_cms.permalink_manager"]
        );
        $this->app["dispatcher"]->addListener(
            'page.permalink_changed',
            array($this->app["red_kite_cms.listener.permalink_changed"], 'onPermalinkChanged')
        );
        $this->app["red_kite_cms.listener.page_removed"] = new PageRemovedListener(
            $this->app["red_kite_cms.pages_collection_parser"], $this->app["red_kite_cms.permalink_manager"]
        );
        $this->app["dispatcher"]->addListener(
            'page.collection.removed',
            array($this->app["red_kite_cms.listener.page_removed"], 'onPageRemoved')
        );
        $this->app["red_kite_cms.listener.template_changed"] = new TemplateChangedListener(
            $this->app["red_kite_cms.theme"], $this->app["red_kite_cms.configuration_handler"]
        );
        $this->app["dispatcher"]->addListener(
            'page.collection.template_changed',
            array($this->app["red_kite_cms.listener.template_changed"], 'onTemplateChanged')
        );
        $this->app["red_kite_cms.listener.page_saved"] = new PageSavedListener(
            $this->app["red_kite_cms.configuration_handler"], $this->app["red_kite_cms.page_renderer_production"]
        );
        $this->app["dispatcher"]->addListener(
            'page.saved',
            array($this->app["red_kite_cms.listener.page_saved"], 'onPageSaved')
        );
    }

    private function boot()
    {
        Dispatcher::setDispatcher($this->app["dispatcher"]);
        DataLogger::init($this->app["monolog"]);
        Translator::setTranslator($this->app["translator"]);

        $this->app["red_kite_cms.plugin_manager"]->boot();
        $theme = $this->app["red_kite_cms.plugin_manager"]->getActiveTheme();
        $this->app["red_kite_cms.theme"]->boot($theme);
        $this->app["red_kite_cms.theme_slot_manager"]->boot($theme);
        if ($theme->getName() === $this->app["red_kite_cms.configuration_handler"]->handledTheme()) {
            $this->app["red_kite_cms.theme_slot_manager"]->synchronizeThemeSlots();
        }
        $this->app["red_kite_cms.theme_slot_manager"]->createSlots();

        $siteIncompleteFile = $this->app["red_kite_cms.root_dir"] . '/app/data/' . $this->siteName . '/incomplete.json';
        if (file_exists($siteIncompleteFile)) {
            $user = null;
            if (!$this->app["red_kite_cms.configuration_handler"]->isTheme()) {
                $user = 'admin';
            }

            $this->app["red_kite_cms.page_collection_manager"]->contributor($user);
            $theme = $this->app["red_kite_cms.theme"];
            $this->app["red_kite_cms.page_collection_manager"]
                ->setDefaultPageName('homepage')
                ->add($theme, $theme->homepageTemplate());

            unlink($siteIncompleteFile);
        }

        $this->app["dispatcher"]->dispatch(
            CmsEvents::CMS_BOOTING,
            new CmsBootingEvent($this->app["red_kite_cms.configuration_handler"])
        );
        $this->app["red_kite_cms.block_factory"]->boot();
        $this->app["red_kite_cms.template_assets"]->boot();
        $this->app["red_kite_cms.assetic"]->addFilter('cssrewrite', new CssRewriteFilter());

        $this->app["dispatcher"]->dispatch(
            CmsEvents::CMS_BOOTED,
            new CmsBootedEvent($this->app["red_kite_cms.configuration_handler"])
        );
    }

    private function registerRoutes()
    {
        $backendRoutes = array(
            array(
                'pattern' => "/login",
                'controller' => 'Controller\Security\AuthenticationController::loginAction',
                'method' => array('get'),
            ),
            array(
                'pattern' => "/backend/dashboard",
                'controller' => 'Controller\Cms\DashboardController::showAction',
                'method' => array('get'),
                'bind' => '_rkcms_dashboard',
            ),
            array(
                'pattern' => "/backend",
                'controller' => 'Controller\Cms\BackendController::showAction',
                'method' => array('get'),
                'value' => array(
                    'page' => $this->app["red_kite_cms.configuration_handler"]->homepagePermalink(),
                    '_locale' => $this->app["red_kite_cms.configuration_handler"]->language(),
                    'country' => $this->app["red_kite_cms.configuration_handler"]->country(),
                ),
                'bind' => '_rkcms_homepage',
            ),
        );

        $blockRoutes = array(
            array(
                'pattern' => "/backend/block/add",
                'controller' => 'Controller\Block\AddBlockController::addAction',
                'method' => array('post'),
            ),
            array(
                'pattern' => "/backend/block/edit",
                'controller' => 'Controller\Block\EditBlockController::editAction',
                'method' => array('post'),
            ),
            array(
                'pattern' => "/backend/block/move",
                'controller' => 'Controller\Block\MoveBlockController::moveAction',
                'method' => array('post'),
            ),
            array(
                'pattern' => "/backend/block/remove",
                'controller' => 'Controller\Block\RemoveBlockController::removeAction',
                'method' => array('post'),
            ),
            array(
                'pattern' => "/backend/block/restore",
                'controller' => 'Controller\Block\RestoreBlockController::restoreAction',
                'method' => array('post'),
            ),
        );

        $pageRoutes = array(
            array(
                'pattern' => "/backend/page/collection/show",
                'controller' => 'Controller\PageCollection\ShowPageCollectionController::showAction',
                'method' => array('get'),
                'bind' => '_rkcms_show_pages',
            ),
            array(
                'pattern' => "/backend/page/collection/add",
                'controller' => 'Controller\PageCollection\AddPageCollectionController::addAction',
                'method' => array('post'),
            ),
            array(
                'pattern' => "/backend/page/collection/edit",
                'controller' => 'Controller\PageCollection\EditPageCollectionController::editAction',
                'method' => array('post'),
            ),
            array(
                'pattern' => "/backend/page/collection/remove",
                'controller' => 'Controller\PageCollection\RemovePageCollectionController::removeAction',
                'method' => array('post'),
            ),
            array(
                'pattern' => "/backend/page/collection/approve",
                'controller' => 'Controller\PageCollection\ApprovePageController::approvePageAction',
                'method' => array('post'),
            ),
            array(
                'pattern' => "/backend/page/save",
                'controller' => 'Controller\Page\SavePageController::saveAction',
                'method' => array('post'),
            ),
            array(
                'pattern' => "/backend/page/collection/save-all",
                'controller' => 'Controller\PageCollection\SaveAllPagesController::saveAction',
                'method' => array('post'),
            ),
        );

        $seoRoutes = array(
            array(
                'pattern' => "/backend/page/edit",
                'controller' => 'Controller\Page\EditPageController::editAction',
                'method' => array('post', 'get'),
            ),
            array(
                'pattern' => "/backend/page/approve",
                'controller' => 'Controller\Page\ApprovePageController::approveAction',
                'method' => array('post'),
            ),
            array(
                'pattern' => "/backend/page/publish",
                'controller' => 'Controller\Page\PublishPageController::publishAction',
                'method' => array('post'),
            ),
            array(
                'pattern' => "/backend/page/hide",
                'controller' => 'Controller\Page\HidePageController::hideAction',
                'method' => array('post'),
            ),
            array(
                'pattern' => "/backend/page/permalinks",
                'controller' => 'Controller\Page\PermalinksController::listPermalinksAction',
                'method' => array('get'),
            ),
        );

        $themeRoutes = array(
            array(
                'pattern' => "/backend/theme/show",
                'controller' => 'Controller\Theme\ShowThemeController::showAction',
                'method' => array('get'),
                'bind' => '_rkcms_show_themes',
            ),
            array(
                'pattern' => "/backend/theme/start",
                'controller' => 'Controller\Theme\StartFromThemeController::startAction',
                'method' => array('post'),
                'bind' => '_rkcms_start_themes',
            ),
            array(
                'pattern' => "/backend/theme/save",
                'controller' => 'Controller\Theme\SaveThemeController::saveAction',
                'method' => array('post'),
                'bind' => '_rkcms_save_theme',
            ),
        );

        $elFinder = array(
            array(
                'pattern' => "/backend/elfinder/media/connect",
                'controller' => 'Controller\ElFinder\ElFinderMediaController::mediaAction',
                'method' => array('get', 'post'),
                'bind' => '_rkcms_connect_elfinder_media',
            ),
            array(
                'pattern' => "/backend/elfinder/files/connect",
                'controller' => 'Controller\ElFinder\ElFinderFilesController::filesAction',
                'method' => array('get', 'post'),
                'bind' => '_rkcms_connect_elfinder_media',
            ),
        );

        $security = array(
            array(
                'pattern' => "/backend/users/show",
                'controller' => 'Controller\Security\ShowUserController::showAction',
                'method' => array('get'),
                'bind' => '_rkcms_show_users',
            ),
            array(
                'pattern' => "/backend/user/save",
                'controller' => 'Controller\Security\SaveUserController::saveAction',
                'method' => array('post'),
                'bind' => '_rkcms_save_user',
            ),
        );

        // FIXME This information comes from security and it is not available at this level
        $user = null;
        if (!$this->app["red_kite_cms.configuration_handler"]->isTheme()) {
            $user = 'admin';
        }

        $routingServiceProvider = new RoutingProvider();
        $routingServiceProvider->addRoutes($this->app, $blockRoutes);
        $routingServiceProvider->addRoutes($this->app, $pageRoutes);
        $routingServiceProvider->addRoutes($this->app, $seoRoutes);
        $routingServiceProvider->addRoutes($this->app, $themeRoutes);
        $routingServiceProvider->addRoutes($this->app, $elFinder);
        $routingServiceProvider->addRoutes($this->app, $security);
        $routingServiceProvider->addRoutes($this->app, $backendRoutes);

        $routingGenerator = new RoutingGenerator($this->app["red_kite_cms.configuration_handler"]);
        $websitePageRoutes = $this->app["red_kite_cms.configuration_handler"]->isProduction() ?
            $routingGenerator
                ->pattern('/')
                ->frontController('Controller\Cms\FrontendController::showAction')
                ->generate()
            :
            $routingGenerator
                ->pattern('/backend')
                ->frontController('Controller\Cms\BackendController::showAction')
                ->bindPrefix('_backend')
                ->explicitHomepageRoute(true)
                ->contributor($user)
                ->generate();
        $routingServiceProvider->addRoutes($this->app, $websitePageRoutes);
    }
}