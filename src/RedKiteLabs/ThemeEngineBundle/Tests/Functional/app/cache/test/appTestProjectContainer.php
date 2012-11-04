<?php
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;
class appTestProjectContainer extends Container
{
    public function __construct()
    {
        $this->parameters = $this->getDefaultParameters();
        $this->services =
        $this->scopedServices =
        $this->scopeStacks = array();
        $this->set('service_container', $this);
        $this->scopes = array('request' => 'container');
        $this->scopeChildren = array('request' => array());
    }
    protected function getAlphaLemonThemeEngine_404ErrorHandlerService()
    {
        return $this->services['alpha_lemon_theme_engine.404_error_handler'] = new \AlphaLemon\ThemeEngineBundle\Core\Listener\NotFoundErrorHandlerListener($this->get('templating'));
    }
    protected function getAlphaLemonThemeEngine_ThemesService()
    {
        $this->services['alpha_lemon_theme_engine.themes'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection();
        $instance->addTheme($this->get('business_website.theme'));
        return $instance;
    }
    protected function getAlphalemonBootstrap_AutoloadersCollectionService()
    {
        return $this->services['alphalemon_bootstrap.autoloaders_collection'] = new \AlphaLemon\BootstrapBundle\Core\Json\JsonAutoloaderCollection('/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/../vendor');
    }
    protected function getAlphalemonBootstrap_PostActionsService()
    {
        return $this->services['alphalemon_bootstrap.post_actions'] = new \AlphaLemon\BootstrapBundle\Core\Listener\ExecutePostActionsListener($this);
    }
    protected function getAlphalemonBootstrap_RoutingLoaderService()
    {
        return $this->services['alphalemon_bootstrap.routing_loader'] = new \AlphaLemon\BootstrapBundle\Core\Loader\RoutingLoader($this->get('file_locator'), $this->get('alphalemon_bootstrap.autoloaders_collection'), '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/config/bundles/routing');
    }
    protected function getAlphalemonThemeEngine_ActiveThemeService()
    {
        return $this->services['alphalemon_theme_engine.active_theme'] = new \AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveTheme($this);
    }
    protected function getAlphalemonThemeEngine_PageTreeService()
    {
        return $this->services['alphalemon_theme_engine.page_tree'] = new \AlphaLemon\ThemeEngineBundle\Core\PageTree\AlPageTree($this);
    }
    protected function getAnnotationReaderService()
    {
        return $this->services['annotation_reader'] = new \Doctrine\Common\Annotations\FileCacheReader(new \Doctrine\Common\Annotations\AnnotationReader(), '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/annotations', false);
    }
    protected function getAssetic_AssetManagerService()
    {
        $this->services['assetic.asset_manager'] = $instance = new \Assetic\Factory\LazyAssetManager($this->get('assetic.asset_factory'), array('twig' => new \Assetic\Factory\Loader\CachedFormulaLoader(new \Assetic\Extension\Twig\TwigFormulaLoader($this->get('twig')), new \Assetic\Cache\ConfigCache('/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/assetic/config'), false)));
        $instance->addResource(new \Symfony\Bundle\AsseticBundle\Factory\Resource\DirectoryResource($this->get('templating.loader'), '', '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/Resources/views', '/\\.[^.]+\\.twig$/'), 'twig');
        return $instance;
    }
    protected function getAssetic_ControllerService()
    {
        $instance = new \Symfony\Bundle\AsseticBundle\Controller\AsseticController($this->get('request'), $this->get('assetic.asset_manager'), $this->get('assetic.cache'), false, $this->get('profiler'));
        $instance->setValueSupplier($this->get('assetic.value_supplier.default'));
        return $instance;
    }
    protected function getAssetic_Filter_CssrewriteService()
    {
        return $this->services['assetic.filter.cssrewrite'] = new \Assetic\Filter\CssRewriteFilter();
    }
    protected function getAssetic_Filter_YuiCssService()
    {
        $this->services['assetic.filter.yui_css'] = $instance = new \Assetic\Filter\Yui\CssCompressorFilter('/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/Resources/java/yuicompressor.jar', '/usr/bin/java');
        $instance->setCharset('UTF-8');
        return $instance;
    }
    protected function getAssetic_Filter_YuiJsService()
    {
        $this->services['assetic.filter.yui_js'] = $instance = new \Assetic\Filter\Yui\JsCompressorFilter('/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/Resources/java/yuicompressor.jar', '/usr/bin/java');
        $instance->setCharset('UTF-8');
        $instance->setNomunge(NULL);
        $instance->setPreserveSemi(NULL);
        $instance->setDisableOptimizations(NULL);
        return $instance;
    }
    protected function getAssetic_FilterManagerService()
    {
        return $this->services['assetic.filter_manager'] = new \Symfony\Bundle\AsseticBundle\FilterManager($this, array('cssrewrite' => 'assetic.filter.cssrewrite', 'yui_css' => 'assetic.filter.yui_css', 'yui_js' => 'assetic.filter.yui_js'));
    }
    protected function getAssetic_RequestListenerService()
    {
        return $this->services['assetic.request_listener'] = new \Symfony\Bundle\AsseticBundle\EventListener\RequestListener();
    }
    protected function getBusinessWebsite_ThemeService()
    {
        $this->services['business_website.theme'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\Theme\AlTheme('BusinessWebsiteTheme');
        $instance->addTemplate($this->get('business_website.theme.template.home'));
        $instance->addTemplate($this->get('business_website.theme.template.fullpage'));
        $instance->addTemplate($this->get('business_website.theme.template.rightcolumn'));
        $instance->addTemplate($this->get('business_website.theme.template.sixboxes'));
        return $instance;
    }
    protected function getBusinessWebsite_Theme_Template_Base_Slots_CopyrightBoxService()
    {
        return $this->services['business_website.theme.template.base.slots.copyright_box'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('copyright_box', array('repeated' => 'site', 'htmlContent' => '
                    <a href="#">(C) Copyright Progress Business Company</a>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Base_Slots_FooterTitle1Service()
    {
        return $this->services['business_website.theme.template.base.slots.footer_title_1'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('footer_title_1', array('repeated' => 'language', 'htmlContent' => '
                    <h4>Why Us?</h4>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Base_Slots_FooterTitle2Service()
    {
        return $this->services['business_website.theme.template.base.slots.footer_title_2'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('footer_title_2', array('repeated' => 'language', 'htmlContent' => '
                    <h4>Address</h4>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Base_Slots_FooterTitle3Service()
    {
        return $this->services['business_website.theme.template.base.slots.footer_title_3'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('footer_title_3', array('repeated' => 'language', 'htmlContent' => '
                    <h4>Follow Us</h4>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Base_Slots_FooterTitle4Service()
    {
        return $this->services['business_website.theme.template.base.slots.footer_title_4'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('footer_title_4', array('repeated' => 'language', 'htmlContent' => '
                    <h4>Newsletter</h4>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Base_Slots_LogoService()
    {
        return $this->services['business_website.theme.template.base.slots.logo'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('logo', array('repeated' => 'site', 'htmlContent' => '
                    <img src="/uploads/assets/media/business-website-original-logo.png" title="Progress website logo" alt="Progress website logo" />
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Base_Slots_NavMenuService()
    {
        return $this->services['business_website.theme.template.base.slots.nav_menu'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('nav_menu', array('repeated' => 'language', 'blockType' => 'BusinessMenu'));
    }
    protected function getBusinessWebsite_Theme_Template_Base_Slots_NavMenu1Service()
    {
        return $this->services['business_website.theme.template.base.slots.nav_menu_1'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('nav_menu_1', array('repeated' => 'language', 'htmlContent' => '
                    <ul class="list1"><li><a href="#">Lorem ipsum dolor sit</a></li><li><a href="#">Dmet, consectetur</a></li><li><a href="#">Adipisicing elit eiusmod </a></li><li><a href="#">Tempor incididunt ut</a></li></ul>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Base_Slots_NavMenu2Service()
    {
        return $this->services['business_website.theme.template.base.slots.nav_menu_2'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('nav_menu_2', array('repeated' => 'language', 'htmlContent' => '
                    <ul class="address"><li><span>Country:</span>USA</li><li><span>City:</span>San Diego</li><li><span>Phone:</span>8 800 154-45-67</li><li><span>Email:</span><a href="mailto:">progress@mail.com</a></li></ul>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Base_Slots_NavMenu3Service()
    {
        return $this->services['business_website.theme.template.base.slots.nav_menu_3'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('nav_menu_3', array('repeated' => 'language', 'htmlContent' => '
                    <ul id="icons"><li><a href="#"><img src="/bundles/businesswebsitetheme/images/icon1.jpg" alt="">Facebook</a></li><li><a href="#"><img src="/bundles/businesswebsitetheme/images/icon2.jpg" alt="">Twitter</a></li><li><a href="#"><img src="/bundles/businesswebsitetheme/images/icon3.jpg" alt="">LinkedIn</a></li><li><a href="#"><img src="/bundles/businesswebsitetheme/images/icon4.jpg" alt="">Delicious</a></li></ul>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Base_Slots_NewsletterBoxService()
    {
        return $this->services['business_website.theme.template.base.slots.newsletter_box'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('newsletter_box', array('repeated' => 'language', 'htmlContent' => '
                    <form id="newsletter" method="post"><div><div class="wrapper"><input class="input" type="text" value="Type Your Email Here"  onblur="if(this.value==\'\') this.value=\'Type Your Email Here\'" onfocus="if(this.value ==\'Type Your Email Here\' ) this.value=\'\'" ></div><a href="#" class="button" onclick="document.getElementById(\'newsletter\').submit()">Subscribe</a></div></form>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_FullpageService()
    {
        $this->services['business_website.theme.template.fullpage'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate($this->get('kernel'), $this->get('business_website.theme.template_assets.fullpage'), $this->get('business_website.theme.template.fullpage.slots'));
        $instance->setThemeName('BusinessWebsiteThemeBundle');
        $instance->setTemplateName('fullpage');
        return $instance;
    }
    protected function getBusinessWebsite_Theme_Template_Fullpage_SlotsService()
    {
        $this->services['business_website.theme.template.fullpage.slots'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots();
        $instance->addSlot($this->get('business_website.theme.template.base.slots.logo'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_1'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu_1'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_2'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu_2'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_3'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu_3'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_4'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.newsletter_box'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.copyright_box'));
        $instance->addSlot($this->get('business_website.theme.template.fullpage.slots.page_content'));
        return $instance;
    }
    protected function getBusinessWebsite_Theme_Template_Fullpage_Slots_PageContentService()
    {
        return $this->services['business_website.theme.template.fullpage.slots.page_content'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('page_content', array('htmlContent' => '
                    <h5><span class="dropcap"><strong>28</strong><span>06</span></span>Lorem ipsum dolor sit amet consectetur adipisicing elit</h5><div class="wrapper pad_bot2"><figure class="left marg_right1"><img src="/bundles/businesswebsitetheme/images/page2_img1.jpg" alt=""></figure><p class="pad_bot1">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.</p><a href="#" class="link1">Read More</a></div><h5><span class="dropcap"><strong>25</strong><span>06</span></span>Duis aute irure dolor in reprehenderit</h5><div class="wrapper pad_bot2"><figure class="left marg_right1"><img src="/bundles/businesswebsitetheme/images/page2_img2.jpg" alt=""></figure><p class="pad_bot1">Sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur.</p><a href="#" class="link1">Read More</a></div><h5><span class="dropcap"><strong>21</strong><span>06</span></span>Sed ut perspiciatis unde omnis iste natus error sit voluptatem</h5><div class="wrapper pad_bot2"><figure class="left marg_right1"><img src="/bundles/businesswebsitetheme/images/page2_img3.jpg" alt=""></figure><p class="pad_bot1">Vel illum qui dolorem eum fugiat quo voluptas nulla pariatur. At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis.</p><a href="#" class="link1">Read More</a></div><ul class="nav"><li class="selected"><a href="#tab1">1</a></li><li><a href="#tab2">2</a></li><li><a href="#tab3">3</a></li><li><a href="#tab4">4</a></li><li><a href="#tab5">5</a></li><li><a href="#tab6">6</a></li></ul>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_HomeService()
    {
        $this->services['business_website.theme.template.home'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate($this->get('kernel'), $this->get('business_website.theme.template_assets.home'), $this->get('business_website.theme.template.home.slots'));
        $instance->setThemeName('BusinessWebsiteThemeBundle');
        $instance->setTemplateName('home');
        return $instance;
    }
    protected function getBusinessWebsite_Theme_Template_Home_SlotsService()
    {
        $this->services['business_website.theme.template.home.slots'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots();
        $instance->addSlot($this->get('business_website.theme.template.base.slots.logo'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_1'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu_1'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_2'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu_2'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_3'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu_3'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_4'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.newsletter_box'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.copyright_box'));
        $instance->addSlot($this->get('business_website.theme.template.home.slots.logo'));
        $instance->addSlot($this->get('business_website.theme.template.home.slots.nav_menu'));
        $instance->addSlot($this->get('business_website.theme.template.home.slots.slider_box'));
        $instance->addSlot($this->get('business_website.theme.template.home.slots.top_section_title_1'));
        $instance->addSlot($this->get('business_website.theme.template.home.slots.top_section_1'));
        $instance->addSlot($this->get('business_website.theme.template.home.slots.top_section_title_2'));
        $instance->addSlot($this->get('business_website.theme.template.home.slots.top_section_2'));
        $instance->addSlot($this->get('business_website.theme.template.home.slots.top_section_title_3'));
        $instance->addSlot($this->get('business_website.theme.template.home.slots.top_section_3'));
        $instance->addSlot($this->get('business_website.theme.template.home.slots.top_section_title_4'));
        $instance->addSlot($this->get('business_website.theme.template.home.slots.top_section_4'));
        $instance->addSlot($this->get('business_website.theme.template.home.slots.left_sidebar_content'));
        $instance->addSlot($this->get('business_website.theme.template.home.slots.right_sidebar_content'));
        return $instance;
    }
    protected function getBusinessWebsite_Theme_Template_Home_Slots_LeftSidebarContentService()
    {
        return $this->services['business_website.theme.template.home.slots.left_sidebar_content'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('left_sidebar_content', array('htmlContent' => '
                    <h2>Welcome, dear visitor!</h2><figure class="left marg_right1"><img src="/bundles/businesswebsitetheme/images/page1_img1.jpg" alt=""></figure><p class="pad_bot1">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa.</p><p>Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus.</p>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Home_Slots_LogoService()
    {
        return $this->services['business_website.theme.template.home.slots.logo'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('logo', array('repeated' => 'site', 'htmlContent' => '
                    <img src="/uploads/assets/media/business-website-original-logo.png" title="Progress website logo" alt="Progress website logo" />
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Home_Slots_NavMenuService()
    {
        return $this->services['business_website.theme.template.home.slots.nav_menu'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('nav_menu', array('repeated' => 'language', 'blockType' => 'BusinessMenu'));
    }
    protected function getBusinessWebsite_Theme_Template_Home_Slots_RightSidebarContentService()
    {
        return $this->services['business_website.theme.template.home.slots.right_sidebar_content'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('right_sidebar_content', array('blockType' => 'BusinessCarousel'));
    }
    protected function getBusinessWebsite_Theme_Template_Home_Slots_SliderBoxService()
    {
        return $this->services['business_website.theme.template.home.slots.slider_box'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('slider_box', array('blockType' => 'BusinessSlider'));
    }
    protected function getBusinessWebsite_Theme_Template_Home_Slots_TopSection1Service()
    {
        return $this->services['business_website.theme.template.home.slots.top_section_1'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('top_section_1', array('htmlContent' => '
                    <p class="pad_bot1">Progress is one of <a href="http://blog.templatemonster.com/free-website-templates/" target="_blank">free website templates</a> created by TemplateMonster.com, optimized for 1024x768 res.</p><a href="#" class="link1">Read More</a>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Home_Slots_TopSection2Service()
    {
        return $this->services['business_website.theme.template.home.slots.top_section_2'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('top_section_2', array('htmlContent' => '
                    <p class="pad_bot1">This <a href="http://blog.templatemonster.com/2011/07/11/free-website-template-slider-typography/">Progress Template</a> goes with two packages â€“ with PSD source files and without them.</p><a href="#" class="link1">Read More</a>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Home_Slots_TopSection3Service()
    {
        return $this->services['business_website.theme.template.home.slots.top_section_3'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('top_section_3', array('htmlContent' => '
                    <p class="pad_bot1">PSD source files are available for free for registered members. The basic package is available for anyone.</p><a href="#" class="link1">Read More</a>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Home_Slots_TopSection4Service()
    {
        return $this->services['business_website.theme.template.home.slots.top_section_4'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('top_section_4', array('htmlContent' => '
                    <p class="pad_bot1">This website template has several pages: Home, News, Services, Products, Contacts (contact form doesnâ€™t work).</p><a href="#" class="link1">Read More</a>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Home_Slots_TopSectionTitle1Service()
    {
        return $this->services['business_website.theme.template.home.slots.top_section_title_1'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('top_section_title_1', array('blockType' => 'BusinessDropCap'));
    }
    protected function getBusinessWebsite_Theme_Template_Home_Slots_TopSectionTitle2Service()
    {
        return $this->services['business_website.theme.template.home.slots.top_section_title_2'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('top_section_title_2', array('blockType' => 'BusinessDropCap'));
    }
    protected function getBusinessWebsite_Theme_Template_Home_Slots_TopSectionTitle3Service()
    {
        return $this->services['business_website.theme.template.home.slots.top_section_title_3'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('top_section_title_3', array('blockType' => 'BusinessDropCap'));
    }
    protected function getBusinessWebsite_Theme_Template_Home_Slots_TopSectionTitle4Service()
    {
        return $this->services['business_website.theme.template.home.slots.top_section_title_4'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('top_section_title_4', array('blockType' => 'BusinessDropCap'));
    }
    protected function getBusinessWebsite_Theme_Template_RightcolumnService()
    {
        $this->services['business_website.theme.template.rightcolumn'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate($this->get('kernel'), $this->get('business_website.theme.template_assets.rightcolumn'), $this->get('business_website.theme.template.rightcolumn.slots'));
        $instance->setThemeName('BusinessWebsiteThemeBundle');
        $instance->setTemplateName('rightcolumn');
        return $instance;
    }
    protected function getBusinessWebsite_Theme_Template_Rightcolumn_SlotsService()
    {
        $this->services['business_website.theme.template.rightcolumn.slots'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots();
        $instance->addSlot($this->get('business_website.theme.template.base.slots.logo'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_1'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu_1'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_2'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu_2'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_3'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu_3'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_4'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.newsletter_box'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.copyright_box'));
        $instance->addSlot($this->get('business_website.theme.template.rightcolumn.slots.middle_sidebar'));
        $instance->addSlot($this->get('business_website.theme.template.rightcolumn.slots.right_sidebar'));
        return $instance;
    }
    protected function getBusinessWebsite_Theme_Template_Rightcolumn_Slots_MiddleSidebarService()
    {
        return $this->services['business_website.theme.template.rightcolumn.slots.middle_sidebar'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('middle_sidebar', array('htmlContent' => '
                    <h2 class="under">Contact form</h2><form id="ContactForm" method="post"><div><div  class="wrapper"><span>Your Name:</span><input type="text" class="input" ></div><div  class="wrapper"><span>Your City:</span><input type="text" class="input" ></div><div  class="wrapper"><span>Your E-mail:</span><input type="text" class="input" ></div><div  class="textarea_box"><span>Your Message:</span><textarea name="textarea" cols="1" rows="1"></textarea></div><a href="#" onClick="document.getElementById(\'ContactForm\').submit()">Send</a><a href="#" onClick="document.getElementById(\'ContactForm\').reset()">Clear</a></div></form>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Rightcolumn_Slots_RightSidebarService()
    {
        return $this->services['business_website.theme.template.rightcolumn.slots.right_sidebar'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('right_sidebar', array('htmlContent' => '
                    <h2 class="under">Contacts</h2><div id="address"><span>Country:<br>
City:<br>
Telephone:<br>
Email:</span>
USA<br>
San Diego<br>
+354 5635600<br><a href="mailto:" class="color2">elenwhite@mail.com</a></div><h2 class="under">Miscellaneous</h2><p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium volupta- tum deleniti atque corrupti quos dolores et quas molestias excep- turi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum.</p>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_SixboxesService()
    {
        $this->services['business_website.theme.template.sixboxes'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplate($this->get('kernel'), $this->get('business_website.theme.template_assets.sixboxes'), $this->get('business_website.theme.template.sixboxes.slots'));
        $instance->setThemeName('BusinessWebsiteThemeBundle');
        $instance->setTemplateName('sixboxes');
        return $instance;
    }
    protected function getBusinessWebsite_Theme_Template_Sixboxes_SlotsService()
    {
        $this->services['business_website.theme.template.sixboxes.slots'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlTemplateSlots();
        $instance->addSlot($this->get('business_website.theme.template.base.slots.logo'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_1'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu_1'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_2'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu_2'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_3'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.nav_menu_3'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.footer_title_4'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.newsletter_box'));
        $instance->addSlot($this->get('business_website.theme.template.base.slots.copyright_box'));
        $instance->addSlot($this->get('business_website.theme.template.sixboxes.slots.content_1'));
        $instance->addSlot($this->get('business_website.theme.template.sixboxes.slots.content_2'));
        $instance->addSlot($this->get('business_website.theme.template.sixboxes.slots.content_3'));
        $instance->addSlot($this->get('business_website.theme.template.sixboxes.slots.content_4'));
        $instance->addSlot($this->get('business_website.theme.template.sixboxes.slots.content_5'));
        $instance->addSlot($this->get('business_website.theme.template.sixboxes.slots.content_6'));
        return $instance;
    }
    protected function getBusinessWebsite_Theme_Template_Sixboxes_Slots_Content1Service()
    {
        return $this->services['business_website.theme.template.sixboxes.slots.content_1'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('content_1', array('htmlContent' => '
                    <h3><span class="dropcap">1</span>Product name</h3><figure><img src="/bundles/businesswebsitetheme/images/page4_img1.jpg" alt=""></figure><p class="pad_bot1">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</p><a href="#" class="link1">Read More</a>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Sixboxes_Slots_Content2Service()
    {
        return $this->services['business_website.theme.template.sixboxes.slots.content_2'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('content_2', array('htmlContent' => '
                    <h3><span class="dropcap">4</span>Product name</h3><figure><img src="/bundles/businesswebsitetheme/images/page4_img2.jpg" alt=""></figure><p class="pad_bot1">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia dese- runt mollit anim id est laborum.</p><a href="#" class="link1">Read More</a>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Sixboxes_Slots_Content3Service()
    {
        return $this->services['business_website.theme.template.sixboxes.slots.content_3'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('content_3', array('htmlContent' => '
                    <h3><span class="dropcap">2</span>Product name</h3><figure><img src="/bundles/businesswebsitetheme/images/page4_img3.jpg" alt=""></figure><p class="pad_bot1">Dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip exea.</p><a href="#" class="link1">Read More</a>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Sixboxes_Slots_Content4Service()
    {
        return $this->services['business_website.theme.template.sixboxes.slots.content_4'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('content_4', array('htmlContent' => '
                    <h3><span class="dropcap">5</span>Product name</h3><figure><img src="/bundles/businesswebsitetheme/images/page4_img4.jpg" alt=""></figure><p class="pad_bot1">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p><a href="#" class="link1">Read More</a>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Sixboxes_Slots_Content5Service()
    {
        return $this->services['business_website.theme.template.sixboxes.slots.content_5'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('content_5', array('htmlContent' => '
                    <h3><span class="dropcap">3</span>Product name</h3><figure><img src="/bundles/businesswebsitetheme/images/page4_img5.jpg" alt=""></figure><p class="pad_bot1">Commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</p><a href="#" class="link1">Read More</a>
                '));
    }
    protected function getBusinessWebsite_Theme_Template_Sixboxes_Slots_Content6Service()
    {
        return $this->services['business_website.theme.template.sixboxes.slots.content_6'] = new \AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot('content_6', array('htmlContent' => '
                    <h3><span class="dropcap">6</span>Product name</h3><figure><img src="/bundles/businesswebsitetheme/images/page4_img6.jpg" alt=""></figure><p class="pad_bot1">Totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p><a href="#" class="link1">Read More</a>
                '));
    }
    protected function getBusinessWebsite_Theme_TemplateAssets_FullpageService()
    {
        $this->services['business_website.theme.template_assets.fullpage'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplateAssets();
        $instance->setExternalStylesheets(array(0 => '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css', 1 => '@BusinessWebsiteThemeBundle/Resources/public/css/layout.css', 2 => '@BusinessWebsiteThemeBundle/Resources/public/css/style.css', 3 => '@BusinessWebsiteThemeBundle/Resources/public/css/al_fix_style.css'));
        $instance->setExternalJavascripts(array(0 => '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*', 1 => '@BusinessWebsiteThemeBundle/Resources/public/js/cufon-yui.js', 2 => '@BusinessWebsiteThemeBundle/Resources/public/js//al-cufon-replace.js', 3 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_400.font.js', 4 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_700.font.js', 5 => '@BusinessWebsiteThemeBundle/Resources/public/js/tabs.js'));
        return $instance;
    }
    protected function getBusinessWebsite_Theme_TemplateAssets_HomeService()
    {
        $this->services['business_website.theme.template_assets.home'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplateAssets();
        $instance->setExternalStylesheets(array(0 => '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css', 1 => '@BusinessWebsiteThemeBundle/Resources/public/css/layout.css', 2 => '@BusinessWebsiteThemeBundle/Resources/public/css/style.css', 3 => '@BusinessWebsiteThemeBundle/Resources/public/css/al_fix_style.css'));
        $instance->setExternalJavascripts(array(0 => '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*', 1 => '@BusinessWebsiteThemeBundle/Resources/public/js/cufon-yui.js', 2 => '@BusinessWebsiteThemeBundle/Resources/public/js/al-cufon-replace.js', 3 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_400.font.js', 4 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_700.font.js', 5 => '@BusinessWebsiteThemeBundle/Resources/public/js/jquery.easing.1.3.js', 6 => '@BusinessWebsiteThemeBundle/Resources/public/js/jcarousellite.js'));
        return $instance;
    }
    protected function getBusinessWebsite_Theme_TemplateAssets_RightcolumnService()
    {
        $this->services['business_website.theme.template_assets.rightcolumn'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplateAssets();
        $instance->setExternalStylesheets(array(0 => '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css', 1 => '@BusinessWebsiteThemeBundle/Resources/public/css/layout.css', 2 => '@BusinessWebsiteThemeBundle/Resources/public/css/style.css', 3 => '@BusinessWebsiteThemeBundle/Resources/public/css/al_fix_style.css'));
        $instance->setExternalJavascripts(array(0 => '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*', 1 => '@BusinessWebsiteThemeBundle/Resources/public/js/cufon-yui.js', 2 => '@BusinessWebsiteThemeBundle/Resources/public/js//al-cufon-replace.js', 3 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_400.font.js', 4 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_700.font.js', 5 => '@BusinessWebsiteThemeBundle/Resources/public/js/tabs.js'));
        return $instance;
    }
    protected function getBusinessWebsite_Theme_TemplateAssets_SixboxesService()
    {
        $this->services['business_website.theme.template_assets.sixboxes'] = $instance = new \AlphaLemon\ThemeEngineBundle\Core\Template\AlTemplateAssets();
        $instance->setExternalStylesheets(array(0 => '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css', 1 => '@BusinessWebsiteThemeBundle/Resources/public/css/layout.css', 2 => '@BusinessWebsiteThemeBundle/Resources/public/css/style.css', 3 => '@BusinessWebsiteThemeBundle/Resources/public/css/al_fix_style.css'));
        $instance->setExternalJavascripts(array(0 => '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*', 1 => '@BusinessWebsiteThemeBundle/Resources/public/js/cufon-yui.js', 2 => '@BusinessWebsiteThemeBundle/Resources/public/js//al-cufon-replace.js', 3 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_400.font.js', 4 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_700.font.js', 5 => '@BusinessWebsiteThemeBundle/Resources/public/js/tabs.js'));
        return $instance;
    }
    protected function getCacheClearerService()
    {
        return $this->services['cache_clearer'] = new \Symfony\Component\HttpKernel\CacheClearer\ChainCacheClearer(array());
    }
    protected function getCacheWarmerService()
    {
        $a = $this->get('kernel');
        $b = $this->get('templating.filename_parser');
        $c = new \Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplateFinder($a, $b, '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/Resources');
        return $this->services['cache_warmer'] = new \Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate(array(0 => new \Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplatePathsCacheWarmer($c, $this->get('templating.locator')), 1 => new \Symfony\Bundle\AsseticBundle\CacheWarmer\AssetManagerCacheWarmer($this), 2 => new \Symfony\Bundle\FrameworkBundle\CacheWarmer\RouterCacheWarmer($this->get('router')), 3 => new \Symfony\Bundle\TwigBundle\CacheWarmer\TemplateCacheCacheWarmer($this, $c), 4 => new \JMS\DiExtraBundle\HttpKernel\ControllerInjectorsWarmer($a, $this->get('jms_di_extra.controller_resolver'))));
    }
    protected function getDataCollector_RequestService()
    {
        return $this->services['data_collector.request'] = new \Symfony\Component\HttpKernel\DataCollector\RequestDataCollector();
    }
    protected function getDataCollector_RouterService()
    {
        return $this->services['data_collector.router'] = new \Symfony\Bundle\FrameworkBundle\DataCollector\RouterDataCollector();
    }
    protected function getEventDispatcherService()
    {
        $this->services['event_dispatcher'] = $instance = new \Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher($this);
        $instance->addListenerService('kernel.controller', array(0 => 'data_collector.router', 1 => 'onKernelController'), 0);
        $instance->addListenerService('kernel.request', array(0 => 'security.firewall', 1 => 'onKernelRequest'), 8);
        $instance->addListenerService('kernel.response', array(0 => 'security.rememberme.response_listener', 1 => 'onKernelResponse'), 0);
        $instance->addListenerService('kernel.response', array(0 => 'monolog.handler.firephp', 1 => 'onKernelResponse'), 0);
        $instance->addListenerService('kernel.request', array(0 => 'assetic.request_listener', 1 => 'onKernelRequest'), 0);
        $instance->addListenerService('kernel.controller', array(0 => 'sensio_framework_extra.controller.listener', 1 => 'onKernelController'), 0);
        $instance->addListenerService('kernel.controller', array(0 => 'sensio_framework_extra.converter.listener', 1 => 'onKernelController'), 0);
        $instance->addListenerService('kernel.controller', array(0 => 'sensio_framework_extra.view.listener', 1 => 'onKernelController'), 0);
        $instance->addListenerService('kernel.view', array(0 => 'sensio_framework_extra.view.listener', 1 => 'onKernelView'), 0);
        $instance->addListenerService('kernel.response', array(0 => 'sensio_framework_extra.cache.listener', 1 => 'onKernelResponse'), 0);
        $instance->addListenerService('kernel.request', array(0 => 'alphalemon_bootstrap.post_actions', 1 => 'onKernelRequest'), 99999);
        $instance->addListenerService('kernel.exception', array(0 => 'alpha_lemon_theme_engine.404_error_handler', 1 => 'onKernelException'), 255);
        $instance->addSubscriberService('response_listener', 'Symfony\\Component\\HttpKernel\\EventListener\\ResponseListener');
        $instance->addSubscriberService('streamed_response_listener', 'Symfony\\Component\\HttpKernel\\EventListener\\StreamedResponseListener');
        $instance->addSubscriberService('locale_listener', 'Symfony\\Component\\HttpKernel\\EventListener\\LocaleListener');
        $instance->addSubscriberService('test.session.listener', 'Symfony\\Bundle\\FrameworkBundle\\EventListener\\TestSessionListener');
        $instance->addSubscriberService('session_listener', 'Symfony\\Bundle\\FrameworkBundle\\EventListener\\SessionListener');
        $instance->addSubscriberService('profiler_listener', 'Symfony\\Component\\HttpKernel\\EventListener\\ProfilerListener');
        $instance->addSubscriberService('data_collector.request', 'Symfony\\Component\\HttpKernel\\DataCollector\\RequestDataCollector');
        $instance->addSubscriberService('router_listener', 'Symfony\\Component\\HttpKernel\\EventListener\\RouterListener');
        $instance->addSubscriberService('twig.exception_listener', 'Symfony\\Component\\HttpKernel\\EventListener\\ExceptionListener');
        $instance->addSubscriberService('swiftmailer.email_sender.listener', 'Symfony\\Bundle\\SwiftmailerBundle\\EventListener\\EmailSenderListener');
        $instance->addSubscriberService('web_profiler.debug_toolbar', 'Symfony\\Bundle\\WebProfilerBundle\\EventListener\\WebDebugToolbarListener');
        return $instance;
    }
    protected function getFileLocatorService()
    {
        return $this->services['file_locator'] = new \Symfony\Component\HttpKernel\Config\FileLocator($this->get('kernel'), '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/Resources');
    }
    protected function getFilesystemService()
    {
        return $this->services['filesystem'] = new \Symfony\Component\Filesystem\Filesystem();
    }
    protected function getForm_CsrfProviderService()
    {
        return $this->services['form.csrf_provider'] = new \Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider($this->get('session'), 'ThisTokenIsNotSoSecretChangeIt');
    }
    protected function getForm_FactoryService()
    {
        return $this->services['form.factory'] = new \Symfony\Component\Form\FormFactory($this->get('form.registry'), $this->get('form.resolved_type_factory'));
    }
    protected function getForm_RegistryService()
    {
        return $this->services['form.registry'] = new \Symfony\Component\Form\FormRegistry(array(0 => new \Symfony\Component\Form\Extension\DependencyInjection\DependencyInjectionExtension($this, array('field' => 'form.type.field', 'form' => 'form.type.form', 'birthday' => 'form.type.birthday', 'checkbox' => 'form.type.checkbox', 'choice' => 'form.type.choice', 'collection' => 'form.type.collection', 'country' => 'form.type.country', 'date' => 'form.type.date', 'datetime' => 'form.type.datetime', 'email' => 'form.type.email', 'file' => 'form.type.file', 'hidden' => 'form.type.hidden', 'integer' => 'form.type.integer', 'language' => 'form.type.language', 'locale' => 'form.type.locale', 'money' => 'form.type.money', 'number' => 'form.type.number', 'password' => 'form.type.password', 'percent' => 'form.type.percent', 'radio' => 'form.type.radio', 'repeated' => 'form.type.repeated', 'search' => 'form.type.search', 'textarea' => 'form.type.textarea', 'text' => 'form.type.text', 'time' => 'form.type.time', 'timezone' => 'form.type.timezone', 'url' => 'form.type.url'), array('form' => array(0 => 'form.type_extension.form.http_foundation', 1 => 'form.type_extension.form.validator', 2 => 'form.type_extension.csrf'), 'repeated' => array(0 => 'form.type_extension.repeated.validator')), array(0 => 'form.type_guesser.validator'))), $this->get('form.resolved_type_factory'));
    }
    protected function getForm_ResolvedTypeFactoryService()
    {
        return $this->services['form.resolved_type_factory'] = new \Symfony\Component\Form\ResolvedFormTypeFactory();
    }
    protected function getForm_Type_BirthdayService()
    {
        return $this->services['form.type.birthday'] = new \Symfony\Component\Form\Extension\Core\Type\BirthdayType();
    }
    protected function getForm_Type_CheckboxService()
    {
        return $this->services['form.type.checkbox'] = new \Symfony\Component\Form\Extension\Core\Type\CheckboxType();
    }
    protected function getForm_Type_ChoiceService()
    {
        return $this->services['form.type.choice'] = new \Symfony\Component\Form\Extension\Core\Type\ChoiceType();
    }
    protected function getForm_Type_CollectionService()
    {
        return $this->services['form.type.collection'] = new \Symfony\Component\Form\Extension\Core\Type\CollectionType();
    }
    protected function getForm_Type_CountryService()
    {
        return $this->services['form.type.country'] = new \Symfony\Component\Form\Extension\Core\Type\CountryType();
    }
    protected function getForm_Type_DateService()
    {
        return $this->services['form.type.date'] = new \Symfony\Component\Form\Extension\Core\Type\DateType();
    }
    protected function getForm_Type_DatetimeService()
    {
        return $this->services['form.type.datetime'] = new \Symfony\Component\Form\Extension\Core\Type\DateTimeType();
    }
    protected function getForm_Type_EmailService()
    {
        return $this->services['form.type.email'] = new \Symfony\Component\Form\Extension\Core\Type\EmailType();
    }
    protected function getForm_Type_FieldService()
    {
        return $this->services['form.type.field'] = new \Symfony\Component\Form\Extension\Core\Type\FieldType();
    }
    protected function getForm_Type_FileService()
    {
        return $this->services['form.type.file'] = new \Symfony\Component\Form\Extension\Core\Type\FileType();
    }
    protected function getForm_Type_FormService()
    {
        return $this->services['form.type.form'] = new \Symfony\Component\Form\Extension\Core\Type\FormType();
    }
    protected function getForm_Type_HiddenService()
    {
        return $this->services['form.type.hidden'] = new \Symfony\Component\Form\Extension\Core\Type\HiddenType();
    }
    protected function getForm_Type_IntegerService()
    {
        return $this->services['form.type.integer'] = new \Symfony\Component\Form\Extension\Core\Type\IntegerType();
    }
    protected function getForm_Type_LanguageService()
    {
        return $this->services['form.type.language'] = new \Symfony\Component\Form\Extension\Core\Type\LanguageType();
    }
    protected function getForm_Type_LocaleService()
    {
        return $this->services['form.type.locale'] = new \Symfony\Component\Form\Extension\Core\Type\LocaleType();
    }
    protected function getForm_Type_MoneyService()
    {
        return $this->services['form.type.money'] = new \Symfony\Component\Form\Extension\Core\Type\MoneyType();
    }
    protected function getForm_Type_NumberService()
    {
        return $this->services['form.type.number'] = new \Symfony\Component\Form\Extension\Core\Type\NumberType();
    }
    protected function getForm_Type_PasswordService()
    {
        return $this->services['form.type.password'] = new \Symfony\Component\Form\Extension\Core\Type\PasswordType();
    }
    protected function getForm_Type_PercentService()
    {
        return $this->services['form.type.percent'] = new \Symfony\Component\Form\Extension\Core\Type\PercentType();
    }
    protected function getForm_Type_RadioService()
    {
        return $this->services['form.type.radio'] = new \Symfony\Component\Form\Extension\Core\Type\RadioType();
    }
    protected function getForm_Type_RepeatedService()
    {
        return $this->services['form.type.repeated'] = new \Symfony\Component\Form\Extension\Core\Type\RepeatedType();
    }
    protected function getForm_Type_SearchService()
    {
        return $this->services['form.type.search'] = new \Symfony\Component\Form\Extension\Core\Type\SearchType();
    }
    protected function getForm_Type_TextService()
    {
        return $this->services['form.type.text'] = new \Symfony\Component\Form\Extension\Core\Type\TextType();
    }
    protected function getForm_Type_TextareaService()
    {
        return $this->services['form.type.textarea'] = new \Symfony\Component\Form\Extension\Core\Type\TextareaType();
    }
    protected function getForm_Type_TimeService()
    {
        return $this->services['form.type.time'] = new \Symfony\Component\Form\Extension\Core\Type\TimeType();
    }
    protected function getForm_Type_TimezoneService()
    {
        return $this->services['form.type.timezone'] = new \Symfony\Component\Form\Extension\Core\Type\TimezoneType();
    }
    protected function getForm_Type_UrlService()
    {
        return $this->services['form.type.url'] = new \Symfony\Component\Form\Extension\Core\Type\UrlType();
    }
    protected function getForm_TypeExtension_CsrfService()
    {
        return $this->services['form.type_extension.csrf'] = new \Symfony\Component\Form\Extension\Csrf\Type\FormTypeCsrfExtension($this->get('form.csrf_provider'), true, '_token');
    }
    protected function getForm_TypeExtension_Form_HttpFoundationService()
    {
        return $this->services['form.type_extension.form.http_foundation'] = new \Symfony\Component\Form\Extension\HttpFoundation\Type\FormTypeHttpFoundationExtension();
    }
    protected function getForm_TypeExtension_Form_ValidatorService()
    {
        return $this->services['form.type_extension.form.validator'] = new \Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension($this->get('validator'));
    }
    protected function getForm_TypeExtension_Repeated_ValidatorService()
    {
        return $this->services['form.type_extension.repeated.validator'] = new \Symfony\Component\Form\Extension\Validator\Type\RepeatedTypeValidatorExtension();
    }
    protected function getForm_TypeGuesser_ValidatorService()
    {
        return $this->services['form.type_guesser.validator'] = new \Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser($this->get('validator.mapping.class_metadata_factory'));
    }
    protected function getHttpKernelService()
    {
        return $this->services['http_kernel'] = new \Symfony\Bundle\FrameworkBundle\HttpKernel($this->get('event_dispatcher'), $this, $this->get('jms_di_extra.controller_resolver'));
    }
    protected function getJmsAop_InterceptorLoaderService()
    {
        return $this->services['jms_aop.interceptor_loader'] = new \JMS\AopBundle\Aop\InterceptorLoader($this, array());
    }
    protected function getJmsAop_PointcutContainerService()
    {
        return $this->services['jms_aop.pointcut_container'] = new \JMS\AopBundle\Aop\PointcutContainer(array('security.access.method_interceptor' => $this->get('security.access.pointcut')));
    }
    protected function getJmsDiExtra_Metadata_ConverterService()
    {
        return $this->services['jms_di_extra.metadata.converter'] = new \JMS\DiExtraBundle\Metadata\MetadataConverter();
    }
    protected function getJmsDiExtra_Metadata_MetadataFactoryService()
    {
        $this->services['jms_di_extra.metadata.metadata_factory'] = $instance = new \Metadata\MetadataFactory(new \Metadata\Driver\LazyLoadingDriver($this, 'jms_di_extra.metadata_driver'), 'Metadata\\ClassHierarchyMetadata', false);
        $instance->setCache(new \Metadata\Cache\FileCache('/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/jms_diextra/metadata'));
        return $instance;
    }
    protected function getJmsDiExtra_MetadataDriverService()
    {
        return $this->services['jms_di_extra.metadata_driver'] = new \JMS\DiExtraBundle\Metadata\Driver\AnnotationDriver($this->get('annotation_reader'));
    }
    protected function getKernelService()
    {
        throw new RuntimeException('You have requested a synthetic service ("kernel"). The DIC does not know how to construct this service.');
    }
    protected function getLocaleListenerService()
    {
        return $this->services['locale_listener'] = new \Symfony\Component\HttpKernel\EventListener\LocaleListener('en', $this->get('router'));
    }
    protected function getLoggerService()
    {
        $this->services['logger'] = $instance = new \Symfony\Bridge\Monolog\Logger('app');
        $instance->pushHandler($this->get('monolog.handler.firephp'));
        $instance->pushHandler($this->get('monolog.handler.main'));
        $instance->pushHandler($this->get('monolog.handler.debug'));
        return $instance;
    }
    protected function getMailerService()
    {
        return $this->services['mailer'] = new \Swift_Mailer($this->get('swiftmailer.transport'));
    }
    protected function getMonolog_Handler_DebugService()
    {
        return $this->services['monolog.handler.debug'] = new \Symfony\Bridge\Monolog\Handler\DebugHandler(100, true);
    }
    protected function getMonolog_Handler_FirephpService()
    {
        return $this->services['monolog.handler.firephp'] = new \Symfony\Bridge\Monolog\Handler\FirePHPHandler(200, true);
    }
    protected function getMonolog_Handler_MainService()
    {
        return $this->services['monolog.handler.main'] = new \Monolog\Handler\StreamHandler('/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/logs/test.log', 100, true);
    }
    protected function getMonolog_Logger_ProfilerService()
    {
        $this->services['monolog.logger.profiler'] = $instance = new \Symfony\Bridge\Monolog\Logger('profiler');
        $instance->pushHandler($this->get('monolog.handler.firephp'));
        $instance->pushHandler($this->get('monolog.handler.main'));
        $instance->pushHandler($this->get('monolog.handler.debug'));
        return $instance;
    }
    protected function getMonolog_Logger_RequestService()
    {
        $this->services['monolog.logger.request'] = $instance = new \Symfony\Bridge\Monolog\Logger('request');
        $instance->pushHandler($this->get('monolog.handler.firephp'));
        $instance->pushHandler($this->get('monolog.handler.main'));
        $instance->pushHandler($this->get('monolog.handler.debug'));
        return $instance;
    }
    protected function getMonolog_Logger_RouterService()
    {
        $this->services['monolog.logger.router'] = $instance = new \Symfony\Bridge\Monolog\Logger('router');
        $instance->pushHandler($this->get('monolog.handler.firephp'));
        $instance->pushHandler($this->get('monolog.handler.main'));
        $instance->pushHandler($this->get('monolog.handler.debug'));
        return $instance;
    }
    protected function getMonolog_Logger_SecurityService()
    {
        $this->services['monolog.logger.security'] = $instance = new \Symfony\Bridge\Monolog\Logger('security');
        $instance->pushHandler($this->get('monolog.handler.firephp'));
        $instance->pushHandler($this->get('monolog.handler.main'));
        $instance->pushHandler($this->get('monolog.handler.debug'));
        return $instance;
    }
    protected function getProfilerService()
    {
        $a = $this->get('monolog.logger.profiler');
        $b = $this->get('kernel');
        $c = new \Symfony\Component\HttpKernel\DataCollector\ConfigDataCollector();
        $c->setKernel($b);
        $d = new \Symfony\Component\HttpKernel\DataCollector\EventDataCollector();
        $d->setEventDispatcher($this->get('event_dispatcher'));
        $this->services['profiler'] = $instance = new \Symfony\Component\HttpKernel\Profiler\Profiler(new \Symfony\Component\HttpKernel\Profiler\FileProfilerStorage('file:/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/profiler', '', '', 86400), $a);
        $instance->add($c);
        $instance->add($this->get('data_collector.request'));
        $instance->add(new \Symfony\Component\HttpKernel\DataCollector\ExceptionDataCollector());
        $instance->add($d);
        $instance->add(new \Symfony\Component\HttpKernel\DataCollector\LoggerDataCollector($a));
        $instance->add(new \Symfony\Component\HttpKernel\DataCollector\TimeDataCollector($b));
        $instance->add(new \Symfony\Component\HttpKernel\DataCollector\MemoryDataCollector());
        $instance->add($this->get('data_collector.router'));
        $instance->add(new \Symfony\Bundle\SecurityBundle\DataCollector\SecurityDataCollector($this->get('security.context')));
        return $instance;
    }
    protected function getProfilerListenerService()
    {
        return $this->services['profiler_listener'] = new \Symfony\Component\HttpKernel\EventListener\ProfilerListener($this->get('profiler'), NULL, false, false);
    }
    protected function getRequestService()
    {
        if (!isset($this->scopedServices['request'])) {
            throw new InactiveScopeException('request', 'request');
        }
        throw new RuntimeException('You have requested a synthetic service ("request"). The DIC does not know how to construct this service.');
    }
    protected function getResponseListenerService()
    {
        return $this->services['response_listener'] = new \Symfony\Component\HttpKernel\EventListener\ResponseListener('UTF-8');
    }
    protected function getRouterService()
    {
        return $this->services['router'] = new \Symfony\Bundle\FrameworkBundle\Routing\Router($this, '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/assetic/routing.yml', array('cache_dir' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test', 'debug' => false, 'generator_class' => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator', 'generator_base_class' => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator', 'generator_dumper_class' => 'Symfony\\Component\\Routing\\Generator\\Dumper\\PhpGeneratorDumper', 'generator_cache_class' => 'apptestUrlGenerator', 'matcher_class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\RedirectableUrlMatcher', 'matcher_base_class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\RedirectableUrlMatcher', 'matcher_dumper_class' => 'Symfony\\Component\\Routing\\Matcher\\Dumper\\PhpMatcherDumper', 'matcher_cache_class' => 'apptestUrlMatcher', 'strict_requirements' => true), $this->get('router.request_context'), $this->get('monolog.logger.router'));
    }
    protected function getRouterListenerService()
    {
        return $this->services['router_listener'] = new \Symfony\Component\HttpKernel\EventListener\RouterListener($this->get('router'), $this->get('router.request_context'), $this->get('monolog.logger.request'));
    }
    protected function getRouting_LoaderService()
    {
        $a = $this->get('file_locator');
        $b = $this->get('annotation_reader');
        $c = new \Sensio\Bundle\FrameworkExtraBundle\Routing\AnnotatedRouteControllerLoader($b);
        $d = new \Symfony\Component\Config\Loader\LoaderResolver();
        $d->addLoader(new \Symfony\Component\Routing\Loader\XmlFileLoader($a));
        $d->addLoader(new \Symfony\Component\Routing\Loader\YamlFileLoader($a));
        $d->addLoader(new \Symfony\Component\Routing\Loader\PhpFileLoader($a));
        $d->addLoader(new \Symfony\Bundle\AsseticBundle\Routing\AsseticLoader($this->get('assetic.asset_manager')));
        $d->addLoader(new \Symfony\Component\Routing\Loader\AnnotationDirectoryLoader($a, $c));
        $d->addLoader(new \Symfony\Component\Routing\Loader\AnnotationFileLoader($a, $c));
        $d->addLoader($c);
        $d->addLoader($this->get('alphalemon_bootstrap.routing_loader'));
        return $this->services['routing.loader'] = new \Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader($this->get('controller_name_converter'), $this->get('monolog.logger.router'), $d);
    }
    protected function getSecurity_Access_MethodInterceptorService()
    {
        return $this->services['security.access.method_interceptor'] = new \JMS\SecurityExtraBundle\Security\Authorization\Interception\MethodSecurityInterceptor($this->get('security.context'), $this->get('security.authentication.manager'), $this->get('security.access.decision_manager'), new \JMS\SecurityExtraBundle\Security\Authorization\AfterInvocation\AfterInvocationManager(array()), new \JMS\SecurityExtraBundle\Security\Authorization\RunAsManager('RunAsToken', 'ROLE_'), $this->get('security.extra.metadata_factory'), $this->get('monolog.logger.security'));
    }
    protected function getSecurity_Access_PointcutService()
    {
        $this->services['security.access.pointcut'] = $instance = new \JMS\SecurityExtraBundle\Security\Authorization\Interception\SecurityPointcut($this->get('security.extra.metadata_factory'), false, array());
        $instance->setSecuredClasses(array());
        return $instance;
    }
    protected function getSecurity_Authentication_TrustResolverService()
    {
        return $this->services['security.authentication.trust_resolver'] = new \Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolver('Symfony\\Component\\Security\\Core\\Authentication\\Token\\AnonymousToken', 'Symfony\\Component\\Security\\Core\\Authentication\\Token\\RememberMeToken');
    }
    protected function getSecurity_ContextService()
    {
        return $this->services['security.context'] = new \Symfony\Component\Security\Core\SecurityContext($this->get('security.authentication.manager'), $this->get('security.access.decision_manager'), false);
    }
    protected function getSecurity_EncoderFactoryService()
    {
        return $this->services['security.encoder_factory'] = new \Symfony\Component\Security\Core\Encoder\EncoderFactory(array('Symfony\\Component\\Security\\Core\\User\\User' => array('class' => 'Symfony\\Component\\Security\\Core\\Encoder\\PlaintextPasswordEncoder', 'arguments' => array(0 => false))));
    }
    protected function getSecurity_Expressions_CompilerService()
    {
        $a = new \JMS\SecurityExtraBundle\Security\Authorization\Expression\Compiler\ContainerAwareVariableCompiler();
        $a->setMaps(array('trust_resolver' => 'security.authentication.trust_resolver', 'role_hierarchy' => 'security.role_hierarchy', 'permission_evaluator' => 'security.acl.permission_evaluator'), array());
        $this->services['security.expressions.compiler'] = $instance = new \JMS\SecurityExtraBundle\Security\Authorization\Expression\ExpressionCompiler();
        $instance->addFunctionCompiler(new \JMS\SecurityExtraBundle\Security\Acl\Expression\HasPermissionFunctionCompiler());
        $instance->addTypeCompiler(new \JMS\SecurityExtraBundle\Security\Authorization\Expression\Compiler\ParameterExpressionCompiler());
        $instance->addTypeCompiler($a);
        return $instance;
    }
    protected function getSecurity_Extra_MetadataDriverService()
    {
        return $this->services['security.extra.metadata_driver'] = new \Metadata\Driver\DriverChain(array(0 => new \JMS\SecurityExtraBundle\Metadata\Driver\AnnotationDriver($this->get('annotation_reader'))));
    }
    protected function getSecurity_FirewallService()
    {
        return $this->services['security.firewall'] = new \Symfony\Component\Security\Http\Firewall(new \Symfony\Bundle\SecurityBundle\Security\FirewallMap($this, array('security.firewall.map.context.dev' => new \Symfony\Component\HttpFoundation\RequestMatcher('^/(_(profiler|wdt)|css|images|js)/'), 'security.firewall.map.context.login' => new \Symfony\Component\HttpFoundation\RequestMatcher('^/demo/secured/login$'), 'security.firewall.map.context.secured_area' => new \Symfony\Component\HttpFoundation\RequestMatcher('^/demo/secured/'))), $this->get('event_dispatcher'));
    }
    protected function getSecurity_Firewall_Map_Context_DevService()
    {
        return $this->services['security.firewall.map.context.dev'] = new \Symfony\Bundle\SecurityBundle\Security\FirewallContext(array(), NULL);
    }
    protected function getSecurity_Firewall_Map_Context_LoginService()
    {
        return $this->services['security.firewall.map.context.login'] = new \Symfony\Bundle\SecurityBundle\Security\FirewallContext(array(), NULL);
    }
    protected function getSecurity_Firewall_Map_Context_SecuredAreaService()
    {
        $a = $this->get('monolog.logger.security');
        $b = $this->get('security.context');
        $c = $this->get('event_dispatcher');
        $d = $this->get('router');
        $e = $this->get('http_kernel');
        $f = $this->get('security.authentication.manager');
        $g = new \Symfony\Component\Security\Http\AccessMap();
        $h = new \Symfony\Component\Security\Http\HttpUtils($d, $d);
        $i = new \Symfony\Component\Security\Http\Firewall\LogoutListener($b, $h, new \Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler($h, '/demo/'), array('csrf_parameter' => '_csrf_token', 'intention' => 'logout', 'logout_path' => '/demo/secured/logout'));
        $i->addHandler(new \Symfony\Component\Security\Http\Logout\SessionLogoutHandler());
        $j = new \Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler($h, array('login_path' => '/demo/secured/login', 'always_use_default_target_path' => false, 'default_target_path' => '/', 'target_path_parameter' => '_target_path', 'use_referer' => false));
        $j->setProviderKey('secured_area');
        return $this->services['security.firewall.map.context.secured_area'] = new \Symfony\Bundle\SecurityBundle\Security\FirewallContext(array(0 => new \Symfony\Component\Security\Http\Firewall\ChannelListener($g, new \Symfony\Component\Security\Http\EntryPoint\RetryAuthenticationEntryPoint(80, 443), $a), 1 => new \Symfony\Component\Security\Http\Firewall\ContextListener($b, array(0 => $this->get('security.user.provider.concrete.in_memory')), 'secured_area', $a, $c), 2 => $i, 3 => new \Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener($b, $f, new \Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy('migrate'), $h, 'secured_area', $j, new \Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler($e, $h, array('login_path' => '/demo/secured/login', 'failure_path' => NULL, 'failure_forward' => false), $a), array('check_path' => '/demo/secured/login_check', 'use_forward' => false, 'username_parameter' => '_username', 'password_parameter' => '_password', 'csrf_parameter' => '_csrf_token', 'intention' => 'authenticate', 'post_only' => true), $a, $c), 4 => new \Symfony\Component\Security\Http\Firewall\AccessListener($b, $this->get('security.access.decision_manager'), $g, $f, $a)), new \Symfony\Component\Security\Http\Firewall\ExceptionListener($b, $this->get('security.authentication.trust_resolver'), $h, 'secured_area', new \Symfony\Component\Security\Http\EntryPoint\FormAuthenticationEntryPoint($e, $h, '/demo/secured/login', false), NULL, NULL, $a));
    }
    protected function getSecurity_Rememberme_ResponseListenerService()
    {
        return $this->services['security.rememberme.response_listener'] = new \Symfony\Component\Security\Http\RememberMe\ResponseListener();
    }
    protected function getSecurity_RoleHierarchyService()
    {
        return $this->services['security.role_hierarchy'] = new \Symfony\Component\Security\Core\Role\RoleHierarchy(array('ROLE_ADMIN' => array(0 => 'ROLE_USER'), 'ROLE_SUPER_ADMIN' => array(0 => 'ROLE_USER', 1 => 'ROLE_ADMIN', 2 => 'ROLE_ALLOWED_TO_SWITCH')));
    }
    protected function getSecurity_Validator_UserPasswordService()
    {
        return $this->services['security.validator.user_password'] = new \Symfony\Component\Security\Core\Validator\Constraint\UserPasswordValidator($this->get('security.context'), $this->get('security.encoder_factory'));
    }
    protected function getSensio_Distribution_WebconfiguratorService()
    {
        return $this->services['sensio.distribution.webconfigurator'] = new \Sensio\Bundle\DistributionBundle\Configurator\Configurator('/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app');
    }
    protected function getSensioFrameworkExtra_Cache_ListenerService()
    {
        return $this->services['sensio_framework_extra.cache.listener'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\CacheListener();
    }
    protected function getSensioFrameworkExtra_Controller_ListenerService()
    {
        return $this->services['sensio_framework_extra.controller.listener'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\ControllerListener($this->get('annotation_reader'));
    }
    protected function getSensioFrameworkExtra_Converter_DatetimeService()
    {
        return $this->services['sensio_framework_extra.converter.datetime'] = new \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DateTimeParamConverter();
    }
    protected function getSensioFrameworkExtra_Converter_Doctrine_OrmService()
    {
        return $this->services['sensio_framework_extra.converter.doctrine.orm'] = new \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter(NULL);
    }
    protected function getSensioFrameworkExtra_Converter_ListenerService()
    {
        return $this->services['sensio_framework_extra.converter.listener'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\ParamConverterListener($this->get('sensio_framework_extra.converter.manager'));
    }
    protected function getSensioFrameworkExtra_Converter_ManagerService()
    {
        $this->services['sensio_framework_extra.converter.manager'] = $instance = new \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterManager();
        $instance->add($this->get('sensio_framework_extra.converter.doctrine.orm'), 0, 'doctrine.orm');
        $instance->add($this->get('sensio_framework_extra.converter.datetime'), 0, 'datetime');
        return $instance;
    }
    protected function getSensioFrameworkExtra_View_GuesserService()
    {
        return $this->services['sensio_framework_extra.view.guesser'] = new \Sensio\Bundle\FrameworkExtraBundle\Templating\TemplateGuesser($this->get('kernel'));
    }
    protected function getSensioFrameworkExtra_View_ListenerService()
    {
        return $this->services['sensio_framework_extra.view.listener'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\TemplateListener($this);
    }
    protected function getServiceContainerService()
    {
        throw new RuntimeException('You have requested a synthetic service ("service_container"). The DIC does not know how to construct this service.');
    }
    protected function getSessionService()
    {
        return $this->services['session'] = new \Symfony\Component\HttpFoundation\Session\Session($this->get('session.storage.filesystem'), new \Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag(), new \Symfony\Component\HttpFoundation\Session\Flash\FlashBag());
    }
    protected function getSession_HandlerService()
    {
        return $this->services['session.handler'] = new \Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler('/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/sessions');
    }
    protected function getSession_Storage_FilesystemService()
    {
        return $this->services['session.storage.filesystem'] = new \Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage('/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/sessions');
    }
    protected function getSession_Storage_NativeService()
    {
        return $this->services['session.storage.native'] = new \Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage(array(), $this->get('session.handler'));
    }
    protected function getSessionListenerService()
    {
        return $this->services['session_listener'] = new \Symfony\Bundle\FrameworkBundle\EventListener\SessionListener($this);
    }
    protected function getStreamedResponseListenerService()
    {
        return $this->services['streamed_response_listener'] = new \Symfony\Component\HttpKernel\EventListener\StreamedResponseListener();
    }
    protected function getSwiftmailer_EmailSender_ListenerService()
    {
        return $this->services['swiftmailer.email_sender.listener'] = new \Symfony\Bundle\SwiftmailerBundle\EventListener\EmailSenderListener($this);
    }
    protected function getSwiftmailer_Plugin_MessageloggerService()
    {
        return $this->services['swiftmailer.plugin.messagelogger'] = new \Swift_Plugins_MessageLogger();
    }
    protected function getSwiftmailer_SpoolService()
    {
        return $this->services['swiftmailer.spool'] = new \Swift_MemorySpool();
    }
    protected function getSwiftmailer_TransportService()
    {
        return $this->services['swiftmailer.transport'] = new \Swift_Transport_SpoolTransport($this->get('swiftmailer.transport.eventdispatcher'), $this->get('swiftmailer.spool'));
    }
    protected function getSwiftmailer_Transport_RealService()
    {
        return $this->services['swiftmailer.transport.real'] = new \Swift_Transport_NullTransport($this->get('swiftmailer.transport.eventdispatcher'));
    }
    protected function getTemplatingService()
    {
        $this->services['templating'] = $instance = new \Symfony\Bundle\TwigBundle\TwigEngine($this->get('twig'), $this->get('templating.name_parser'), $this->get('templating.locator'), $this->get('templating.globals'));
        $instance->setDefaultEscapingStrategy(array(0 => $instance, 1 => 'guessDefaultEscapingStrategy'));
        return $instance;
    }
    protected function getTemplating_Asset_PackageFactoryService()
    {
        return $this->services['templating.asset.package_factory'] = new \Symfony\Bundle\FrameworkBundle\Templating\Asset\PackageFactory($this);
    }
    protected function getTemplating_FilenameParserService()
    {
        return $this->services['templating.filename_parser'] = new \Symfony\Bundle\FrameworkBundle\Templating\TemplateFilenameParser();
    }
    protected function getTemplating_GlobalsService()
    {
        return $this->services['templating.globals'] = new \Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables($this);
    }
    protected function getTemplating_Helper_ActionsService()
    {
        return $this->services['templating.helper.actions'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\ActionsHelper($this->get('http_kernel'));
    }
    protected function getTemplating_Helper_AssetsService()
    {
        if (!isset($this->scopedServices['request'])) {
            throw new InactiveScopeException('templating.helper.assets', 'request');
        }
        return $this->services['templating.helper.assets'] = $this->scopedServices['request']['templating.helper.assets'] = new \Symfony\Component\Templating\Helper\CoreAssetsHelper(new \Symfony\Bundle\FrameworkBundle\Templating\Asset\PathPackage($this->get('request'), NULL, '%s?%s'), array());
    }
    protected function getTemplating_Helper_CodeService()
    {
        return $this->services['templating.helper.code'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\CodeHelper(NULL, '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app', 'UTF-8');
    }
    protected function getTemplating_Helper_FormService()
    {
        $a = new \Symfony\Bundle\FrameworkBundle\Templating\PhpEngine($this->get('templating.name_parser'), $this, $this->get('templating.loader'), $this->get('templating.globals'));
        $a->setCharset('UTF-8');
        $a->setHelpers(array('slots' => 'templating.helper.slots', 'assets' => 'templating.helper.assets', 'request' => 'templating.helper.request', 'session' => 'templating.helper.session', 'router' => 'templating.helper.router', 'actions' => 'templating.helper.actions', 'code' => 'templating.helper.code', 'translator' => 'templating.helper.translator', 'form' => 'templating.helper.form', 'logout_url' => 'templating.helper.logout_url', 'security' => 'templating.helper.security', 'assetic' => 'assetic.helper.dynamic'));
        return $this->services['templating.helper.form'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper(new \Symfony\Component\Form\FormRenderer(new \Symfony\Component\Form\Extension\Templating\TemplatingRendererEngine($a, array(0 => 'FrameworkBundle:Form')), $this->get('form.csrf_provider')));
    }
    protected function getTemplating_Helper_LogoutUrlService()
    {
        $this->services['templating.helper.logout_url'] = $instance = new \Symfony\Bundle\SecurityBundle\Templating\Helper\LogoutUrlHelper($this, $this->get('router'));
        $instance->registerListener('secured_area', '/demo/secured/logout', 'logout', '_csrf_token', NULL);
        return $instance;
    }
    protected function getTemplating_Helper_RequestService()
    {
        return $this->services['templating.helper.request'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\RequestHelper($this->get('request'));
    }
    protected function getTemplating_Helper_RouterService()
    {
        return $this->services['templating.helper.router'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper($this->get('router'));
    }
    protected function getTemplating_Helper_SecurityService()
    {
        return $this->services['templating.helper.security'] = new \Symfony\Bundle\SecurityBundle\Templating\Helper\SecurityHelper($this->get('security.context'));
    }
    protected function getTemplating_Helper_SessionService()
    {
        return $this->services['templating.helper.session'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\SessionHelper($this->get('request'));
    }
    protected function getTemplating_Helper_SlotsService()
    {
        return $this->services['templating.helper.slots'] = new \Symfony\Component\Templating\Helper\SlotsHelper();
    }
    protected function getTemplating_Helper_TranslatorService()
    {
        return $this->services['templating.helper.translator'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\TranslatorHelper($this->get('translator'));
    }
    protected function getTemplating_LoaderService()
    {
        return $this->services['templating.loader'] = new \Symfony\Bundle\FrameworkBundle\Templating\Loader\FilesystemLoader($this->get('templating.locator'));
    }
    protected function getTemplating_NameParserService()
    {
        return $this->services['templating.name_parser'] = new \Symfony\Bundle\FrameworkBundle\Templating\TemplateNameParser($this->get('kernel'));
    }
    protected function getTest_ClientService()
    {
        return new \Symfony\Bundle\FrameworkBundle\Client($this->get('kernel'), array(), new \Symfony\Component\BrowserKit\History(), new \Symfony\Component\BrowserKit\CookieJar());
    }
    protected function getTest_Client_CookiejarService()
    {
        return new \Symfony\Component\BrowserKit\CookieJar();
    }
    protected function getTest_Client_HistoryService()
    {
        return new \Symfony\Component\BrowserKit\History();
    }
    protected function getTest_Session_ListenerService()
    {
        return $this->services['test.session.listener'] = new \Symfony\Bundle\FrameworkBundle\EventListener\TestSessionListener($this);
    }
    protected function getTranslation_Dumper_CsvService()
    {
        return $this->services['translation.dumper.csv'] = new \Symfony\Component\Translation\Dumper\CsvFileDumper();
    }
    protected function getTranslation_Dumper_IniService()
    {
        return $this->services['translation.dumper.ini'] = new \Symfony\Component\Translation\Dumper\IniFileDumper();
    }
    protected function getTranslation_Dumper_MoService()
    {
        return $this->services['translation.dumper.mo'] = new \Symfony\Component\Translation\Dumper\MoFileDumper();
    }
    protected function getTranslation_Dumper_PhpService()
    {
        return $this->services['translation.dumper.php'] = new \Symfony\Component\Translation\Dumper\PhpFileDumper();
    }
    protected function getTranslation_Dumper_PoService()
    {
        return $this->services['translation.dumper.po'] = new \Symfony\Component\Translation\Dumper\PoFileDumper();
    }
    protected function getTranslation_Dumper_QtService()
    {
        return $this->services['translation.dumper.qt'] = new \Symfony\Component\Translation\Dumper\QtFileDumper();
    }
    protected function getTranslation_Dumper_ResService()
    {
        return $this->services['translation.dumper.res'] = new \Symfony\Component\Translation\Dumper\IcuResFileDumper();
    }
    protected function getTranslation_Dumper_XliffService()
    {
        return $this->services['translation.dumper.xliff'] = new \Symfony\Component\Translation\Dumper\XliffFileDumper();
    }
    protected function getTranslation_Dumper_YmlService()
    {
        return $this->services['translation.dumper.yml'] = new \Symfony\Component\Translation\Dumper\YamlFileDumper();
    }
    protected function getTranslation_ExtractorService()
    {
        $this->services['translation.extractor'] = $instance = new \Symfony\Component\Translation\Extractor\ChainExtractor();
        $instance->addExtractor('php', $this->get('translation.extractor.php'));
        $instance->addExtractor('twig', $this->get('twig.translation.extractor'));
        return $instance;
    }
    protected function getTranslation_Extractor_PhpService()
    {
        return $this->services['translation.extractor.php'] = new \Symfony\Bundle\FrameworkBundle\Translation\PhpExtractor();
    }
    protected function getTranslation_LoaderService()
    {
        $a = $this->get('translation.loader.xliff');
        $this->services['translation.loader'] = $instance = new \Symfony\Bundle\FrameworkBundle\Translation\TranslationLoader();
        $instance->addLoader('php', $this->get('translation.loader.php'));
        $instance->addLoader('yml', $this->get('translation.loader.yml'));
        $instance->addLoader('xlf', $a);
        $instance->addLoader('xliff', $a);
        $instance->addLoader('po', $this->get('translation.loader.po'));
        $instance->addLoader('mo', $this->get('translation.loader.mo'));
        $instance->addLoader('ts', $this->get('translation.loader.qt'));
        $instance->addLoader('csv', $this->get('translation.loader.csv'));
        $instance->addLoader('res', $this->get('translation.loader.res'));
        $instance->addLoader('dat', $this->get('translation.loader.dat'));
        $instance->addLoader('ini', $this->get('translation.loader.ini'));
        return $instance;
    }
    protected function getTranslation_Loader_CsvService()
    {
        return $this->services['translation.loader.csv'] = new \Symfony\Component\Translation\Loader\CsvFileLoader();
    }
    protected function getTranslation_Loader_DatService()
    {
        return $this->services['translation.loader.dat'] = new \Symfony\Component\Translation\Loader\IcuResFileLoader();
    }
    protected function getTranslation_Loader_IniService()
    {
        return $this->services['translation.loader.ini'] = new \Symfony\Component\Translation\Loader\IniFileLoader();
    }
    protected function getTranslation_Loader_MoService()
    {
        return $this->services['translation.loader.mo'] = new \Symfony\Component\Translation\Loader\MoFileLoader();
    }
    protected function getTranslation_Loader_PhpService()
    {
        return $this->services['translation.loader.php'] = new \Symfony\Component\Translation\Loader\PhpFileLoader();
    }
    protected function getTranslation_Loader_PoService()
    {
        return $this->services['translation.loader.po'] = new \Symfony\Component\Translation\Loader\PoFileLoader();
    }
    protected function getTranslation_Loader_QtService()
    {
        return $this->services['translation.loader.qt'] = new \Symfony\Component\Translation\Loader\QtTranslationsLoader();
    }
    protected function getTranslation_Loader_ResService()
    {
        return $this->services['translation.loader.res'] = new \Symfony\Component\Translation\Loader\IcuResFileLoader();
    }
    protected function getTranslation_Loader_XliffService()
    {
        return $this->services['translation.loader.xliff'] = new \Symfony\Component\Translation\Loader\XliffFileLoader();
    }
    protected function getTranslation_Loader_YmlService()
    {
        return $this->services['translation.loader.yml'] = new \Symfony\Component\Translation\Loader\YamlFileLoader();
    }
    protected function getTranslation_WriterService()
    {
        $this->services['translation.writer'] = $instance = new \Symfony\Component\Translation\Writer\TranslationWriter();
        $instance->addDumper('php', $this->get('translation.dumper.php'));
        $instance->addDumper('xlf', $this->get('translation.dumper.xliff'));
        $instance->addDumper('po', $this->get('translation.dumper.po'));
        $instance->addDumper('mo', $this->get('translation.dumper.mo'));
        $instance->addDumper('yml', $this->get('translation.dumper.yml'));
        $instance->addDumper('ts', $this->get('translation.dumper.qt'));
        $instance->addDumper('csv', $this->get('translation.dumper.csv'));
        $instance->addDumper('ini', $this->get('translation.dumper.ini'));
        $instance->addDumper('res', $this->get('translation.dumper.res'));
        return $instance;
    }
    protected function getTranslatorService()
    {
        return $this->services['translator'] = new \Symfony\Component\Translation\IdentityTranslator($this->get('translator.selector'));
    }
    protected function getTranslator_DefaultService()
    {
        return $this->services['translator.default'] = new \Symfony\Bundle\FrameworkBundle\Translation\Translator($this, $this->get('translator.selector'), array('translation.loader.php' => array(0 => 'php'), 'translation.loader.yml' => array(0 => 'yml'), 'translation.loader.xliff' => array(0 => 'xlf', 1 => 'xliff'), 'translation.loader.po' => array(0 => 'po'), 'translation.loader.mo' => array(0 => 'mo'), 'translation.loader.qt' => array(0 => 'ts'), 'translation.loader.csv' => array(0 => 'csv'), 'translation.loader.res' => array(0 => 'res'), 'translation.loader.dat' => array(0 => 'dat'), 'translation.loader.ini' => array(0 => 'ini')), array('cache_dir' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/translations', 'debug' => false));
    }
    protected function getTwigService()
    {
        $a = $this->get('security.context');
        $this->services['twig'] = $instance = new \Twig_Environment($this->get('twig.loader'), array('debug' => false, 'strict_variables' => false, 'exception_controller' => 'Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction', 'cache' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/twig', 'charset' => 'UTF-8', 'paths' => array()));
        $instance->addExtension(new \Symfony\Bundle\SecurityBundle\Twig\Extension\LogoutUrlExtension($this->get('templating.helper.logout_url')));
        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\SecurityExtension($a));
        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\TranslationExtension($this->get('translator')));
        $instance->addExtension(new \Symfony\Bundle\TwigBundle\Extension\AssetsExtension($this));
        $instance->addExtension(new \Symfony\Bundle\TwigBundle\Extension\ActionsExtension($this));
        $instance->addExtension(new \Symfony\Bundle\TwigBundle\Extension\CodeExtension($this));
        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\RoutingExtension($this->get('router')));
        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\YamlExtension());
        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\FormExtension(new \Symfony\Bridge\Twig\Form\TwigRenderer(new \Symfony\Bridge\Twig\Form\TwigRendererEngine(array(0 => 'form_div_layout.html.twig')), $this->get('form.csrf_provider'))));
        $instance->addExtension(new \Symfony\Bundle\AsseticBundle\Twig\AsseticExtension($this->get('assetic.asset_factory'), $this->get('templating.name_parser'), true, array(), array(), $this->get('assetic.value_supplier.default')));
        $instance->addExtension(new \JMS\SecurityExtraBundle\Twig\SecurityExtension($a));
        $instance->addExtension(new \AlphaLemon\ThemeEngineBundle\Twig\SlotRendererExtension($this));
        $instance->addExtension(new \AlphaLemon\ThemeEngineBundle\Twig\FileExtension());
        return $instance;
    }
    protected function getTwig_ExceptionListenerService()
    {
        return $this->services['twig.exception_listener'] = new \Symfony\Component\HttpKernel\EventListener\ExceptionListener('Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction', $this->get('monolog.logger.request'));
    }
    protected function getTwig_LoaderService()
    {
        $this->services['twig.loader'] = $instance = new \Symfony\Bundle\TwigBundle\Loader\FilesystemLoader($this->get('templating.locator'), $this->get('templating.name_parser'));
        $instance->addPath('/home/alphalemon/tests/ThemeEngineBundle/vendor/symfony/symfony/src/Symfony/Bridge/Twig/Resources/views/Form');
        return $instance;
    }
    protected function getTwig_Translation_ExtractorService()
    {
        return $this->services['twig.translation.extractor'] = new \Symfony\Bridge\Twig\Translation\TwigExtractor($this->get('twig'));
    }
    protected function getValidatorService()
    {
        return $this->services['validator'] = new \Symfony\Component\Validator\Validator($this->get('validator.mapping.class_metadata_factory'), new \Symfony\Bundle\FrameworkBundle\Validator\ConstraintValidatorFactory($this, array('security.validator.user_password' => 'security.validator.user_password')), array());
    }
    protected function getWebProfiler_DebugToolbarService()
    {
        return $this->services['web_profiler.debug_toolbar'] = new \Symfony\Bundle\WebProfilerBundle\EventListener\WebDebugToolbarListener($this->get('templating'), false, 1, 'bottom');
    }
    protected function getSession_StorageService()
    {
        return $this->get('session.storage.filesystem');
    }
    protected function getAssetic_AssetFactoryService()
    {
        $this->services['assetic.asset_factory'] = $instance = new \Symfony\Bundle\AsseticBundle\Factory\AssetFactory($this->get('kernel'), $this, $this->getParameterBag(), '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/../web', false);
        $instance->addWorker(new \Symfony\Bundle\AsseticBundle\Factory\Worker\UseControllerWorker());
        return $instance;
    }
    protected function getAssetic_CacheService()
    {
        return $this->services['assetic.cache'] = new \Assetic\Cache\FilesystemCache('/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/assetic/assets');
    }
    protected function getAssetic_ValueSupplier_DefaultService()
    {
        return $this->services['assetic.value_supplier.default'] = new \Symfony\Bundle\AsseticBundle\DefaultValueSupplier($this);
    }
    protected function getControllerNameConverterService()
    {
        return $this->services['controller_name_converter'] = new \Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser($this->get('kernel'));
    }
    protected function getJmsDiExtra_ControllerResolverService()
    {
        return $this->services['jms_di_extra.controller_resolver'] = new \JMS\DiExtraBundle\HttpKernel\ControllerResolver($this, $this->get('controller_name_converter'), $this->get('monolog.logger.request'));
    }
    protected function getRouter_RequestContextService()
    {
        return $this->services['router.request_context'] = new \Symfony\Component\Routing\RequestContext('', 'GET', 'localhost', 'http', 80, 443);
    }
    protected function getSecurity_Access_DecisionManagerService()
    {
        $a = new \JMS\SecurityExtraBundle\Security\Authorization\Expression\LazyLoadingExpressionVoter(new \JMS\SecurityExtraBundle\Security\Authorization\Expression\ContainerAwareExpressionHandler($this));
        $a->setLazyCompiler($this, 'security.expressions.compiler');
        $a->setCacheDir('/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/jms_security/expressions');
        return $this->services['security.access.decision_manager'] = new \Symfony\Component\Security\Core\Authorization\AccessDecisionManager(array(0 => $a, 1 => new \Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter($this->get('security.role_hierarchy')), 2 => new \Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter($this->get('security.authentication.trust_resolver'))), 'affirmative', false, true);
    }
    protected function getSecurity_Authentication_ManagerService()
    {
        $this->services['security.authentication.manager'] = $instance = new \Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager(array(0 => new \Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider($this->get('security.user.provider.concrete.in_memory'), new \Symfony\Component\Security\Core\User\UserChecker(), 'secured_area', $this->get('security.encoder_factory'), true)), true);
        $instance->setEventDispatcher($this->get('event_dispatcher'));
        return $instance;
    }
    protected function getSecurity_Extra_MetadataFactoryService()
    {
        $this->services['security.extra.metadata_factory'] = $instance = new \Metadata\MetadataFactory(new \Metadata\Driver\LazyLoadingDriver($this, 'security.extra.metadata_driver'), new \Metadata\Cache\FileCache('/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/jms_security', false));
        $instance->setIncludeInterfaces(true);
        return $instance;
    }
    protected function getSecurity_User_Provider_Concrete_InMemoryService()
    {
        $this->services['security.user.provider.concrete.in_memory'] = $instance = new \Symfony\Component\Security\Core\User\InMemoryUserProvider();
        $instance->createUser(new \Symfony\Component\Security\Core\User\User('user', 'userpass', array(0 => 'ROLE_USER')));
        $instance->createUser(new \Symfony\Component\Security\Core\User\User('admin', 'adminpass', array(0 => 'ROLE_ADMIN')));
        return $instance;
    }
    protected function getSwiftmailer_Transport_EventdispatcherService()
    {
        return $this->services['swiftmailer.transport.eventdispatcher'] = new \Swift_Events_SimpleEventDispatcher();
    }
    protected function getTemplating_LocatorService()
    {
        return $this->services['templating.locator'] = new \Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator($this->get('file_locator'), '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test');
    }
    protected function getTranslator_SelectorService()
    {
        return $this->services['translator.selector'] = new \Symfony\Component\Translation\MessageSelector();
    }
    protected function getValidator_Mapping_ClassMetadataFactoryService()
    {
        return $this->services['validator.mapping.class_metadata_factory'] = new \Symfony\Component\Validator\Mapping\ClassMetadataFactory(new \Symfony\Component\Validator\Mapping\Loader\LoaderChain(array(0 => new \Symfony\Component\Validator\Mapping\Loader\AnnotationLoader($this->get('annotation_reader')), 1 => new \Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader(), 2 => new \Symfony\Component\Validator\Mapping\Loader\XmlFilesLoader(array(0 => '/home/alphalemon/tests/ThemeEngineBundle/vendor/symfony/symfony/src/Symfony/Component/Form/Resources/config/validation.xml')), 3 => new \Symfony\Component\Validator\Mapping\Loader\YamlFilesLoader(array(0 => '/home/alphalemon/tests/ThemeEngineBundle/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/Resources/config/validation.yml')))), NULL);
    }
    public function getParameter($name)
    {
        $name = strtolower($name);
        if (!array_key_exists($name, $this->parameters)) {
            throw new InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
        }
        return $this->parameters[$name];
    }
    public function hasParameter($name)
    {
        return array_key_exists(strtolower($name), $this->parameters);
    }
    public function setParameter($name, $value)
    {
        throw new LogicException('Impossible to call set() on a frozen ParameterBag.');
    }
    public function getParameterBag()
    {
        if (null === $this->parameterBag) {
            $this->parameterBag = new FrozenParameterBag($this->parameters);
        }
        return $this->parameterBag;
    }
    protected function getDefaultParameters()
    {
        return array(
            'kernel.root_dir' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app',
            'kernel.environment' => 'test',
            'kernel.debug' => false,
            'kernel.name' => 'app',
            'kernel.cache_dir' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test',
            'kernel.logs_dir' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/logs',
            'kernel.bundles' => array(
                'FrameworkBundle' => 'Symfony\\Bundle\\FrameworkBundle\\FrameworkBundle',
                'SecurityBundle' => 'Symfony\\Bundle\\SecurityBundle\\SecurityBundle',
                'TwigBundle' => 'Symfony\\Bundle\\TwigBundle\\TwigBundle',
                'MonologBundle' => 'Symfony\\Bundle\\MonologBundle\\MonologBundle',
                'SwiftmailerBundle' => 'Symfony\\Bundle\\SwiftmailerBundle\\SwiftmailerBundle',
                'AsseticBundle' => 'Symfony\\Bundle\\AsseticBundle\\AsseticBundle',
                'SensioFrameworkExtraBundle' => 'Sensio\\Bundle\\FrameworkExtraBundle\\SensioFrameworkExtraBundle',
                'JMSAopBundle' => 'JMS\\AopBundle\\JMSAopBundle',
                'JMSDiExtraBundle' => 'JMS\\DiExtraBundle\\JMSDiExtraBundle',
                'JMSSecurityExtraBundle' => 'JMS\\SecurityExtraBundle\\JMSSecurityExtraBundle',
                'WebProfilerBundle' => 'Symfony\\Bundle\\WebProfilerBundle\\WebProfilerBundle',
                'SensioDistributionBundle' => 'Sensio\\Bundle\\DistributionBundle\\SensioDistributionBundle',
                'SensioGeneratorBundle' => 'Sensio\\Bundle\\GeneratorBundle\\SensioGeneratorBundle',
                'AlphaLemonBootstrapBundle' => 'AlphaLemon\\BootstrapBundle\\AlphaLemonBootstrapBundle',
                'BusinessWebsiteThemeBundle' => 'AlphaLemon\\Theme\\BusinessWebsiteThemeBundle\\BusinessWebsiteThemeBundle',
                'BusinessSliderBundle' => 'AlphaLemon\\Block\\BusinessSliderBundle\\BusinessSliderBundle',
                'BusinessMenuBundle' => 'AlphaLemon\\Block\\BusinessMenuBundle\\BusinessMenuBundle',
                'BusinessDropCapBundle' => 'AlphaLemon\\Block\\BusinessDropCapBundle\\BusinessDropCapBundle',
                'BusinessCarouselBundle' => 'AlphaLemon\\Block\\BusinessCarouselBundle\\BusinessCarouselBundle',
                'AlphaLemonThemeEngineBundle' => 'AlphaLemon\\ThemeEngineBundle\\AlphaLemonThemeEngineBundle',
            ),
            'kernel.charset' => 'UTF-8',
            'kernel.container_class' => 'appTestProjectContainer',
            'database_driver' => 'pdo_mysql',
            'database_host' => 'localhost',
            'database_port' => NULL,
            'database_name' => 'symfony',
            'database_user' => 'root',
            'database_password' => NULL,
            'mailer_transport' => 'smtp',
            'mailer_host' => 'localhost',
            'mailer_user' => NULL,
            'mailer_password' => NULL,
            'locale' => 'en',
            'secret' => 'ThisTokenIsNotSoSecretChangeIt',
            'controller_resolver.class' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\ControllerResolver',
            'controller_name_converter.class' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\ControllerNameParser',
            'response_listener.class' => 'Symfony\\Component\\HttpKernel\\EventListener\\ResponseListener',
            'streamed_response_listener.class' => 'Symfony\\Component\\HttpKernel\\EventListener\\StreamedResponseListener',
            'locale_listener.class' => 'Symfony\\Component\\HttpKernel\\EventListener\\LocaleListener',
            'event_dispatcher.class' => 'Symfony\\Component\\EventDispatcher\\ContainerAwareEventDispatcher',
            'http_kernel.class' => 'Symfony\\Bundle\\FrameworkBundle\\HttpKernel',
            'filesystem.class' => 'Symfony\\Component\\Filesystem\\Filesystem',
            'cache_warmer.class' => 'Symfony\\Component\\HttpKernel\\CacheWarmer\\CacheWarmerAggregate',
            'cache_clearer.class' => 'Symfony\\Component\\HttpKernel\\CacheClearer\\ChainCacheClearer',
            'file_locator.class' => 'Symfony\\Component\\HttpKernel\\Config\\FileLocator',
            'translator.class' => 'Symfony\\Bundle\\FrameworkBundle\\Translation\\Translator',
            'translator.identity.class' => 'Symfony\\Component\\Translation\\IdentityTranslator',
            'translator.selector.class' => 'Symfony\\Component\\Translation\\MessageSelector',
            'translation.loader.php.class' => 'Symfony\\Component\\Translation\\Loader\\PhpFileLoader',
            'translation.loader.yml.class' => 'Symfony\\Component\\Translation\\Loader\\YamlFileLoader',
            'translation.loader.xliff.class' => 'Symfony\\Component\\Translation\\Loader\\XliffFileLoader',
            'translation.loader.po.class' => 'Symfony\\Component\\Translation\\Loader\\PoFileLoader',
            'translation.loader.mo.class' => 'Symfony\\Component\\Translation\\Loader\\MoFileLoader',
            'translation.loader.qt.class' => 'Symfony\\Component\\Translation\\Loader\\QtTranslationsLoader',
            'translation.loader.csv.class' => 'Symfony\\Component\\Translation\\Loader\\CsvFileLoader',
            'translation.loader.res.class' => 'Symfony\\Component\\Translation\\Loader\\IcuResFileLoader',
            'translation.loader.dat.class' => 'Symfony\\Component\\Translation\\Loader\\IcuDatFileLoader',
            'translation.loader.ini.class' => 'Symfony\\Component\\Translation\\Loader\\IniFileLoader',
            'translation.dumper.php.class' => 'Symfony\\Component\\Translation\\Dumper\\PhpFileDumper',
            'translation.dumper.xliff.class' => 'Symfony\\Component\\Translation\\Dumper\\XliffFileDumper',
            'translation.dumper.po.class' => 'Symfony\\Component\\Translation\\Dumper\\PoFileDumper',
            'translation.dumper.mo.class' => 'Symfony\\Component\\Translation\\Dumper\\MoFileDumper',
            'translation.dumper.yml.class' => 'Symfony\\Component\\Translation\\Dumper\\YamlFileDumper',
            'translation.dumper.qt.class' => 'Symfony\\Component\\Translation\\Dumper\\QtFileDumper',
            'translation.dumper.csv.class' => 'Symfony\\Component\\Translation\\Dumper\\CsvFileDumper',
            'translation.dumper.ini.class' => 'Symfony\\Component\\Translation\\Dumper\\IniFileDumper',
            'translation.dumper.res.class' => 'Symfony\\Component\\Translation\\Dumper\\IcuResFileDumper',
            'translation.extractor.php.class' => 'Symfony\\Bundle\\FrameworkBundle\\Translation\\PhpExtractor',
            'translation.loader.class' => 'Symfony\\Bundle\\FrameworkBundle\\Translation\\TranslationLoader',
            'translation.extractor.class' => 'Symfony\\Component\\Translation\\Extractor\\ChainExtractor',
            'translation.writer.class' => 'Symfony\\Component\\Translation\\Writer\\TranslationWriter',
            'kernel.secret' => 'ThisTokenIsNotSoSecretChangeIt',
            'kernel.trust_proxy_headers' => false,
            'kernel.default_locale' => 'en',
            'test.client.class' => 'Symfony\\Bundle\\FrameworkBundle\\Client',
            'test.client.parameters' => array(
            ),
            'test.client.history.class' => 'Symfony\\Component\\BrowserKit\\History',
            'test.client.cookiejar.class' => 'Symfony\\Component\\BrowserKit\\CookieJar',
            'test.session.listener.class' => 'Symfony\\Bundle\\FrameworkBundle\\EventListener\\TestSessionListener',
            'session.class' => 'Symfony\\Component\\HttpFoundation\\Session\\Session',
            'session.flashbag.class' => 'Symfony\\Component\\HttpFoundation\\Session\\Flash\\FlashBag',
            'session.attribute_bag.class' => 'Symfony\\Component\\HttpFoundation\\Session\\Attribute\\AttributeBag',
            'session.storage.native.class' => 'Symfony\\Component\\HttpFoundation\\Session\\Storage\\NativeSessionStorage',
            'session.storage.mock_file.class' => 'Symfony\\Component\\HttpFoundation\\Session\\Storage\\MockFileSessionStorage',
            'session.handler.native_file.class' => 'Symfony\\Component\\HttpFoundation\\Session\\Storage\\Handler\\NativeFileSessionHandler',
            'session_listener.class' => 'Symfony\\Bundle\\FrameworkBundle\\EventListener\\SessionListener',
            'session.storage.options' => array(
            ),
            'session.save_path' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/sessions',
            'form.resolved_type_factory.class' => 'Symfony\\Component\\Form\\ResolvedFormTypeFactory',
            'form.registry.class' => 'Symfony\\Component\\Form\\FormRegistry',
            'form.factory.class' => 'Symfony\\Component\\Form\\FormFactory',
            'form.extension.class' => 'Symfony\\Component\\Form\\Extension\\DependencyInjection\\DependencyInjectionExtension',
            'form.type_guesser.validator.class' => 'Symfony\\Component\\Form\\Extension\\Validator\\ValidatorTypeGuesser',
            'form.csrf_provider.class' => 'Symfony\\Component\\Form\\Extension\\Csrf\\CsrfProvider\\SessionCsrfProvider',
            'form.type_extension.csrf.enabled' => true,
            'form.type_extension.csrf.field_name' => '_token',
            'validator.class' => 'Symfony\\Component\\Validator\\Validator',
            'validator.mapping.class_metadata_factory.class' => 'Symfony\\Component\\Validator\\Mapping\\ClassMetadataFactory',
            'validator.mapping.cache.apc.class' => 'Symfony\\Component\\Validator\\Mapping\\Cache\\ApcCache',
            'validator.mapping.cache.prefix' => '',
            'validator.mapping.loader.loader_chain.class' => 'Symfony\\Component\\Validator\\Mapping\\Loader\\LoaderChain',
            'validator.mapping.loader.static_method_loader.class' => 'Symfony\\Component\\Validator\\Mapping\\Loader\\StaticMethodLoader',
            'validator.mapping.loader.annotation_loader.class' => 'Symfony\\Component\\Validator\\Mapping\\Loader\\AnnotationLoader',
            'validator.mapping.loader.xml_files_loader.class' => 'Symfony\\Component\\Validator\\Mapping\\Loader\\XmlFilesLoader',
            'validator.mapping.loader.yaml_files_loader.class' => 'Symfony\\Component\\Validator\\Mapping\\Loader\\YamlFilesLoader',
            'validator.validator_factory.class' => 'Symfony\\Bundle\\FrameworkBundle\\Validator\\ConstraintValidatorFactory',
            'validator.mapping.loader.xml_files_loader.mapping_files' => array(
                0 => '/home/alphalemon/tests/ThemeEngineBundle/vendor/symfony/symfony/src/Symfony/Component/Form/Resources/config/validation.xml',
            ),
            'validator.mapping.loader.yaml_files_loader.mapping_files' => array(
                0 => '/home/alphalemon/tests/ThemeEngineBundle/vendor/alphalemon/app-business-carousel-bundle/AlphaLemon/Block/BusinessCarouselBundle/Resources/config/validation.yml',
            ),
            'profiler.class' => 'Symfony\\Component\\HttpKernel\\Profiler\\Profiler',
            'profiler_listener.class' => 'Symfony\\Component\\HttpKernel\\EventListener\\ProfilerListener',
            'data_collector.config.class' => 'Symfony\\Component\\HttpKernel\\DataCollector\\ConfigDataCollector',
            'data_collector.request.class' => 'Symfony\\Component\\HttpKernel\\DataCollector\\RequestDataCollector',
            'data_collector.exception.class' => 'Symfony\\Component\\HttpKernel\\DataCollector\\ExceptionDataCollector',
            'data_collector.events.class' => 'Symfony\\Component\\HttpKernel\\DataCollector\\EventDataCollector',
            'data_collector.logger.class' => 'Symfony\\Component\\HttpKernel\\DataCollector\\LoggerDataCollector',
            'data_collector.time.class' => 'Symfony\\Component\\HttpKernel\\DataCollector\\TimeDataCollector',
            'data_collector.memory.class' => 'Symfony\\Component\\HttpKernel\\DataCollector\\MemoryDataCollector',
            'data_collector.router.class' => 'Symfony\\Bundle\\FrameworkBundle\\DataCollector\\RouterDataCollector',
            'profiler_listener.only_exceptions' => false,
            'profiler_listener.only_master_requests' => false,
            'profiler.storage.dsn' => 'file:/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/profiler',
            'profiler.storage.username' => '',
            'profiler.storage.password' => '',
            'profiler.storage.lifetime' => 86400,
            'router.class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\Router',
            'router.request_context.class' => 'Symfony\\Component\\Routing\\RequestContext',
            'routing.loader.class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\DelegatingLoader',
            'routing.resolver.class' => 'Symfony\\Component\\Config\\Loader\\LoaderResolver',
            'routing.loader.xml.class' => 'Symfony\\Component\\Routing\\Loader\\XmlFileLoader',
            'routing.loader.yml.class' => 'Symfony\\Component\\Routing\\Loader\\YamlFileLoader',
            'routing.loader.php.class' => 'Symfony\\Component\\Routing\\Loader\\PhpFileLoader',
            'router.options.generator_class' => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator',
            'router.options.generator_base_class' => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator',
            'router.options.generator_dumper_class' => 'Symfony\\Component\\Routing\\Generator\\Dumper\\PhpGeneratorDumper',
            'router.options.matcher_class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\RedirectableUrlMatcher',
            'router.options.matcher_base_class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\RedirectableUrlMatcher',
            'router.options.matcher_dumper_class' => 'Symfony\\Component\\Routing\\Matcher\\Dumper\\PhpMatcherDumper',
            'router.cache_warmer.class' => 'Symfony\\Bundle\\FrameworkBundle\\CacheWarmer\\RouterCacheWarmer',
            'router.options.matcher.cache_class' => 'apptestUrlMatcher',
            'router.options.generator.cache_class' => 'apptestUrlGenerator',
            'router_listener.class' => 'Symfony\\Component\\HttpKernel\\EventListener\\RouterListener',
            'router.request_context.host' => 'localhost',
            'router.request_context.scheme' => 'http',
            'router.resource' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/assetic/routing.yml',
            'request_listener.http_port' => 80,
            'request_listener.https_port' => 443,
            'templating.engine.delegating.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\DelegatingEngine',
            'templating.name_parser.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\TemplateNameParser',
            'templating.filename_parser.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\TemplateFilenameParser',
            'templating.cache_warmer.template_paths.class' => 'Symfony\\Bundle\\FrameworkBundle\\CacheWarmer\\TemplatePathsCacheWarmer',
            'templating.locator.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Loader\\TemplateLocator',
            'templating.loader.filesystem.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Loader\\FilesystemLoader',
            'templating.loader.cache.class' => 'Symfony\\Component\\Templating\\Loader\\CacheLoader',
            'templating.loader.chain.class' => 'Symfony\\Component\\Templating\\Loader\\ChainLoader',
            'templating.finder.class' => 'Symfony\\Bundle\\FrameworkBundle\\CacheWarmer\\TemplateFinder',
            'templating.engine.php.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\PhpEngine',
            'templating.helper.slots.class' => 'Symfony\\Component\\Templating\\Helper\\SlotsHelper',
            'templating.helper.assets.class' => 'Symfony\\Component\\Templating\\Helper\\CoreAssetsHelper',
            'templating.helper.actions.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\ActionsHelper',
            'templating.helper.router.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\RouterHelper',
            'templating.helper.request.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\RequestHelper',
            'templating.helper.session.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\SessionHelper',
            'templating.helper.code.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\CodeHelper',
            'templating.helper.translator.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\TranslatorHelper',
            'templating.helper.form.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\FormHelper',
            'templating.form.engine.class' => 'Symfony\\Component\\Form\\Extension\\Templating\\TemplatingRendererEngine',
            'templating.form.renderer.class' => 'Symfony\\Component\\Form\\FormRenderer',
            'templating.globals.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\GlobalVariables',
            'templating.asset.path_package.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Asset\\PathPackage',
            'templating.asset.url_package.class' => 'Symfony\\Component\\Templating\\Asset\\UrlPackage',
            'templating.asset.package_factory.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Asset\\PackageFactory',
            'templating.helper.code.file_link_format' => NULL,
            'templating.helper.form.resources' => array(
                0 => 'FrameworkBundle:Form',
            ),
            'templating.hinclude.default_template' => NULL,
            'templating.loader.cache.path' => NULL,
            'templating.engines' => array(
                0 => 'twig',
            ),
            'annotations.reader.class' => 'Doctrine\\Common\\Annotations\\AnnotationReader',
            'annotations.cached_reader.class' => 'Doctrine\\Common\\Annotations\\CachedReader',
            'annotations.file_cache_reader.class' => 'Doctrine\\Common\\Annotations\\FileCacheReader',
            'security.context.class' => 'Symfony\\Component\\Security\\Core\\SecurityContext',
            'security.user_checker.class' => 'Symfony\\Component\\Security\\Core\\User\\UserChecker',
            'security.encoder_factory.generic.class' => 'Symfony\\Component\\Security\\Core\\Encoder\\EncoderFactory',
            'security.encoder.digest.class' => 'Symfony\\Component\\Security\\Core\\Encoder\\MessageDigestPasswordEncoder',
            'security.encoder.plain.class' => 'Symfony\\Component\\Security\\Core\\Encoder\\PlaintextPasswordEncoder',
            'security.user.provider.in_memory.class' => 'Symfony\\Component\\Security\\Core\\User\\InMemoryUserProvider',
            'security.user.provider.in_memory.user.class' => 'Symfony\\Component\\Security\\Core\\User\\User',
            'security.user.provider.chain.class' => 'Symfony\\Component\\Security\\Core\\User\\ChainUserProvider',
            'security.authentication.trust_resolver.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\AuthenticationTrustResolver',
            'security.authentication.trust_resolver.anonymous_class' => 'Symfony\\Component\\Security\\Core\\Authentication\\Token\\AnonymousToken',
            'security.authentication.trust_resolver.rememberme_class' => 'Symfony\\Component\\Security\\Core\\Authentication\\Token\\RememberMeToken',
            'security.authentication.manager.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\AuthenticationProviderManager',
            'security.authentication.session_strategy.class' => 'Symfony\\Component\\Security\\Http\\Session\\SessionAuthenticationStrategy',
            'security.access.decision_manager.class' => 'Symfony\\Component\\Security\\Core\\Authorization\\AccessDecisionManager',
            'security.access.simple_role_voter.class' => 'Symfony\\Component\\Security\\Core\\Authorization\\Voter\\RoleVoter',
            'security.access.authenticated_voter.class' => 'Symfony\\Component\\Security\\Core\\Authorization\\Voter\\AuthenticatedVoter',
            'security.access.role_hierarchy_voter.class' => 'Symfony\\Component\\Security\\Core\\Authorization\\Voter\\RoleHierarchyVoter',
            'security.firewall.class' => 'Symfony\\Component\\Security\\Http\\Firewall',
            'security.firewall.map.class' => 'Symfony\\Bundle\\SecurityBundle\\Security\\FirewallMap',
            'security.firewall.context.class' => 'Symfony\\Bundle\\SecurityBundle\\Security\\FirewallContext',
            'security.matcher.class' => 'Symfony\\Component\\HttpFoundation\\RequestMatcher',
            'security.role_hierarchy.class' => 'Symfony\\Component\\Security\\Core\\Role\\RoleHierarchy',
            'security.http_utils.class' => 'Symfony\\Component\\Security\\Http\\HttpUtils',
            'security.validator.user_password.class' => 'Symfony\\Component\\Security\\Core\\Validator\\Constraint\\UserPasswordValidator',
            'security.authentication.retry_entry_point.class' => 'Symfony\\Component\\Security\\Http\\EntryPoint\\RetryAuthenticationEntryPoint',
            'security.channel_listener.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\ChannelListener',
            'security.authentication.form_entry_point.class' => 'Symfony\\Component\\Security\\Http\\EntryPoint\\FormAuthenticationEntryPoint',
            'security.authentication.listener.form.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\UsernamePasswordFormAuthenticationListener',
            'security.authentication.listener.basic.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\BasicAuthenticationListener',
            'security.authentication.basic_entry_point.class' => 'Symfony\\Component\\Security\\Http\\EntryPoint\\BasicAuthenticationEntryPoint',
            'security.authentication.listener.digest.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\DigestAuthenticationListener',
            'security.authentication.digest_entry_point.class' => 'Symfony\\Component\\Security\\Http\\EntryPoint\\DigestAuthenticationEntryPoint',
            'security.authentication.listener.x509.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\X509AuthenticationListener',
            'security.authentication.listener.anonymous.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\AnonymousAuthenticationListener',
            'security.authentication.switchuser_listener.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\SwitchUserListener',
            'security.logout_listener.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\LogoutListener',
            'security.logout.handler.session.class' => 'Symfony\\Component\\Security\\Http\\Logout\\SessionLogoutHandler',
            'security.logout.handler.cookie_clearing.class' => 'Symfony\\Component\\Security\\Http\\Logout\\CookieClearingLogoutHandler',
            'security.logout.success_handler.class' => 'Symfony\\Component\\Security\\Http\\Logout\\DefaultLogoutSuccessHandler',
            'security.access_listener.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\AccessListener',
            'security.access_map.class' => 'Symfony\\Component\\Security\\Http\\AccessMap',
            'security.exception_listener.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\ExceptionListener',
            'security.context_listener.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\ContextListener',
            'security.authentication.provider.dao.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\Provider\\DaoAuthenticationProvider',
            'security.authentication.provider.pre_authenticated.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\Provider\\PreAuthenticatedAuthenticationProvider',
            'security.authentication.provider.anonymous.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\Provider\\AnonymousAuthenticationProvider',
            'security.authentication.success_handler.class' => 'Symfony\\Component\\Security\\Http\\Authentication\\DefaultAuthenticationSuccessHandler',
            'security.authentication.failure_handler.class' => 'Symfony\\Component\\Security\\Http\\Authentication\\DefaultAuthenticationFailureHandler',
            'security.authentication.provider.rememberme.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\Provider\\RememberMeAuthenticationProvider',
            'security.authentication.listener.rememberme.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\RememberMeListener',
            'security.rememberme.token.provider.in_memory.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\RememberMe\\InMemoryTokenProvider',
            'security.authentication.rememberme.services.persistent.class' => 'Symfony\\Component\\Security\\Http\\RememberMe\\PersistentTokenBasedRememberMeServices',
            'security.authentication.rememberme.services.simplehash.class' => 'Symfony\\Component\\Security\\Http\\RememberMe\\TokenBasedRememberMeServices',
            'security.rememberme.response_listener.class' => 'Symfony\\Component\\Security\\Http\\RememberMe\\ResponseListener',
            'templating.helper.logout_url.class' => 'Symfony\\Bundle\\SecurityBundle\\Templating\\Helper\\LogoutUrlHelper',
            'templating.helper.security.class' => 'Symfony\\Bundle\\SecurityBundle\\Templating\\Helper\\SecurityHelper',
            'twig.extension.logout_url.class' => 'Symfony\\Bundle\\SecurityBundle\\Twig\\Extension\\LogoutUrlExtension',
            'twig.extension.security.class' => 'Symfony\\Bridge\\Twig\\Extension\\SecurityExtension',
            'data_collector.security.class' => 'Symfony\\Bundle\\SecurityBundle\\DataCollector\\SecurityDataCollector',
            'security.access.denied_url' => NULL,
            'security.authentication.manager.erase_credentials' => true,
            'security.authentication.session_strategy.strategy' => 'migrate',
            'security.access.always_authenticate_before_granting' => false,
            'security.authentication.hide_user_not_found' => true,
            'security.role_hierarchy.roles' => array(
                'ROLE_ADMIN' => array(
                    0 => 'ROLE_USER',
                ),
                'ROLE_SUPER_ADMIN' => array(
                    0 => 'ROLE_USER',
                    1 => 'ROLE_ADMIN',
                    2 => 'ROLE_ALLOWED_TO_SWITCH',
                ),
            ),
            'twig.class' => 'Twig_Environment',
            'twig.loader.class' => 'Symfony\\Bundle\\TwigBundle\\Loader\\FilesystemLoader',
            'templating.engine.twig.class' => 'Symfony\\Bundle\\TwigBundle\\TwigEngine',
            'twig.cache_warmer.class' => 'Symfony\\Bundle\\TwigBundle\\CacheWarmer\\TemplateCacheCacheWarmer',
            'twig.extension.trans.class' => 'Symfony\\Bridge\\Twig\\Extension\\TranslationExtension',
            'twig.extension.assets.class' => 'Symfony\\Bundle\\TwigBundle\\Extension\\AssetsExtension',
            'twig.extension.actions.class' => 'Symfony\\Bundle\\TwigBundle\\Extension\\ActionsExtension',
            'twig.extension.code.class' => 'Symfony\\Bundle\\TwigBundle\\Extension\\CodeExtension',
            'twig.extension.routing.class' => 'Symfony\\Bridge\\Twig\\Extension\\RoutingExtension',
            'twig.extension.yaml.class' => 'Symfony\\Bridge\\Twig\\Extension\\YamlExtension',
            'twig.extension.form.class' => 'Symfony\\Bridge\\Twig\\Extension\\FormExtension',
            'twig.form.engine.class' => 'Symfony\\Bridge\\Twig\\Form\\TwigRendererEngine',
            'twig.form.renderer.class' => 'Symfony\\Bridge\\Twig\\Form\\TwigRenderer',
            'twig.translation.extractor.class' => 'Symfony\\Bridge\\Twig\\Translation\\TwigExtractor',
            'twig.exception_listener.class' => 'Symfony\\Component\\HttpKernel\\EventListener\\ExceptionListener',
            'twig.exception_listener.controller' => 'Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction',
            'twig.form.resources' => array(
                0 => 'form_div_layout.html.twig',
            ),
            'twig.options' => array(
                'debug' => false,
                'strict_variables' => false,
                'exception_controller' => 'Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction',
                'cache' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/twig',
                'charset' => 'UTF-8',
                'paths' => array(
                ),
            ),
            'monolog.logger.class' => 'Symfony\\Bridge\\Monolog\\Logger',
            'monolog.gelf.publisher.class' => 'Gelf\\MessagePublisher',
            'monolog.handler.stream.class' => 'Monolog\\Handler\\StreamHandler',
            'monolog.handler.group.class' => 'Monolog\\Handler\\GroupHandler',
            'monolog.handler.buffer.class' => 'Monolog\\Handler\\BufferHandler',
            'monolog.handler.rotating_file.class' => 'Monolog\\Handler\\RotatingFileHandler',
            'monolog.handler.syslog.class' => 'Monolog\\Handler\\SyslogHandler',
            'monolog.handler.null.class' => 'Monolog\\Handler\\NullHandler',
            'monolog.handler.test.class' => 'Monolog\\Handler\\TestHandler',
            'monolog.handler.gelf.class' => 'Monolog\\Handler\\GelfHandler',
            'monolog.handler.firephp.class' => 'Symfony\\Bridge\\Monolog\\Handler\\FirePHPHandler',
            'monolog.handler.chromephp.class' => 'Symfony\\Bridge\\Monolog\\Handler\\ChromePhpHandler',
            'monolog.handler.debug.class' => 'Symfony\\Bridge\\Monolog\\Handler\\DebugHandler',
            'monolog.handler.swift_mailer.class' => 'Monolog\\Handler\\SwiftMailerHandler',
            'monolog.handler.native_mailer.class' => 'Monolog\\Handler\\NativeMailerHandler',
            'monolog.handler.socket.class' => 'Monolog\\Handler\\SocketHandler',
            'monolog.handler.fingers_crossed.class' => 'Monolog\\Handler\\FingersCrossedHandler',
            'monolog.handler.fingers_crossed.error_level_activation_strategy.class' => 'Monolog\\Handler\\FingersCrossed\\ErrorLevelActivationStrategy',
            'monolog.handlers_to_channels' => array(
                'monolog.handler.firephp' => NULL,
                'monolog.handler.main' => NULL,
            ),
            'swiftmailer.class' => 'Swift_Mailer',
            'swiftmailer.transport.sendmail.class' => 'Swift_Transport_SendmailTransport',
            'swiftmailer.transport.mail.class' => 'Swift_Transport_MailTransport',
            'swiftmailer.transport.failover.class' => 'Swift_Transport_FailoverTransport',
            'swiftmailer.plugin.redirecting.class' => 'Swift_Plugins_RedirectingPlugin',
            'swiftmailer.plugin.impersonate.class' => 'Swift_Plugins_ImpersonatePlugin',
            'swiftmailer.plugin.messagelogger.class' => 'Swift_Plugins_MessageLogger',
            'swiftmailer.plugin.antiflood.class' => 'Swift_Plugins_AntiFloodPlugin',
            'swiftmailer.plugin.antiflood.threshold' => 99,
            'swiftmailer.plugin.antiflood.sleep' => 0,
            'swiftmailer.data_collector.class' => 'Symfony\\Bridge\\Swiftmailer\\DataCollector\\MessageDataCollector',
            'swiftmailer.transport.smtp.encryption' => NULL,
            'swiftmailer.transport.smtp.port' => 25,
            'swiftmailer.transport.smtp.host' => 'localhost',
            'swiftmailer.transport.smtp.username' => NULL,
            'swiftmailer.transport.smtp.password' => NULL,
            'swiftmailer.transport.smtp.auth_mode' => NULL,
            'swiftmailer.transport.smtp.timeout' => 30,
            'swiftmailer.transport.smtp.source_ip' => NULL,
            'swiftmailer.plugin.blackhole.class' => 'Swift_Plugins_BlackholePlugin',
            'swiftmailer.spool.memory.class' => 'Swift_MemorySpool',
            'swiftmailer.email_sender.listener.class' => 'Symfony\\Bundle\\SwiftmailerBundle\\EventListener\\EmailSenderListener',
            'swiftmailer.spool.memory.path' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/swiftmailer/spool',
            'swiftmailer.spool.enabled' => true,
            'swiftmailer.sender_address' => NULL,
            'swiftmailer.single_address' => NULL,
            'swiftmailer.delivery_whitelist' => array(
            ),
            'assetic.asset_factory.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\AssetFactory',
            'assetic.asset_manager.class' => 'Assetic\\Factory\\LazyAssetManager',
            'assetic.asset_manager_cache_warmer.class' => 'Symfony\\Bundle\\AsseticBundle\\CacheWarmer\\AssetManagerCacheWarmer',
            'assetic.cached_formula_loader.class' => 'Assetic\\Factory\\Loader\\CachedFormulaLoader',
            'assetic.config_cache.class' => 'Assetic\\Cache\\ConfigCache',
            'assetic.config_loader.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\Loader\\ConfigurationLoader',
            'assetic.config_resource.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\Resource\\ConfigurationResource',
            'assetic.coalescing_directory_resource.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\Resource\\CoalescingDirectoryResource',
            'assetic.directory_resource.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\Resource\\DirectoryResource',
            'assetic.filter_manager.class' => 'Symfony\\Bundle\\AsseticBundle\\FilterManager',
            'assetic.worker.ensure_filter.class' => 'Assetic\\Factory\\Worker\\EnsureFilterWorker',
            'assetic.value_supplier.class' => 'Symfony\\Bundle\\AsseticBundle\\DefaultValueSupplier',
            'assetic.node.paths' => array(
            ),
            'assetic.cache_dir' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/assetic',
            'assetic.bundles' => array(
            ),
            'assetic.twig_extension.class' => 'Symfony\\Bundle\\AsseticBundle\\Twig\\AsseticExtension',
            'assetic.twig_formula_loader.class' => 'Assetic\\Extension\\Twig\\TwigFormulaLoader',
            'assetic.helper.dynamic.class' => 'Symfony\\Bundle\\AsseticBundle\\Templating\\DynamicAsseticHelper',
            'assetic.helper.static.class' => 'Symfony\\Bundle\\AsseticBundle\\Templating\\StaticAsseticHelper',
            'assetic.php_formula_loader.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\Loader\\AsseticHelperFormulaLoader',
            'assetic.debug' => false,
            'assetic.use_controller' => true,
            'assetic.enable_profiler' => false,
            'assetic.read_from' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/../web',
            'assetic.write_to' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/../web',
            'assetic.variables' => array(
            ),
            'assetic.java.bin' => '/usr/bin/java',
            'assetic.node.bin' => '/usr/bin/node',
            'assetic.ruby.bin' => '/usr/bin/ruby',
            'assetic.sass.bin' => '/usr/local/bin/sass',
            'assetic.filter.cssrewrite.class' => 'Assetic\\Filter\\CssRewriteFilter',
            'assetic.filter.yui_css.class' => 'Assetic\\Filter\\Yui\\CssCompressorFilter',
            'assetic.filter.yui_css.java' => '/usr/bin/java',
            'assetic.filter.yui_css.jar' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/Resources/java/yuicompressor.jar',
            'assetic.filter.yui_css.charset' => 'UTF-8',
            'assetic.filter.yui_js.class' => 'Assetic\\Filter\\Yui\\JsCompressorFilter',
            'assetic.filter.yui_js.java' => '/usr/bin/java',
            'assetic.filter.yui_js.jar' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/Resources/java/yuicompressor.jar',
            'assetic.filter.yui_js.charset' => 'UTF-8',
            'assetic.filter.yui_js.nomunge' => NULL,
            'assetic.filter.yui_js.preserve_semi' => NULL,
            'assetic.filter.yui_js.disable_optimizations' => NULL,
            'assetic.twig_extension.functions' => array(
            ),
            'assetic.controller.class' => 'Symfony\\Bundle\\AsseticBundle\\Controller\\AsseticController',
            'assetic.routing_loader.class' => 'Symfony\\Bundle\\AsseticBundle\\Routing\\AsseticLoader',
            'assetic.cache.class' => 'Assetic\\Cache\\FilesystemCache',
            'assetic.use_controller_worker.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\Worker\\UseControllerWorker',
            'assetic.request_listener.class' => 'Symfony\\Bundle\\AsseticBundle\\EventListener\\RequestListener',
            'sensio_framework_extra.view.guesser.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\Templating\\TemplateGuesser',
            'sensio_framework_extra.controller.listener.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\EventListener\\ControllerListener',
            'sensio_framework_extra.routing.loader.annot_dir.class' => 'Symfony\\Component\\Routing\\Loader\\AnnotationDirectoryLoader',
            'sensio_framework_extra.routing.loader.annot_file.class' => 'Symfony\\Component\\Routing\\Loader\\AnnotationFileLoader',
            'sensio_framework_extra.routing.loader.annot_class.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\Routing\\AnnotatedRouteControllerLoader',
            'sensio_framework_extra.converter.listener.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\EventListener\\ParamConverterListener',
            'sensio_framework_extra.converter.manager.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\Request\\ParamConverter\\ParamConverterManager',
            'sensio_framework_extra.converter.doctrine.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\Request\\ParamConverter\\DoctrineParamConverter',
            'sensio_framework_extra.converter.datetime.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\Request\\ParamConverter\\DateTimeParamConverter',
            'sensio_framework_extra.view.listener.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\EventListener\\TemplateListener',
            'jms_aop.cache_dir' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/jms_aop',
            'jms_aop.interceptor_loader.class' => 'JMS\\AopBundle\\Aop\\InterceptorLoader',
            'jms_di_extra.metadata.driver.annotation_driver.class' => 'JMS\\DiExtraBundle\\Metadata\\Driver\\AnnotationDriver',
            'jms_di_extra.metadata.driver.configured_controller_injections.class' => 'JMS\\DiExtraBundle\\Metadata\\Driver\\ConfiguredControllerInjectionsDriver',
            'jms_di_extra.metadata.driver.lazy_loading_driver.class' => 'Metadata\\Driver\\LazyLoadingDriver',
            'jms_di_extra.metadata.metadata_factory.class' => 'Metadata\\MetadataFactory',
            'jms_di_extra.metadata.cache.file_cache.class' => 'Metadata\\Cache\\FileCache',
            'jms_di_extra.metadata.converter.class' => 'JMS\\DiExtraBundle\\Metadata\\MetadataConverter',
            'jms_di_extra.controller_resolver.class' => 'JMS\\DiExtraBundle\\HttpKernel\\ControllerResolver',
            'jms_di_extra.controller_injectors_warmer.class' => 'JMS\\DiExtraBundle\\HttpKernel\\ControllerInjectorsWarmer',
            'jms_di_extra.all_bundles' => false,
            'jms_di_extra.bundles' => array(
            ),
            'jms_di_extra.directories' => array(
            ),
            'jms_di_extra.cache_dir' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/jms_diextra',
            'jms_di_extra.doctrine_integration' => true,
            'jms_di_extra.doctrine_integration.entity_manager.file' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/jms_diextra/doctrine/EntityManager_50963470a1c3b.php',
            'jms_di_extra.doctrine_integration.entity_manager.class' => 'EntityManager50963470a1c3b_546a8d27f194334ee012bfe64f629947b07e4919\\__CG__\\Doctrine\\ORM\\EntityManager',
            'security.secured_services' => array(
            ),
            'security.access.method_interceptor.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\Interception\\MethodSecurityInterceptor',
            'security.access.method_access_control' => array(
            ),
            'security.access.run_as_manager.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\RunAsManager',
            'security.authentication.provider.run_as.class' => 'JMS\\SecurityExtraBundle\\Security\\Authentication\\Provider\\RunAsAuthenticationProvider',
            'security.run_as.key' => 'RunAsToken',
            'security.run_as.role_prefix' => 'ROLE_',
            'security.access.after_invocation_manager.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\AfterInvocation\\AfterInvocationManager',
            'security.access.after_invocation.acl_provider.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\AfterInvocation\\AclAfterInvocationProvider',
            'security.access.iddqd_voter.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\Voter\\IddqdVoter',
            'security.extra.metadata_factory.class' => 'Metadata\\MetadataFactory',
            'security.extra.lazy_loading_driver.class' => 'Metadata\\Driver\\LazyLoadingDriver',
            'security.extra.driver_chain.class' => 'Metadata\\Driver\\DriverChain',
            'security.extra.annotation_driver.class' => 'JMS\\SecurityExtraBundle\\Metadata\\Driver\\AnnotationDriver',
            'security.extra.file_cache.class' => 'Metadata\\Cache\\FileCache',
            'security.access.secure_all_services' => false,
            'security.extra.cache_dir' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/cache/test/jms_security',
            'security.acl.permission_evaluator.class' => 'JMS\\SecurityExtraBundle\\Security\\Acl\\Expression\\PermissionEvaluator',
            'security.acl.has_permission_compiler.class' => 'JMS\\SecurityExtraBundle\\Security\\Acl\\Expression\\HasPermissionFunctionCompiler',
            'security.expressions.voter.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\Expression\\LazyLoadingExpressionVoter',
            'security.expressions.handler.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\Expression\\ContainerAwareExpressionHandler',
            'security.expressions.compiler.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\Expression\\ExpressionCompiler',
            'security.expressions.expression.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\Expression\\Expression',
            'security.expressions.variable_compiler.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\Expression\\Compiler\\ContainerAwareVariableCompiler',
            'security.expressions.parameter_compiler.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\Expression\\Compiler\\ParameterExpressionCompiler',
            'security.extra.config_driver.class' => 'JMS\\SecurityExtraBundle\\Metadata\\Driver\\ConfigDriver',
            'security.extra.twig_extension.class' => 'JMS\\SecurityExtraBundle\\Twig\\SecurityExtension',
            'security.authenticated_voter.disabled' => false,
            'security.role_voter.disabled' => false,
            'security.acl_voter.disabled' => false,
            'web_profiler.debug_toolbar.class' => 'Symfony\\Bundle\\WebProfilerBundle\\EventListener\\WebDebugToolbarListener',
            'web_profiler.debug_toolbar.intercept_redirects' => false,
            'web_profiler.debug_toolbar.mode' => 1,
            'web_profiler.debug_toolbar.position' => 'bottom',
            'sensio.distribution.webconfigurator.class' => 'Sensio\\Bundle\\DistributionBundle\\Configurator\\Configurator',
            'alphalemon_bootstrap.autoloaders_collection.class' => 'AlphaLemon\\BootstrapBundle\\Core\\Json\\JsonAutoloaderCollection',
            'alphalemon_bootstrap.routing.loader.class' => 'AlphaLemon\\BootstrapBundle\\Core\\Loader\\RoutingLoader',
            'alphalemon_bootstrap.post_actions.class' => 'AlphaLemon\\BootstrapBundle\\Core\\Listener\\ExecutePostActionsListener',
            'alphalemon_bootstrap.routing_dir' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/config/bundles/routing',
            'alphalemon_bootstrap.vendor_dir' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/../vendor',
            'business_website.home.external_stylesheets' => array(
                0 => '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css',
                1 => '@BusinessWebsiteThemeBundle/Resources/public/css/layout.css',
                2 => '@BusinessWebsiteThemeBundle/Resources/public/css/style.css',
                3 => '@BusinessWebsiteThemeBundle/Resources/public/css/al_fix_style.css',
            ),
            'business_website.home.external_javascripts' => array(
                0 => '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*',
                1 => '@BusinessWebsiteThemeBundle/Resources/public/js/cufon-yui.js',
                2 => '@BusinessWebsiteThemeBundle/Resources/public/js/al-cufon-replace.js',
                3 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_400.font.js',
                4 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_700.font.js',
                5 => '@BusinessWebsiteThemeBundle/Resources/public/js/jquery.easing.1.3.js',
                6 => '@BusinessWebsiteThemeBundle/Resources/public/js/jcarousellite.js',
            ),
            'business_website.home.external_stylesheets.cms' => array(
                0 => '@BusinessWebsiteThemeBundle/Resources/public/css/cms_fix.css',
            ),
            'business_website.fullpage.external_stylesheets' => array(
                0 => '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css',
                1 => '@BusinessWebsiteThemeBundle/Resources/public/css/layout.css',
                2 => '@BusinessWebsiteThemeBundle/Resources/public/css/style.css',
                3 => '@BusinessWebsiteThemeBundle/Resources/public/css/al_fix_style.css',
            ),
            'business_website.fullpage.external_javascripts' => array(
                0 => '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*',
                1 => '@BusinessWebsiteThemeBundle/Resources/public/js/cufon-yui.js',
                2 => '@BusinessWebsiteThemeBundle/Resources/public/js//al-cufon-replace.js',
                3 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_400.font.js',
                4 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_700.font.js',
                5 => '@BusinessWebsiteThemeBundle/Resources/public/js/tabs.js',
            ),
            'business_website.fullpage.external_stylesheets.cms' => array(
            ),
            'business_website.rightcolumn.external_stylesheets' => array(
                0 => '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css',
                1 => '@BusinessWebsiteThemeBundle/Resources/public/css/layout.css',
                2 => '@BusinessWebsiteThemeBundle/Resources/public/css/style.css',
                3 => '@BusinessWebsiteThemeBundle/Resources/public/css/al_fix_style.css',
            ),
            'business_website.rightcolumn.external_javascripts' => array(
                0 => '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*',
                1 => '@BusinessWebsiteThemeBundle/Resources/public/js/cufon-yui.js',
                2 => '@BusinessWebsiteThemeBundle/Resources/public/js//al-cufon-replace.js',
                3 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_400.font.js',
                4 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_700.font.js',
                5 => '@BusinessWebsiteThemeBundle/Resources/public/js/tabs.js',
            ),
            'business_website.rightcolumn.external_stylesheets.cms' => array(
            ),
            'business_website.sixboxes.external_stylesheets' => array(
                0 => '@BusinessWebsiteThemeBundle/Resources/public/css/reset.css',
                1 => '@BusinessWebsiteThemeBundle/Resources/public/css/layout.css',
                2 => '@BusinessWebsiteThemeBundle/Resources/public/css/style.css',
                3 => '@BusinessWebsiteThemeBundle/Resources/public/css/al_fix_style.css',
            ),
            'business_website.sixboxes.external_javascripts' => array(
                0 => '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*',
                1 => '@BusinessWebsiteThemeBundle/Resources/public/js/cufon-yui.js',
                2 => '@BusinessWebsiteThemeBundle/Resources/public/js//al-cufon-replace.js',
                3 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_400.font.js',
                4 => '@BusinessWebsiteThemeBundle/Resources/public/js/Swis721_Cn_BT_700.font.js',
                5 => '@BusinessWebsiteThemeBundle/Resources/public/js/tabs.js',
            ),
            'business_website.sixboxes.external_stylesheets.cms' => array(
            ),
            'businessslider.editor_settings' => array(
                'html_editor' => true,
                'internal_js' => true,
            ),
            'businessslider.external_stylesheets' => array(
                0 => '@BusinessSliderBundle/Resources/public/css/business-slider.css',
            ),
            'businessslider.external_javascripts' => array(
                0 => '@BusinessSliderBundle/Resources/public/js/tms-0.3.js',
                1 => '@BusinessSliderBundle/Resources/public/js/tms_presets.js',
                2 => '@BusinessSliderBundle/Resources/public/js/slider.js',
            ),
            'businessmenu.external_stylesheets' => array(
                0 => '@BusinessMenuBundle/Resources/public/css/business-menu.css',
            ),
            'businessmenu.external_javascripts' => array(
                0 => '@BusinessMenuBundle/Resources/public/js/cufon-yui.js',
                1 => '@BusinessMenuBundle/Resources/public/js/al-cufon-replace.js',
                2 => '@BusinessMenuBundle/Resources/public/js/Swis721_Cn_BT_400.font.js',
                3 => '@BusinessMenuBundle/Resources/public/js/Swis721_Cn_BT_700.font.js',
            ),
            'businessdropcap_editor_settings' => array(
                'html_editor' => true,
            ),
            'businessdropcap.external_stylesheets' => array(
                0 => '@BusinessDropCapBundle/Resources/public/css/business-dropcap.css',
            ),
            'businessdropcap.external_stylesheets.cms' => array(
                0 => '@BusinessDropCapBundle/Resources/public/css/business-dropcap-editor.css',
            ),
            'dropcapeditor.external_javascripts' => array(
                0 => '@BusinessMenuBundle/Resources/public/js/cufon-yui.js',
                1 => '@BusinessMenuBundle/Resources/public/js/al-cufon-replace.js',
                2 => '@BusinessMenuBundle/Resources/public/js/Swis721_Cn_BT_400.font.js',
                3 => '@BusinessMenuBundle/Resources/public/js/Swis721_Cn_BT_700.font.js',
            ),
            'businesscarousel_editor_settings' => array(
                'html_editor' => true,
            ),
            'businesscarousel.external_stylesheets' => array(
                0 => '@BusinessCarouselBundle/Resources/public/css/business-carousel.css',
            ),
            'businesscarousel.external_javascripts' => array(
                0 => '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*',
                1 => '@BusinessCarouselBundle/Resources/public/js/jcarousellite.js',
                2 => '@BusinessCarouselBundle/Resources/public/js/carousel.js',
            ),
            'businesscarousel.external_stylesheets.cms' => array(
                0 => '@BusinessCarouselBundle/Resources/public/css/business-carousel-editor.css',
            ),
            'twig.extension.render_slot.class' => 'AlphaLemon\\ThemeEngineBundle\\Twig\\SlotRendererExtension',
            'twig.extension.file.class' => 'AlphaLemon\\ThemeEngineBundle\\Twig\\FileExtension',
            'alpha_lemon_theme_engine.base_template' => 'AlphaLemonThemeEngineBundle:Theme:base.html.twig',
            'alpha_lemon_theme_engine.active_theme_file' => '/home/alphalemon/tests/ThemeEngineBundle/Tests/Functional/app/Resources/.tests_active_theme',
            'alpha_lemon_theme_engine.themes_panel.base_theme' => 'AlphaLemonThemeEngineBundle:Themes:index.html.twig',
            'alpha_lemon_theme_engine.themes_panel.theme_section' => 'AlphaLemonThemeEngineBundle:Themes:theme_panel_sections.html.twig',
            'alpha_lemon_theme_engine.themes_panel.theme_skeleton' => 'AlphaLemonThemeEngineBundle:Themes:theme_skeleton.html.twig',
            'alpha_lemon_theme_engine.info_valid_entries' => array(
                0 => 'title',
                1 => 'description',
                2 => 'author',
                3 => 'license',
                4 => 'website',
                5 => 'email',
                6 => 'version',
            ),
            'alpha_lemon_theme_engine.page_tree.class' => 'AlphaLemon\\ThemeEngineBundle\\Core\\PageTree\\AlPageTree',
            'alpha_lemon_theme_engine.active_theme.class' => 'AlphaLemon\\ThemeEngineBundle\\Core\\Theme\\AlActiveTheme',
            'alpha_lemon_theme_engine.themes.class' => 'AlphaLemon\\ThemeEngineBundle\\Core\\ThemesCollection\\AlThemesCollection',
            'alpha_lemon_theme_engine.theme.class' => 'AlphaLemon\\ThemeEngineBundle\\Core\\Theme\\AlTheme',
            'alpha_lemon_theme_engine.slot.class' => 'AlphaLemon\\ThemeEngineBundle\\Core\\TemplateSlots\\AlSlot',
            'alpha_lemon_theme_engine.template.class' => 'AlphaLemon\\ThemeEngineBundle\\Core\\Template\\AlTemplate',
            'alpha_lemon_theme_engine.template_assets.class' => 'AlphaLemon\\ThemeEngineBundle\\Core\\Template\\AlTemplateAssets',
            'alpha_lemon_theme_engine.template_slots.class' => 'AlphaLemon\\ThemeEngineBundle\\Core\\TemplateSlots\\AlTemplateSlots',
            'alpha_lemon_theme_engine.404_error_handler.class' => 'AlphaLemon\\ThemeEngineBundle\\Core\\Listener\\NotFoundErrorHandlerListener',
            'alpha_lemon_theme_engine.deploy_bundle' => 'AlphaLemonWebSiteBundle',
            'data_collector.templates' => array(
                'data_collector.config' => array(
                    0 => 'config',
                    1 => 'WebProfilerBundle:Collector:config',
                ),
                'data_collector.request' => array(
                    0 => 'request',
                    1 => 'WebProfilerBundle:Collector:request',
                ),
                'data_collector.exception' => array(
                    0 => 'exception',
                    1 => 'WebProfilerBundle:Collector:exception',
                ),
                'data_collector.events' => array(
                    0 => 'events',
                    1 => 'WebProfilerBundle:Collector:events',
                ),
                'data_collector.logger' => array(
                    0 => 'logger',
                    1 => 'WebProfilerBundle:Collector:logger',
                ),
                'data_collector.time' => array(
                    0 => 'time',
                    1 => 'WebProfilerBundle:Collector:time',
                ),
                'data_collector.memory' => array(
                    0 => 'memory',
                    1 => 'WebProfilerBundle:Collector:memory',
                ),
                'data_collector.router' => array(
                    0 => 'router',
                    1 => 'WebProfilerBundle:Collector:router',
                ),
                'data_collector.security' => array(
                    0 => 'security',
                    1 => 'SecurityBundle:Collector:security',
                ),
            ),
        );
    }
}
