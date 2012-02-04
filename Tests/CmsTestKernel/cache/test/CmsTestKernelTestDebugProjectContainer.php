<?php

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * CmsTestKernelTestDebugProjectContainer
 *
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 */
class CmsTestKernelTestDebugProjectContainer extends Container
{
    /**
     * Constructor.
     */
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

    /**
     * Gets the 'al_assets_builder' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\AlphaLemonCmsBundle\Core\AssetsBuilder\AlAssetsBuilder A AlphaLemon\AlphaLemonCmsBundle\Core\AssetsBuilder\AlAssetsBuilder instance.
     */
    protected function getAlAssetsBuilderService()
    {
        return $this->services['al_assets_builder'] = new \AlphaLemon\AlphaLemonCmsBundle\Core\AssetsBuilder\AlAssetsBuilder($this);
    }

    /**
     * Gets the 'al_cms_finalize_content_listener.class' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\AlphaLemonCmsBundle\Core\Listener\AlFinalizeContentsListener A AlphaLemon\AlphaLemonCmsBundle\Core\Listener\AlFinalizeContentsListener instance.
     */
    protected function getAlCmsFinalizeContentListener_ClassService()
    {
        return $this->services['al_cms_finalize_content_listener.class'] = new \AlphaLemon\AlphaLemonCmsBundle\Core\Listener\AlFinalizeContentsListener($this);
    }

    /**
     * Gets the 'al_cms_setup_listener.class' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\AlphaLemonCmsBundle\Core\Listener\AlCmsSetupListener A AlphaLemon\AlphaLemonCmsBundle\Core\Listener\AlCmsSetupListener instance.
     */
    protected function getAlCmsSetupListener_ClassService()
    {
        return $this->services['al_cms_setup_listener.class'] = new \AlphaLemon\AlphaLemonCmsBundle\Core\Listener\AlCmsSetupListener($this);
    }

    /**
     * Gets the 'al_frontend_request_listener.class' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\FrontendBundle\Core\Listener\AlFrontendRequestListener A AlphaLemon\FrontendBundle\Core\Listener\AlFrontendRequestListener instance.
     */
    protected function getAlFrontendRequestListener_ClassService()
    {
        return $this->services['al_frontend_request_listener.class'] = new \AlphaLemon\FrontendBundle\Core\Listener\AlFrontendRequestListener($this);
    }

    /**
     * Gets the 'al_language_manager' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager A AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager instance.
     */
    protected function getAlLanguageManagerService()
    {
        return $this->services['al_language_manager'] = new \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager($this);
    }

    /**
     * Gets the 'al_media_editor_renderer.class' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\AlMediaBundle\Core\Listener\RenderedEditorListener A AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\AlMediaBundle\Core\Listener\RenderedEditorListener instance.
     */
    protected function getAlMediaEditorRenderer_ClassService()
    {
        return $this->services['al_media_editor_renderer.class'] = new \AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\AlMediaBundle\Core\Listener\RenderedEditorListener();
    }

    /**
     * Gets the 'al_page_attributes_manager' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageAttributes\AlPageAttributesManager A AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageAttributes\AlPageAttributesManager instance.
     */
    protected function getAlPageAttributesManagerService()
    {
        return $this->services['al_page_attributes_manager'] = new \AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageAttributes\AlPageAttributesManager($this);
    }

    /**
     * Gets the 'al_page_manager' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager A AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager instance.
     */
    protected function getAlPageManagerService()
    {
        return $this->services['al_page_manager'] = new \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager($this);
    }

    /**
     * Gets the 'al_page_tree' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree A AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree instance.
     */
    protected function getAlPageTreeService()
    {
        return $this->services['al_page_tree'] = new \AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree($this);
    }

    /**
     * Gets the 'alphalemon.cms.security.provider' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\AlphaLemonCmsBundle\Core\Security\Provider\AlCmsAlUserProvider A AlphaLemon\AlphaLemonCmsBundle\Core\Security\Provider\AlCmsAlUserProvider instance.
     */
    protected function getAlphalemon_Cms_Security_ProviderService()
    {
        return $this->services['alphalemon.cms.security.provider'] = new \AlphaLemon\AlphaLemonCmsBundle\Core\Security\Provider\AlCmsAlUserProvider();
    }

    /**
     * Gets the 'annotation_reader' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Doctrine\Common\Annotations\FileCacheReader A Doctrine\Common\Annotations\FileCacheReader instance.
     */
    protected function getAnnotationReaderService()
    {
        return $this->services['annotation_reader'] = new \Doctrine\Common\Annotations\FileCacheReader(new \Doctrine\Common\Annotations\AnnotationReader(), '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/cache/test/annotations', true);
    }

    /**
     * Gets the 'cache_warmer' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate A Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate instance.
     */
    protected function getCacheWarmerService()
    {
        $a = $this->get('kernel');
        $b = $this->get('templating.name_parser');

        $c = new \Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplateFinder($a, $b, '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/Resources');

        return $this->services['cache_warmer'] = new \Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate(array(0 => new \Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplatePathsCacheWarmer($c, $this->get('templating.locator')), 1 => new \Symfony\Bundle\FrameworkBundle\CacheWarmer\RouterCacheWarmer($this->get('router')), 2 => new \Symfony\Bundle\TwigBundle\CacheWarmer\TemplateCacheCacheWarmer($this, $c)));
    }

    /**
     * Gets the 'el_finder_connector' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\ElFinderBundle\Core\Connector\AlphaLemonElFinderConnector A AlphaLemon\ElFinderBundle\Core\Connector\AlphaLemonElFinderConnector instance.
     */
    protected function getElFinderConnectorService()
    {
        return $this->services['el_finder_connector'] = new \AlphaLemon\ElFinderBundle\Core\Connector\AlphaLemonElFinderConnector($this);
    }

    /**
     * Gets the 'el_finder_css_connector' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\ElFinderStylesheetsConnector A AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\ElFinderStylesheetsConnector instance.
     */
    protected function getElFinderCssConnectorService()
    {
        return $this->services['el_finder_css_connector'] = new \AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\ElFinderStylesheetsConnector($this);
    }

    /**
     * Gets the 'el_finder_js_connector' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\ElFinderJavascriptsConnector A AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\ElFinderJavascriptsConnector instance.
     */
    protected function getElFinderJsConnectorService()
    {
        return $this->services['el_finder_js_connector'] = new \AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\ElFinderJavascriptsConnector($this);
    }

    /**
     * Gets the 'el_finder_media_connector' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\ElFinderMediaConnector A AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\ElFinderMediaConnector instance.
     */
    protected function getElFinderMediaConnectorService()
    {
        return $this->services['el_finder_media_connector'] = new \AlphaLemon\AlphaLemonCmsBundle\Core\ElFinder\ElFinderMediaConnector($this);
    }

    /**
     * Gets the 'event_dispatcher' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Debug\TraceableEventDispatcher A Symfony\Bundle\FrameworkBundle\Debug\TraceableEventDispatcher instance.
     */
    protected function getEventDispatcherService()
    {
        $this->services['event_dispatcher'] = $instance = new \Symfony\Bundle\FrameworkBundle\Debug\TraceableEventDispatcher($this, NULL);

        $instance->addListenerService('actions.block_editor_rendered', array(0 => 'al_media_editor_renderer.class', 1 => 'onBlockEditorRendered'), 0);
        $instance->addListenerService('kernel.request', array(0 => 'router_listener', 1 => 'onEarlyKernelRequest'), 255);
        $instance->addListenerService('kernel.request', array(0 => 'router_listener', 1 => 'onKernelRequest'), 0);
        $instance->addListenerService('kernel.response', array(0 => 'response_listener', 1 => 'onKernelResponse'), 0);
        $instance->addListenerService('kernel.request', array(0 => 'test.session.listener', 1 => 'onKernelRequest'), 192);
        $instance->addListenerService('kernel.response', array(0 => 'test.session.listener', 1 => 'onKernelResponse'), -128);
        $instance->addListenerService('kernel.request', array(0 => 'session_listener', 1 => 'onKernelRequest'), 128);
        $instance->addListenerService('kernel.exception', array(0 => 'twig.exception_listener', 1 => 'onKernelException'), -128);
        $instance->addListenerService('kernel.request', array(0 => 'al_frontend_request_listener.class', 1 => 'onKernelRequest'), -512);
        $instance->addListenerService('kernel.request', array(0 => 'al_cms_setup_listener.class', 1 => 'onKernelRequest'), 0);
        $instance->addListenerService('kernel.response', array(0 => 'al_cms_finalize_content_listener.class', 1 => 'onKernelResponse'), 0);

        return $instance;
    }

    /**
     * Gets the 'file_locator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\Config\FileLocator A Symfony\Component\HttpKernel\Config\FileLocator instance.
     */
    protected function getFileLocatorService()
    {
        return $this->services['file_locator'] = new \Symfony\Component\HttpKernel\Config\FileLocator($this->get('kernel'), '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/Resources');
    }

    /**
     * Gets the 'filesystem' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Filesystem\Filesystem A Symfony\Component\Filesystem\Filesystem instance.
     */
    protected function getFilesystemService()
    {
        return $this->services['filesystem'] = new \Symfony\Component\Filesystem\Filesystem();
    }

    /**
     * Gets the 'form.csrf_provider' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider A Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider instance.
     */
    protected function getForm_CsrfProviderService()
    {
        return $this->services['form.csrf_provider'] = new \Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider($this->get('session'), 'secret');
    }

    /**
     * Gets the 'form.factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\FormFactory A Symfony\Component\Form\FormFactory instance.
     */
    protected function getForm_FactoryService()
    {
        return $this->services['form.factory'] = new \Symfony\Component\Form\FormFactory(array(0 => new \Symfony\Component\Form\Extension\DependencyInjection\DependencyInjectionExtension($this, array('field' => 'form.type.field', 'form' => 'form.type.form', 'birthday' => 'form.type.birthday', 'checkbox' => 'form.type.checkbox', 'choice' => 'form.type.choice', 'collection' => 'form.type.collection', 'country' => 'form.type.country', 'date' => 'form.type.date', 'datetime' => 'form.type.datetime', 'email' => 'form.type.email', 'file' => 'form.type.file', 'hidden' => 'form.type.hidden', 'integer' => 'form.type.integer', 'language' => 'form.type.language', 'locale' => 'form.type.locale', 'money' => 'form.type.money', 'number' => 'form.type.number', 'password' => 'form.type.password', 'percent' => 'form.type.percent', 'radio' => 'form.type.radio', 'repeated' => 'form.type.repeated', 'search' => 'form.type.search', 'textarea' => 'form.type.textarea', 'text' => 'form.type.text', 'time' => 'form.type.time', 'timezone' => 'form.type.timezone', 'url' => 'form.type.url', 'csrf' => 'form.type.csrf', 'model' => 'propel.form.type.model'), array('field' => array(0 => 'form.type_extension.field'), 'form' => array(0 => 'form.type_extension.csrf')), array(0 => 'form.type_guesser.validator', 1 => 'form.type_guesser.propel'))));
    }

    /**
     * Gets the 'form.type.birthday' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\BirthdayType A Symfony\Component\Form\Extension\Core\Type\BirthdayType instance.
     */
    protected function getForm_Type_BirthdayService()
    {
        return $this->services['form.type.birthday'] = new \Symfony\Component\Form\Extension\Core\Type\BirthdayType();
    }

    /**
     * Gets the 'form.type.checkbox' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\CheckboxType A Symfony\Component\Form\Extension\Core\Type\CheckboxType instance.
     */
    protected function getForm_Type_CheckboxService()
    {
        return $this->services['form.type.checkbox'] = new \Symfony\Component\Form\Extension\Core\Type\CheckboxType();
    }

    /**
     * Gets the 'form.type.choice' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\ChoiceType A Symfony\Component\Form\Extension\Core\Type\ChoiceType instance.
     */
    protected function getForm_Type_ChoiceService()
    {
        return $this->services['form.type.choice'] = new \Symfony\Component\Form\Extension\Core\Type\ChoiceType();
    }

    /**
     * Gets the 'form.type.collection' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\CollectionType A Symfony\Component\Form\Extension\Core\Type\CollectionType instance.
     */
    protected function getForm_Type_CollectionService()
    {
        return $this->services['form.type.collection'] = new \Symfony\Component\Form\Extension\Core\Type\CollectionType();
    }

    /**
     * Gets the 'form.type.country' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\CountryType A Symfony\Component\Form\Extension\Core\Type\CountryType instance.
     */
    protected function getForm_Type_CountryService()
    {
        return $this->services['form.type.country'] = new \Symfony\Component\Form\Extension\Core\Type\CountryType();
    }

    /**
     * Gets the 'form.type.csrf' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Csrf\Type\CsrfType A Symfony\Component\Form\Extension\Csrf\Type\CsrfType instance.
     */
    protected function getForm_Type_CsrfService()
    {
        return $this->services['form.type.csrf'] = new \Symfony\Component\Form\Extension\Csrf\Type\CsrfType($this->get('form.csrf_provider'));
    }

    /**
     * Gets the 'form.type.date' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\DateType A Symfony\Component\Form\Extension\Core\Type\DateType instance.
     */
    protected function getForm_Type_DateService()
    {
        return $this->services['form.type.date'] = new \Symfony\Component\Form\Extension\Core\Type\DateType();
    }

    /**
     * Gets the 'form.type.datetime' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\DateTimeType A Symfony\Component\Form\Extension\Core\Type\DateTimeType instance.
     */
    protected function getForm_Type_DatetimeService()
    {
        return $this->services['form.type.datetime'] = new \Symfony\Component\Form\Extension\Core\Type\DateTimeType();
    }

    /**
     * Gets the 'form.type.email' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\EmailType A Symfony\Component\Form\Extension\Core\Type\EmailType instance.
     */
    protected function getForm_Type_EmailService()
    {
        return $this->services['form.type.email'] = new \Symfony\Component\Form\Extension\Core\Type\EmailType();
    }

    /**
     * Gets the 'form.type.field' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\FieldType A Symfony\Component\Form\Extension\Core\Type\FieldType instance.
     */
    protected function getForm_Type_FieldService()
    {
        return $this->services['form.type.field'] = new \Symfony\Component\Form\Extension\Core\Type\FieldType($this->get('validator'));
    }

    /**
     * Gets the 'form.type.file' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\FileType A Symfony\Component\Form\Extension\Core\Type\FileType instance.
     */
    protected function getForm_Type_FileService()
    {
        return $this->services['form.type.file'] = new \Symfony\Component\Form\Extension\Core\Type\FileType();
    }

    /**
     * Gets the 'form.type.form' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\FormType A Symfony\Component\Form\Extension\Core\Type\FormType instance.
     */
    protected function getForm_Type_FormService()
    {
        return $this->services['form.type.form'] = new \Symfony\Component\Form\Extension\Core\Type\FormType();
    }

    /**
     * Gets the 'form.type.hidden' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\HiddenType A Symfony\Component\Form\Extension\Core\Type\HiddenType instance.
     */
    protected function getForm_Type_HiddenService()
    {
        return $this->services['form.type.hidden'] = new \Symfony\Component\Form\Extension\Core\Type\HiddenType();
    }

    /**
     * Gets the 'form.type.integer' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\IntegerType A Symfony\Component\Form\Extension\Core\Type\IntegerType instance.
     */
    protected function getForm_Type_IntegerService()
    {
        return $this->services['form.type.integer'] = new \Symfony\Component\Form\Extension\Core\Type\IntegerType();
    }

    /**
     * Gets the 'form.type.language' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\LanguageType A Symfony\Component\Form\Extension\Core\Type\LanguageType instance.
     */
    protected function getForm_Type_LanguageService()
    {
        return $this->services['form.type.language'] = new \Symfony\Component\Form\Extension\Core\Type\LanguageType();
    }

    /**
     * Gets the 'form.type.locale' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\LocaleType A Symfony\Component\Form\Extension\Core\Type\LocaleType instance.
     */
    protected function getForm_Type_LocaleService()
    {
        return $this->services['form.type.locale'] = new \Symfony\Component\Form\Extension\Core\Type\LocaleType();
    }

    /**
     * Gets the 'form.type.money' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\MoneyType A Symfony\Component\Form\Extension\Core\Type\MoneyType instance.
     */
    protected function getForm_Type_MoneyService()
    {
        return $this->services['form.type.money'] = new \Symfony\Component\Form\Extension\Core\Type\MoneyType();
    }

    /**
     * Gets the 'form.type.number' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\NumberType A Symfony\Component\Form\Extension\Core\Type\NumberType instance.
     */
    protected function getForm_Type_NumberService()
    {
        return $this->services['form.type.number'] = new \Symfony\Component\Form\Extension\Core\Type\NumberType();
    }

    /**
     * Gets the 'form.type.password' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\PasswordType A Symfony\Component\Form\Extension\Core\Type\PasswordType instance.
     */
    protected function getForm_Type_PasswordService()
    {
        return $this->services['form.type.password'] = new \Symfony\Component\Form\Extension\Core\Type\PasswordType();
    }

    /**
     * Gets the 'form.type.percent' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\PercentType A Symfony\Component\Form\Extension\Core\Type\PercentType instance.
     */
    protected function getForm_Type_PercentService()
    {
        return $this->services['form.type.percent'] = new \Symfony\Component\Form\Extension\Core\Type\PercentType();
    }

    /**
     * Gets the 'form.type.radio' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\RadioType A Symfony\Component\Form\Extension\Core\Type\RadioType instance.
     */
    protected function getForm_Type_RadioService()
    {
        return $this->services['form.type.radio'] = new \Symfony\Component\Form\Extension\Core\Type\RadioType();
    }

    /**
     * Gets the 'form.type.repeated' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\RepeatedType A Symfony\Component\Form\Extension\Core\Type\RepeatedType instance.
     */
    protected function getForm_Type_RepeatedService()
    {
        return $this->services['form.type.repeated'] = new \Symfony\Component\Form\Extension\Core\Type\RepeatedType();
    }

    /**
     * Gets the 'form.type.search' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\SearchType A Symfony\Component\Form\Extension\Core\Type\SearchType instance.
     */
    protected function getForm_Type_SearchService()
    {
        return $this->services['form.type.search'] = new \Symfony\Component\Form\Extension\Core\Type\SearchType();
    }

    /**
     * Gets the 'form.type.text' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\TextType A Symfony\Component\Form\Extension\Core\Type\TextType instance.
     */
    protected function getForm_Type_TextService()
    {
        return $this->services['form.type.text'] = new \Symfony\Component\Form\Extension\Core\Type\TextType();
    }

    /**
     * Gets the 'form.type.textarea' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\TextareaType A Symfony\Component\Form\Extension\Core\Type\TextareaType instance.
     */
    protected function getForm_Type_TextareaService()
    {
        return $this->services['form.type.textarea'] = new \Symfony\Component\Form\Extension\Core\Type\TextareaType();
    }

    /**
     * Gets the 'form.type.time' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\TimeType A Symfony\Component\Form\Extension\Core\Type\TimeType instance.
     */
    protected function getForm_Type_TimeService()
    {
        return $this->services['form.type.time'] = new \Symfony\Component\Form\Extension\Core\Type\TimeType();
    }

    /**
     * Gets the 'form.type.timezone' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\TimezoneType A Symfony\Component\Form\Extension\Core\Type\TimezoneType instance.
     */
    protected function getForm_Type_TimezoneService()
    {
        return $this->services['form.type.timezone'] = new \Symfony\Component\Form\Extension\Core\Type\TimezoneType();
    }

    /**
     * Gets the 'form.type.url' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\UrlType A Symfony\Component\Form\Extension\Core\Type\UrlType instance.
     */
    protected function getForm_Type_UrlService()
    {
        return $this->services['form.type.url'] = new \Symfony\Component\Form\Extension\Core\Type\UrlType();
    }

    /**
     * Gets the 'form.type_extension.csrf' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Csrf\Type\FormTypeCsrfExtension A Symfony\Component\Form\Extension\Csrf\Type\FormTypeCsrfExtension instance.
     */
    protected function getForm_TypeExtension_CsrfService()
    {
        return $this->services['form.type_extension.csrf'] = new \Symfony\Component\Form\Extension\Csrf\Type\FormTypeCsrfExtension(true, '_token');
    }

    /**
     * Gets the 'form.type_extension.field' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Validator\Type\FieldTypeValidatorExtension A Symfony\Component\Form\Extension\Validator\Type\FieldTypeValidatorExtension instance.
     */
    protected function getForm_TypeExtension_FieldService()
    {
        return $this->services['form.type_extension.field'] = new \Symfony\Component\Form\Extension\Validator\Type\FieldTypeValidatorExtension($this->get('validator'));
    }

    /**
     * Gets the 'form.type_guesser.propel' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Propel\PropelBundle\Form\PropelTypeGuesser A Propel\PropelBundle\Form\PropelTypeGuesser instance.
     */
    protected function getForm_TypeGuesser_PropelService()
    {
        return $this->services['form.type_guesser.propel'] = new \Propel\PropelBundle\Form\PropelTypeGuesser();
    }

    /**
     * Gets the 'form.type_guesser.validator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser A Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser instance.
     */
    protected function getForm_TypeGuesser_ValidatorService()
    {
        return $this->services['form.type_guesser.validator'] = new \Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser($this->get('validator.mapping.class_metadata_factory'));
    }

    /**
     * Gets the 'http_kernel' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\HttpKernel A Symfony\Bundle\FrameworkBundle\HttpKernel instance.
     */
    protected function getHttpKernelService()
    {
        return $this->services['http_kernel'] = new \Symfony\Bundle\FrameworkBundle\HttpKernel($this->get('event_dispatcher'), $this, new \Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver($this, $this->get('controller_name_converter'), NULL));
    }

    /**
     * Gets the 'kernel' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @throws \RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getKernelService()
    {
        throw new \RuntimeException('You have requested a synthetic service ("kernel"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the 'propel.build_properties' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Propel\PropelBundle\DependencyInjection\Properties A Propel\PropelBundle\DependencyInjection\Properties instance.
     */
    protected function getPropel_BuildPropertiesService()
    {
        return $this->services['propel.build_properties'] = new \Propel\PropelBundle\DependencyInjection\Properties(array());
    }

    /**
     * Gets the 'propel.configuration' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return PropelConfiguration A PropelConfiguration instance.
     */
    protected function getPropel_ConfigurationService()
    {
        return $this->services['propel.configuration'] = new \PropelConfiguration(array('datasources' => array('default' => array('adapter' => 'mysql', 'connection' => array('dsn' => 'mysql:host=localhost;dbname=alphalemon_test', 'user' => 'root', 'password' => 'passera73', 'classname' => 'DebugPDO', 'options' => array(), 'attributes' => array(), 'settings' => array())))));
    }

    /**
     * Gets the 'propel.converter.propel.orm' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Propel\PropelBundle\Request\ParamConverter\PropelParamConverter A Propel\PropelBundle\Request\ParamConverter\PropelParamConverter instance.
     */
    protected function getPropel_Converter_Propel_OrmService()
    {
        return $this->services['propel.converter.propel.orm'] = new \Propel\PropelBundle\Request\ParamConverter\PropelParamConverter();
    }

    /**
     * Gets the 'propel.form.type.model' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Propel\PropelBundle\Form\Type\ModelType A Propel\PropelBundle\Form\Type\ModelType instance.
     */
    protected function getPropel_Form_Type_ModelService()
    {
        return $this->services['propel.form.type.model'] = new \Propel\PropelBundle\Form\Type\ModelType();
    }

    /**
     * Gets the 'propel.logger' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Propel\PropelBundle\Logger\PropelLogger A Propel\PropelBundle\Logger\PropelLogger instance.
     */
    protected function getPropel_LoggerService()
    {
        return $this->services['propel.logger'] = new \Propel\PropelBundle\Logger\PropelLogger(NULL);
    }

    /**
     * Gets the 'propel.twig.extension.syntax' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Propel\PropelBundle\Twig\Extension\SyntaxExtension A Propel\PropelBundle\Twig\Extension\SyntaxExtension instance.
     */
    protected function getPropel_Twig_Extension_SyntaxService()
    {
        return $this->services['propel.twig.extension.syntax'] = new \Propel\PropelBundle\Twig\Extension\SyntaxExtension();
    }

    /**
     * Gets the 'request' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @throws \RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getRequestService()
    {
        if (!isset($this->scopedServices['request'])) {
            throw new InactiveScopeException('request', 'request');
        }

        throw new \RuntimeException('You have requested a synthetic service ("request"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the 'response_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\EventListener\ResponseListener A Symfony\Component\HttpKernel\EventListener\ResponseListener instance.
     */
    protected function getResponseListenerService()
    {
        return $this->services['response_listener'] = new \Symfony\Component\HttpKernel\EventListener\ResponseListener('UTF-8');
    }

    /**
     * Gets the 'router' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Routing\Router A Symfony\Bundle\FrameworkBundle\Routing\Router instance.
     */
    protected function getRouterService()
    {
        return $this->services['router'] = new \Symfony\Bundle\FrameworkBundle\Routing\Router($this, '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/config/routing.yml', array('cache_dir' => '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/cache/test', 'debug' => true, 'generator_class' => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator', 'generator_base_class' => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator', 'generator_dumper_class' => 'Symfony\\Component\\Routing\\Generator\\Dumper\\PhpGeneratorDumper', 'generator_cache_class' => 'CmsTestKerneltestUrlGenerator', 'matcher_class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\RedirectableUrlMatcher', 'matcher_base_class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\RedirectableUrlMatcher', 'matcher_dumper_class' => 'Symfony\\Component\\Routing\\Matcher\\Dumper\\PhpMatcherDumper', 'matcher_cache_class' => 'CmsTestKerneltestUrlMatcher'));
    }

    /**
     * Gets the 'router_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\EventListener\RouterListener A Symfony\Bundle\FrameworkBundle\EventListener\RouterListener instance.
     */
    protected function getRouterListenerService()
    {
        return $this->services['router_listener'] = new \Symfony\Bundle\FrameworkBundle\EventListener\RouterListener($this->get('router'), 80, 443, NULL);
    }

    /**
     * Gets the 'routing.loader' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader A Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader instance.
     */
    protected function getRouting_LoaderService()
    {
        $a = $this->get('file_locator');

        $b = new \Symfony\Component\Config\Loader\LoaderResolver();
        $b->addLoader(new \Symfony\Component\Routing\Loader\XmlFileLoader($a));
        $b->addLoader(new \Symfony\Component\Routing\Loader\YamlFileLoader($a));
        $b->addLoader(new \Symfony\Component\Routing\Loader\PhpFileLoader($a));

        return $this->services['routing.loader'] = new \Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader($this->get('controller_name_converter'), NULL, $b);
    }

    /**
     * Gets the 'service_container' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @throws \RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getServiceContainerService()
    {
        throw new \RuntimeException('You have requested a synthetic service ("service_container"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the 'session' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpFoundation\Session A Symfony\Component\HttpFoundation\Session instance.
     */
    protected function getSessionService()
    {
        return $this->services['session'] = new \Symfony\Component\HttpFoundation\Session($this->get('session.storage'), 'en');
    }

    /**
     * Gets the 'session.storage' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpFoundation\SessionStorage\FilesystemSessionStorage A Symfony\Component\HttpFoundation\SessionStorage\FilesystemSessionStorage instance.
     */
    protected function getSession_StorageService()
    {
        return $this->services['session.storage'] = new \Symfony\Component\HttpFoundation\SessionStorage\FilesystemSessionStorage('/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/cache/test/sessions', array());
    }

    /**
     * Gets the 'session_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\EventListener\SessionListener A Symfony\Bundle\FrameworkBundle\EventListener\SessionListener instance.
     */
    protected function getSessionListenerService()
    {
        return $this->services['session_listener'] = new \Symfony\Bundle\FrameworkBundle\EventListener\SessionListener($this, false);
    }

    /**
     * Gets the 'templating' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\TwigBundle\TwigEngine A Symfony\Bundle\TwigBundle\TwigEngine instance.
     */
    protected function getTemplatingService()
    {
        return $this->services['templating'] = new \Symfony\Bundle\TwigBundle\TwigEngine($this->get('twig'), $this->get('templating.name_parser'), $this->get('templating.globals'));
    }

    /**
     * Gets the 'templating.asset.package_factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Asset\PackageFactory A Symfony\Bundle\FrameworkBundle\Templating\Asset\PackageFactory instance.
     */
    protected function getTemplating_Asset_PackageFactoryService()
    {
        return $this->services['templating.asset.package_factory'] = new \Symfony\Bundle\FrameworkBundle\Templating\Asset\PackageFactory($this);
    }

    /**
     * Gets the 'templating.globals' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables A Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables instance.
     */
    protected function getTemplating_GlobalsService()
    {
        return $this->services['templating.globals'] = new \Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables($this);
    }

    /**
     * Gets the 'templating.helper.actions' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\ActionsHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\ActionsHelper instance.
     */
    protected function getTemplating_Helper_ActionsService()
    {
        return $this->services['templating.helper.actions'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\ActionsHelper($this->get('http_kernel'));
    }

    /**
     * Gets the 'templating.helper.assets' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Templating\Helper\CoreAssetsHelper A Symfony\Component\Templating\Helper\CoreAssetsHelper instance.
     */
    protected function getTemplating_Helper_AssetsService()
    {
        if (!isset($this->scopedServices['request'])) {
            throw new InactiveScopeException('templating.helper.assets', 'request');
        }

        return $this->services['templating.helper.assets'] = $this->scopedServices['request']['templating.helper.assets'] = new \Symfony\Component\Templating\Helper\CoreAssetsHelper(new \Symfony\Bundle\FrameworkBundle\Templating\Asset\PathPackage($this->get('request'), NULL, NULL), array());
    }

    /**
     * Gets the 'templating.helper.code' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\CodeHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\CodeHelper instance.
     */
    protected function getTemplating_Helper_CodeService()
    {
        return $this->services['templating.helper.code'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\CodeHelper(NULL, '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel', 'UTF-8');
    }

    /**
     * Gets the 'templating.helper.form' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper instance.
     */
    protected function getTemplating_Helper_FormService()
    {
        $a = new \Symfony\Bundle\FrameworkBundle\Templating\PhpEngine($this->get('templating.name_parser'), $this, $this->get('templating.loader'), $this->get('templating.globals'));
        $a->setCharset('UTF-8');
        $a->setHelpers(array('slots' => 'templating.helper.slots', 'assets' => 'templating.helper.assets', 'request' => 'templating.helper.request', 'session' => 'templating.helper.session', 'router' => 'templating.helper.router', 'actions' => 'templating.helper.actions', 'code' => 'templating.helper.code', 'translator' => 'templating.helper.translator', 'form' => 'templating.helper.form'));

        return $this->services['templating.helper.form'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper($a, array(0 => 'FrameworkBundle:Form'));
    }

    /**
     * Gets the 'templating.helper.request' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\RequestHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\RequestHelper instance.
     */
    protected function getTemplating_Helper_RequestService()
    {
        return $this->services['templating.helper.request'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\RequestHelper($this->get('request'));
    }

    /**
     * Gets the 'templating.helper.router' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper instance.
     */
    protected function getTemplating_Helper_RouterService()
    {
        return $this->services['templating.helper.router'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper($this->get('router'));
    }

    /**
     * Gets the 'templating.helper.session' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\SessionHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\SessionHelper instance.
     */
    protected function getTemplating_Helper_SessionService()
    {
        return $this->services['templating.helper.session'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\SessionHelper($this->get('request'));
    }

    /**
     * Gets the 'templating.helper.slots' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Templating\Helper\SlotsHelper A Symfony\Component\Templating\Helper\SlotsHelper instance.
     */
    protected function getTemplating_Helper_SlotsService()
    {
        return $this->services['templating.helper.slots'] = new \Symfony\Component\Templating\Helper\SlotsHelper();
    }

    /**
     * Gets the 'templating.helper.translator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\TranslatorHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\TranslatorHelper instance.
     */
    protected function getTemplating_Helper_TranslatorService()
    {
        return $this->services['templating.helper.translator'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\TranslatorHelper($this->get('translator'));
    }

    /**
     * Gets the 'templating.loader' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Loader\FilesystemLoader A Symfony\Bundle\FrameworkBundle\Templating\Loader\FilesystemLoader instance.
     */
    protected function getTemplating_LoaderService()
    {
        return $this->services['templating.loader'] = new \Symfony\Bundle\FrameworkBundle\Templating\Loader\FilesystemLoader($this->get('templating.locator'));
    }

    /**
     * Gets the 'templating.name_parser' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\TemplateNameParser A Symfony\Bundle\FrameworkBundle\Templating\TemplateNameParser instance.
     */
    protected function getTemplating_NameParserService()
    {
        return $this->services['templating.name_parser'] = new \Symfony\Bundle\FrameworkBundle\Templating\TemplateNameParser($this->get('kernel'));
    }

    /**
     * Gets the 'test.client' service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Client A Symfony\Bundle\FrameworkBundle\Client instance.
     */
    protected function getTest_ClientService()
    {
        return new \Symfony\Bundle\FrameworkBundle\Client($this->get('kernel'), array(), new \Symfony\Component\BrowserKit\History(), new \Symfony\Component\BrowserKit\CookieJar());
    }

    /**
     * Gets the 'test.client.cookiejar' service.
     *
     * @return Symfony\Component\BrowserKit\CookieJar A Symfony\Component\BrowserKit\CookieJar instance.
     */
    protected function getTest_Client_CookiejarService()
    {
        return new \Symfony\Component\BrowserKit\CookieJar();
    }

    /**
     * Gets the 'test.client.history' service.
     *
     * @return Symfony\Component\BrowserKit\History A Symfony\Component\BrowserKit\History instance.
     */
    protected function getTest_Client_HistoryService()
    {
        return new \Symfony\Component\BrowserKit\History();
    }

    /**
     * Gets the 'test.session.listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\EventListener\TestSessionListener A Symfony\Bundle\FrameworkBundle\EventListener\TestSessionListener instance.
     */
    protected function getTest_Session_ListenerService()
    {
        return $this->services['test.session.listener'] = new \Symfony\Bundle\FrameworkBundle\EventListener\TestSessionListener($this);
    }

    /**
     * Gets the 'translation.loader.php' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Loader\PhpFileLoader A Symfony\Component\Translation\Loader\PhpFileLoader instance.
     */
    protected function getTranslation_Loader_PhpService()
    {
        return $this->services['translation.loader.php'] = new \Symfony\Component\Translation\Loader\PhpFileLoader();
    }

    /**
     * Gets the 'translation.loader.xliff' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Loader\XliffFileLoader A Symfony\Component\Translation\Loader\XliffFileLoader instance.
     */
    protected function getTranslation_Loader_XliffService()
    {
        return $this->services['translation.loader.xliff'] = new \Symfony\Component\Translation\Loader\XliffFileLoader();
    }

    /**
     * Gets the 'translation.loader.yml' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Loader\YamlFileLoader A Symfony\Component\Translation\Loader\YamlFileLoader instance.
     */
    protected function getTranslation_Loader_YmlService()
    {
        return $this->services['translation.loader.yml'] = new \Symfony\Component\Translation\Loader\YamlFileLoader();
    }

    /**
     * Gets the 'translator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\IdentityTranslator A Symfony\Component\Translation\IdentityTranslator instance.
     */
    protected function getTranslatorService()
    {
        return $this->services['translator'] = new \Symfony\Component\Translation\IdentityTranslator($this->get('translator.selector'));
    }

    /**
     * Gets the 'translator.default' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Translation\Translator A Symfony\Bundle\FrameworkBundle\Translation\Translator instance.
     */
    protected function getTranslator_DefaultService()
    {
        return $this->services['translator.default'] = new \Symfony\Bundle\FrameworkBundle\Translation\Translator($this, $this->get('translator.selector'), array('translation.loader.php' => 'php', 'translation.loader.yml' => 'yml', 'translation.loader.xliff' => 'xliff'), array('cache_dir' => '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/cache/test/translations', 'debug' => true), $this->get('session'));
    }

    /**
     * Gets the 'twig' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Twig_Environment A Twig_Environment instance.
     */
    protected function getTwigService()
    {
        $this->services['twig'] = $instance = new \Twig_Environment($this->get('twig.loader'), array('debug' => true, 'strict_variables' => true, 'exception_controller' => 'Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction', 'cache' => '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/cache/test/twig', 'charset' => 'UTF-8'));

        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\TranslationExtension($this->get('translator')));
        $instance->addExtension(new \Symfony\Bundle\TwigBundle\Extension\AssetsExtension($this));
        $instance->addExtension(new \Symfony\Bundle\TwigBundle\Extension\ActionsExtension($this));
        $instance->addExtension(new \Symfony\Bundle\TwigBundle\Extension\CodeExtension($this));
        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\RoutingExtension($this->get('router')));
        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\YamlExtension());
        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\FormExtension(array(0 => 'form_div_layout.html.twig')));
        $instance->addExtension($this->get('propel.twig.extension.syntax'));
        $instance->addExtension(new \AlphaLemon\ThemeEngineBundle\Twig\RenderSlotExtension($this, $this->get('al_page_tree')));
        $instance->addExtension(new \AlphaLemon\AlphaLemonCmsBundle\Twig\StringsExtension());

        return $instance;
    }

    /**
     * Gets the 'twig.exception_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\EventListener\ExceptionListener A Symfony\Component\HttpKernel\EventListener\ExceptionListener instance.
     */
    protected function getTwig_ExceptionListenerService()
    {
        return $this->services['twig.exception_listener'] = new \Symfony\Component\HttpKernel\EventListener\ExceptionListener('Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction', NULL);
    }

    /**
     * Gets the 'twig.loader' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\TwigBundle\Loader\FilesystemLoader A Symfony\Bundle\TwigBundle\Loader\FilesystemLoader instance.
     */
    protected function getTwig_LoaderService()
    {
        $this->services['twig.loader'] = $instance = new \Symfony\Bundle\TwigBundle\Loader\FilesystemLoader($this->get('templating.locator'), $this->get('templating.name_parser'));

        $instance->addPath('/home/giansimon/www/alphaLemon/vendor/symfony/src/Symfony/Bundle/TwigBundle/DependencyInjection/../../../Bridge/Twig/Resources/views/Form');

        return $instance;
    }

    /**
     * Gets the 'validator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Validator\Validator A Symfony\Component\Validator\Validator instance.
     */
    protected function getValidatorService()
    {
        return $this->services['validator'] = new \Symfony\Component\Validator\Validator($this->get('validator.mapping.class_metadata_factory'), new \Symfony\Bundle\FrameworkBundle\Validator\ConstraintValidatorFactory($this, array()), array());
    }

    /**
     * Gets the debug.event_dispatcher service alias.
     *
     * @return Symfony\Bundle\FrameworkBundle\Debug\TraceableEventDispatcher An instance of the event_dispatcher service
     */
    protected function getDebug_EventDispatcherService()
    {
        return $this->get('event_dispatcher');
    }

    /**
     * Gets the 'controller_name_converter' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser A Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser instance.
     */
    protected function getControllerNameConverterService()
    {
        return $this->services['controller_name_converter'] = new \Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser($this->get('kernel'));
    }

    /**
     * Gets the 'templating.locator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator A Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator instance.
     */
    protected function getTemplating_LocatorService()
    {
        return $this->services['templating.locator'] = new \Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator($this->get('file_locator'), '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/cache/test');
    }

    /**
     * Gets the 'translator.selector' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Component\Translation\MessageSelector A Symfony\Component\Translation\MessageSelector instance.
     */
    protected function getTranslator_SelectorService()
    {
        return $this->services['translator.selector'] = new \Symfony\Component\Translation\MessageSelector();
    }

    /**
     * Gets the 'validator.mapping.class_metadata_factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Component\Validator\Mapping\ClassMetadataFactory A Symfony\Component\Validator\Mapping\ClassMetadataFactory instance.
     */
    protected function getValidator_Mapping_ClassMetadataFactoryService()
    {
        return $this->services['validator.mapping.class_metadata_factory'] = new \Symfony\Component\Validator\Mapping\ClassMetadataFactory(new \Symfony\Component\Validator\Mapping\Loader\LoaderChain(array(0 => new \Symfony\Component\Validator\Mapping\Loader\AnnotationLoader($this->get('annotation_reader')), 1 => new \Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader(), 2 => new \Symfony\Component\Validator\Mapping\Loader\XmlFilesLoader(array(0 => '/home/giansimon/www/alphaLemon/vendor/symfony/src/Symfony/Bundle/FrameworkBundle/DependencyInjection/../../../Component/Form/Resources/config/validation.xml')), 3 => new \Symfony\Component\Validator\Mapping\Loader\YamlFilesLoader(array()))), NULL);
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name)
    {
        $name = strtolower($name);

        if (!array_key_exists($name, $this->parameters)) {
            throw new \InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
        }

        return $this->parameters[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter($name)
    {
        return array_key_exists(strtolower($name), $this->parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($name, $value)
    {
        throw new \LogicException('Impossible to call set() on a frozen ParameterBag.');
    }

    /**
     * {@inheritDoc}
     */
    public function getParameterBag()
    {
        if (null === $this->parameterBag) {
            $this->parameterBag = new FrozenParameterBag($this->parameters);
        }

        return $this->parameterBag;
    }
    /**
     * Gets the default parameters.
     *
     * @return array An array of the default parameters
     */
    protected function getDefaultParameters()
    {
        return array(
            'kernel.root_dir' => '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel',
            'kernel.environment' => 'test',
            'kernel.debug' => true,
            'kernel.name' => 'CmsTestKernel',
            'kernel.cache_dir' => '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/cache/test',
            'kernel.logs_dir' => '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/logs',
            'kernel.bundles' => array(
                'FrameworkBundle' => 'Symfony\\Bundle\\FrameworkBundle\\FrameworkBundle',
                'SecurityBundle' => 'Symfony\\Bundle\\SecurityBundle\\SecurityBundle',
                'TwigBundle' => 'Symfony\\Bundle\\TwigBundle\\TwigBundle',
                'PropelBundle' => 'Propel\\PropelBundle\\PropelBundle',
                'AlphaLemonFrontendBundle' => 'AlphaLemon\\FrontendBundle\\AlphaLemonFrontendBundle',
                'AlphaLemonPageTreeBundle' => 'AlphaLemon\\PageTreeBundle\\AlphaLemonPageTreeBundle',
                'AlphaLemonThemeEngineBundle' => 'AlphaLemon\\ThemeEngineBundle\\AlphaLemonThemeEngineBundle',
                'AlphaLemonCmsBundle' => 'AlphaLemon\\AlphaLemonCmsBundle\\AlphaLemonCmsBundle',
                'AlValumUploaderBundle' => 'AlphaLemon\\AlValumUploaderBundle\\AlValumUploaderBundle',
                'AlphaLemonElFinderBundle' => 'AlphaLemon\\ElFinderBundle\\AlphaLemonElFinderBundle',
                'AlphaLemonThemeBundle' => 'Themes\\AlphaLemonThemeBundle\\AlphaLemonThemeBundle',
                'AlMediaBundle' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\Bundles\\AlMediaBundle\\AlMediaBundle',
                'AlScriptBundle' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\Bundles\\AlScriptBundle\\AlScriptBundle',
                'AlTextBundle' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\Bundles\\AlTextBundle\\AlTextBundle',
                'AlMenuBundle' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\Bundles\\AlMenuBundle\\AlMenuBundle',
            ),
            'kernel.charset' => 'UTF-8',
            'kernel.container_class' => 'CmsTestKernelTestDebugProjectContainer',
            'router_listener.class' => 'Symfony\\Bundle\\FrameworkBundle\\EventListener\\RouterListener',
            'controller_resolver.class' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\ControllerResolver',
            'controller_name_converter.class' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\ControllerNameParser',
            'response_listener.class' => 'Symfony\\Component\\HttpKernel\\EventListener\\ResponseListener',
            'event_dispatcher.class' => 'Symfony\\Bundle\\FrameworkBundle\\ContainerAwareEventDispatcher',
            'http_kernel.class' => 'Symfony\\Bundle\\FrameworkBundle\\HttpKernel',
            'filesystem.class' => 'Symfony\\Component\\Filesystem\\Filesystem',
            'cache_warmer.class' => 'Symfony\\Component\\HttpKernel\\CacheWarmer\\CacheWarmerAggregate',
            'file_locator.class' => 'Symfony\\Component\\HttpKernel\\Config\\FileLocator',
            'translator.class' => 'Symfony\\Bundle\\FrameworkBundle\\Translation\\Translator',
            'translator.identity.class' => 'Symfony\\Component\\Translation\\IdentityTranslator',
            'translator.selector.class' => 'Symfony\\Component\\Translation\\MessageSelector',
            'translation.loader.php.class' => 'Symfony\\Component\\Translation\\Loader\\PhpFileLoader',
            'translation.loader.yml.class' => 'Symfony\\Component\\Translation\\Loader\\YamlFileLoader',
            'translation.loader.xliff.class' => 'Symfony\\Component\\Translation\\Loader\\XliffFileLoader',
            'debug.event_dispatcher.class' => 'Symfony\\Bundle\\FrameworkBundle\\Debug\\TraceableEventDispatcher',
            'debug.container.dump' => '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/cache/test/CmsTestKernelTestDebugProjectContainer.xml',
            'kernel.secret' => 'secret',
            'kernel.trust_proxy_headers' => false,
            'test.client.class' => 'Symfony\\Bundle\\FrameworkBundle\\Client',
            'test.client.parameters' => array(

            ),
            'test.client.history.class' => 'Symfony\\Component\\BrowserKit\\History',
            'test.client.cookiejar.class' => 'Symfony\\Component\\BrowserKit\\CookieJar',
            'test.session.listener.class' => 'Symfony\\Bundle\\FrameworkBundle\\EventListener\\TestSessionListener',
            'session.class' => 'Symfony\\Component\\HttpFoundation\\Session',
            'session.storage.native.class' => 'Symfony\\Component\\HttpFoundation\\SessionStorage\\NativeSessionStorage',
            'session.storage.filesystem.class' => 'Symfony\\Component\\HttpFoundation\\SessionStorage\\FilesystemSessionStorage',
            'session_listener.class' => 'Symfony\\Bundle\\FrameworkBundle\\EventListener\\SessionListener',
            'session.default_locale' => 'en',
            'session.storage.options' => array(

            ),
            'form.extension.class' => 'Symfony\\Component\\Form\\Extension\\DependencyInjection\\DependencyInjectionExtension',
            'form.factory.class' => 'Symfony\\Component\\Form\\FormFactory',
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
                0 => '/home/giansimon/www/alphaLemon/vendor/symfony/src/Symfony/Bundle/FrameworkBundle/DependencyInjection/../../../Component/Form/Resources/config/validation.xml',
            ),
            'validator.mapping.loader.yaml_files_loader.mapping_files' => array(

            ),
            'router.class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\Router',
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
            'router.options.matcher.cache_class' => 'CmsTestKernel%kernel.environment%UrlMatcher',
            'router.options.generator.cache_class' => 'CmsTestKernel%kernel.environment%UrlGenerator',
            'router.resource' => '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/config/routing.yml',
            'request_listener.http_port' => 80,
            'request_listener.https_port' => 443,
            'templating.engine.delegating.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\DelegatingEngine',
            'templating.name_parser.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\TemplateNameParser',
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
            'templating.globals.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\GlobalVariables',
            'templating.asset.path_package.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Asset\\PathPackage',
            'templating.asset.url_package.class' => 'Symfony\\Component\\Templating\\Asset\\UrlPackage',
            'templating.asset.package_factory.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Asset\\PackageFactory',
            'templating.helper.code.file_link_format' => NULL,
            'templating.helper.form.resources' => array(
                0 => 'FrameworkBundle:Form',
            ),
            'templating.debugger.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Debugger',
            'templating.loader.cache.path' => NULL,
            'templating.engines' => array(
                0 => 'twig',
            ),
            'annotations.reader.class' => 'Doctrine\\Common\\Annotations\\AnnotationReader',
            'annotations.cached_reader.class' => 'Doctrine\\Common\\Annotations\\CachedReader',
            'annotations.file_cache_reader.class' => 'Doctrine\\Common\\Annotations\\FileCacheReader',
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
            'twig.exception_listener.class' => 'Symfony\\Component\\HttpKernel\\EventListener\\ExceptionListener',
            'twig.exception_listener.controller' => 'Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction',
            'twig.form.resources' => array(
                0 => 'form_div_layout.html.twig',
            ),
            'twig.options' => array(
                'debug' => true,
                'strict_variables' => true,
                'exception_controller' => 'Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction',
                'cache' => '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/cache/test/twig',
                'charset' => 'UTF-8',
            ),
            'propel.path' => '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/../../vendor/propel',
            'propel.phing_path' => '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/../../vendor/phing',
            'propel.logging' => true,
            'propel.dbal.default_connection' => 'default',
            'propel.configuration.class' => 'PropelConfiguration',
            'propel.logger.class' => 'Propel\\PropelBundle\\Logger\\PropelLogger',
            'propel.data_collector.class' => 'Propel\\PropelBundle\\DataCollector\\PropelDataCollector',
            'propel.build_properties.class' => 'Propel\\PropelBundle\\DependencyInjection\\Properties',
            'propel.form.type.model.class' => 'Propel\\PropelBundle\\Form\\Type\\ModelType',
            'propel.twig.extension.syntax.class' => 'Propel\\PropelBundle\\Twig\\Extension\\SyntaxExtension',
            'form.type_guesser.propel.class' => 'Propel\\PropelBundle\\Form\\PropelTypeGuesser',
            'propel.converter.propel.class' => 'Propel\\PropelBundle\\Request\\ParamConverter\\PropelParamConverter',
            'al.deploy_bundle_assets_base_dir' => 'Resources/public',
            'al.deploy_bundle_media_folder' => 'media',
            'al.deploy_bundle_js_folder' => 'js',
            'al.deploy_bundle_css_folder' => 'css',
            'al_frontend_request_listener.class' => 'AlphaLemon\\FrontendBundle\\Core\\Listener\\AlFrontendRequestListener',
            'al.deploy_bundle' => 'AlphaLemonWebSiteBundle',
            'alpalemon.page_tree' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\PageTree\\AlPageTree',
            'twig.extension.render_slot.class' => 'AlphaLemon\\ThemeEngineBundle\\Twig\\RenderSlotExtension',
            'althemes.stylesheets' => array(
                0 => 'css/al_themes.css',
            ),
            'althemes.base_template' => 'AlphaLemonThemeEngineBundle:Theme:base.html.twig',
            'althemes.base_theme_manager_template' => 'AlphaLemonThemeEngineBundle:Themes:index.html.twig',
            'althemes.panel_sections_template' => 'AlphaLemonThemeEngineBundle:Themes:theme_panel_sections.html.twig',
            'althemes.theme_skeleton_template' => 'AlphaLemonThemeEngineBundle:Themes:theme_skeleton.html.twig',
            'althemes.base_dir' => 'Themes',
            'althemes.info_valid_entries' => array(
                0 => 'title',
                1 => 'description',
                2 => 'author',
                3 => 'license',
                4 => 'website',
                5 => 'email',
                6 => 'version',
            ),
            'alcms.skin' => 'alphaLemon',
            'alcms.stylesheets' => array(
                0 => '@AlphaLemonCmsBundle/Resources/public/css/skins/alphaLemon/*',
                1 => '@AlphaLemonCmsBundle/Resources/public/css/skins/alphaLemon/vendor/smoothness/*',
                2 => '@AlphaLemonCmsBundle/Resources/public/css/skins/alphaLemon/vendor/medialize/*',
                3 => '@AlphaLemonThemeEngineBundle/Resources/public/css/*',
            ),
            'alcms.javascripts' => array(
                0 => '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/jquery/*',
                1 => '@AlphaLemonThemeEngineBundle/Resources/public/js/vendor/*',
                2 => '@AlphaLemonCmsBundle/Resources/public/js/vendor/medialize/*',
                3 => '@AlphaLemonCmsBundle/Resources/public/js/*',
            ),
            'alcms.available_languages' => array(
                'en' => 'English',
            ),
            'al_cms.page_blocks' => array(
                'Text' => 'Text Content',
                'Media' => 'Media Content',
                'Menu' => 'Simple Menu Content',
                'Script' => 'Javascript Content',
            ),
            'alcms.deploy.xliff_skeleton' => '@AlphaLemonCmsBundle/Resources/data/xml/xliff-skeleton.xml',
            'alcms.deploy.xml_skeleton' => '@AlphaLemonCmsBundle/Resources/data/xml/page-skeleton.xml',
            'alcms.assets.skeletons_folder' => '@AlphaLemonCmsBundle/Resources/data/assets',
            'alcms.assets.output_folder' => 'Resources/views/Assets',
            'alcms.upload_assets_dir' => 'uploads/assets',
            'al_cms_setup_listener.class' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\Listener\\AlCmsSetupListener',
            'al_cms_finalize_content_listener.class' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\Listener\\AlFinalizeContentsListener',
            'el_finder.media_connector' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\ElFinder\\ElFinderMediaConnector',
            'el_finder.css_connector' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\ElFinder\\ElFinderStylesheetsConnector',
            'el_finder.js_connector' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\ElFinder\\ElFinderJavascriptsConnector',
            'al_cms_pages_manager' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\Content\\Page\\AlPageManager',
            'al_cms_page_attributes_manager' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\Content\\PageAttributes\\AlPageAttributesManager',
            'al_cms_languages_manager' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\Content\\Language\\AlLanguageManager',
            'alpalemon.assets_builder' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\AssetsBuilder\\AlAssetsBuilder',
            'twig.extension.strings.class' => 'AlphaLemon\\AlphaLemonCmsBundle\\Twig\\StringsExtension',
            'valum.panel_title' => 'Files Uploader',
            'valum.panel_info' => 'To upload a file, click on the button below or drag your files over the button itself. Drag-and-drop is supported in Firefox and Chrome.',
            'valum.id' => 'al_valum_uploader',
            'valum.upload_action' => 'al_uploadFile',
            'valum.allowed_extensions' => '\'jpg\',\'jpeg\',\'png\',\'gif\',\'bmp\'',
            'valum.size_limit' => 2500000,
            'valum.min_size_limit' => 0,
            'valum.params' => array(
                'folder' => '',
            ),
            'valum.onsubmit' => '',
            'valum.onprogress' => '',
            'valum.oncomplete' => '',
            'valum.oncancel' => '',
            'valum.base_folder' => '/home/giansimon/www/alphaLemon/vendor/bundles/AlphaLemon/AlphaLemonCmsBundle/Tests/CmsTestKernel/../',
            'el_finder.connector' => 'AlphaLemon\\ElFinderBundle\\Core\\Connector\\AlphaLemonElFinderConnector',
            'themes.alphalemontheme_home.stylesheets' => array(
                0 => '@AlphaLemonThemeBundle/Resources/public/css/reset.css',
                1 => '@AlphaLemonThemeBundle/Resources/public/css/960.css',
                2 => '@AlphaLemonThemeBundle/Resources/public/css/text.css',
                3 => '@AlphaLemonThemeBundle/Resources/public/css/screen.css',
            ),
            'al_media_editor_renderer.class' => 'AlphaLemon\\AlphaLemonCmsBundle\\Core\\Bundles\\AlMediaBundle\\Core\\Listener\\RenderedEditorListener',
            'al_media_type' => array(
                'image/jpeg' => 'Image',
                'image/png' => 'Image',
                'image/gif' => 'Image',
                'image/bmp' => 'Image',
                'application/x-shockwave-flash' => 'Flash',
            ),
            'al_script_editor_settings' => array(
                'html_editor' => true,
                'internal_js' => true,
                'external_js' => true,
                'external_css' => true,
                'internal_css' => true,
            ),
            'al_text_editor_settings' => array(
                'rich_editor' => true,
            ),
            'al_menu_editor_settings' => array(
                'rich_editor' => true,
            ),
        );
    }
}
