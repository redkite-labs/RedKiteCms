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

namespace RedKiteCms\Rendering\Controller\Cms;

use RedKiteCms\FilesystemEntity\Page;
use RedKiteCms\Rendering\Controller\BaseController;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FrontendController is the object deputed to implement the action to render the CMS frontend
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Cms
 */
abstract class FrontendController extends BaseController
{
    /**
     * @type \RedKiteCms\Configuration\ConfigurationHandler
     */
    protected $configuration;

    /**
     * Implements the action to render the CMS frontend
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);

        $this->configuration = $this->options["red_kite_cms_config"];
        $this->options["template_assets"]->boot('prod');
        $page = $this->renderPage();
        $template = $this->fetchTemplate($page);
        $params = $this->configureRendererOptions($page);

        return $options["twig"]->render($template, $params);
    }

    /**
     * Configures the options for the resolver
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            array(
                'username',
                'root_dir',
                'request',
                'twig',
                'red_kite_cms_config',
                'plugin_manager',
                'page',
                'template_assets',
                'page_renderer',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'username' => array(
                    'string',
                    'null'
                ),
                'root_dir' => 'string',
                'request' => '\Symfony\Component\HttpFoundation\Request',
                'twig' => '\Twig_Environment',
                'red_kite_cms_config' => '\RedKiteCms\Configuration\ConfigurationHandler',
                'plugin_manager' => '\RedKiteCms\Plugin\PluginManager',
                'page' => '\RedKiteCms\FilesystemEntity\Page',
                'template_assets' => '\RedKiteCms\Rendering\TemplateAssetsManager\TemplateAssetsManager'
            )
        );
    }

    /**
     * Renders the page
     *
     * @return \RedKiteCms\FilesystemEntity\Page
     */
    protected function renderPage()
    {
        $page = $this->options["page"];
        $request = $this->options["request"];
        $username = $this->options["username"];
        $pageOptions = array(
            'page' => $request->get('page'),
            'language' => $request->get('_locale'),
            'country' => $request->get('country'),
        );
        $page->render($this->configuration->siteDir(), $pageOptions, $username);

        return $page;
    }

    /**
     * Returns the template to render
     * @param \RedKiteCms\Rendering\Controller\Cms\Page $page
     *
     * @return string
     */
    protected function fetchTemplate(Page $page)
    {
        $themeName = $this->configuration->theme();
        $pageAttributes = $page->getPageAttributes();
        $pageName = $pageAttributes["template"];

        return sprintf('%s/Resources/views/%s.html.twig', $themeName, $pageName);
    }

    /**
     * Configures the backend options
     * @param \RedKiteCms\FilesystemEntity\Page $page
     *
     * @return array
     */
    protected function configureRendererOptions(Page $page)
    {
        $slots = $this->renderSlots($page);
        $baseTemplate = $this->configuration->baseTemplate();
        $templateAssetsManager = $this->initTemplateAssetsManager();
        $seo = $page->getSeoAttributes();

        return array_merge(
            array(
                'page' => $page->getPageName(),
                'language' => $page->getLanguage(),
                'country' => $page->getCountry(),
                'metatitle' => $seo["title"],
                'metadescription' => $seo["description"],
                'metakeywords' => $seo["keywords"],
                'base_template' => $baseTemplate,
                'template_assets_manager' => $templateAssetsManager,
            ),
            $slots
        );
    }

    protected function renderSlots(Page $page)
    {
        $slots = $this->options['page_renderer']->renderSlotsFromPage($page);

        return array(
            "slots" => $slots,
        );
    }

    protected function initTemplateAssetsManager()
    {
        return $this->options["template_assets"];
        /*
        $pluginManager = $this->options["plugin_manager"];
        $templateAssetsManager = $this->options["template_assets"];
        $templateAssetsManager->add($pluginManager->getAssets());

        return $templateAssetsManager;*/
    }
}