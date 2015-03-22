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

use Controller\Cms\FrontendController;
use RedKiteCms\Core\RedKiteCms\Core\Form\PageCollection\SeoType;
use RedKiteCms\FilesystemEntity\Page;
use RedKiteCms\Rendering\TemplateAssetsManager\TemplateAssetsManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BackendController is the object deputed to implement the action to render the CMS backend
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Cms
 */
abstract class BackendController extends FrontendController
{
    /**
     * Implements the action to render the CMS backend
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);

        $request = $this->options["request"];
        $request->getSession()->set('last_route', $request->get('_route'));

        $this->configuration = $this->options["red_kite_cms_config"];
        $page = $this->renderPage();
        $params = $this->configureRendererOptions($page);

        $formFactory = $this->options['form_factory'];
        $seoForm = $formFactory->create(new SeoType());
        $params["seo_form"] = $seoForm->createView();
        $params["editor_toolbar"] = $this->options["toolbar_manager"]->render();

        return $options["twig"]->render('RedKiteCms/Resources/views/Template/Cms/template.html.twig', $params);
    }

    /**
     * Configures the options for the resolver
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            array(
                'skin',
                'languages',
                'block_factory',
                'form_factory',
                'serializer',
                'toolbar_manager',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'skin' => 'string',
                'languages' => 'array',
                'block_factory' => '\RedKiteCms\Content\Block\BlockFactory',
                'form_factory' => '\Symfony\Component\Form\FormFactory',
                'serializer' => '\JMS\Serializer\Serializer',
                'toolbar_manager' => 'RedKiteCms\Rendering\Toolbar\ToolbarManager',
            )
        );

        parent::configureOptions($resolver);
    }

    /**
     * Configures the backend options
     * @param \RedKiteCms\FilesystemEntity\Page $page
     *
     * @return array
     */
    protected function configureRendererOptions(Page $page)
    {
        $params = parent::configureRendererOptions($page);

        $params["commands"] = $this->configuration->commands();
        $params["template"] = $this->fetchTemplate($page);
        $params["is_theme"] = $this->configuration->isTheme() ? 'on' : 'off';

        $request = $this->options["request"];
        $seoDir = $this->configuration->pagesDir() . '/' . $request->get('page') . '/' . $request->get(
                '_locale'
            ) . '_' . $request->get('country');
        $seoFileName = $this->options["username"];
        if (null === $seoFileName) {
            $seoFileName = "seo";
        }
        $seoFile = sprintf('%s/%s.json', $seoDir, $seoFileName);
        $params["seo_data"] = file_get_contents($seoFile);
        $publishedSeoFile = sprintf('%s/seo.json', $seoDir);
        $params["page_published"] = file_exists($publishedSeoFile) ? "1" : "0";

        return $params;
    }

    /**
     * Returns the template to render
     * @param \RedKiteCms\FilesystemEntity\Page $page
     *
     * @return string
     */
    protected function fetchTemplate(Page $page)
    {
        $pageAttributes = $page->getPageAttributes();

        return sprintf('%s/Resources/views/%s.html.twig', $this->configuration->theme(), $pageAttributes["template"]);
    }

    /**
     * Renders the slots
     * @param \RedKiteCms\FilesystemEntity\Page $page
     *
     * @return array
     */
    protected function renderSlots(Page $page)
    {
        $blockFactory = $this->options["block_factory"];
        $templateRenderer = $this->options['page_renderer'];
        $availableBlocks = $blockFactory->getAvailableBlocks();

        // We need to render all blocks to avoid problems when a kind ok block is
        // not present on a page
        $blocks = $blockFactory->createAllBlocks();
        $slots = $templateRenderer->renderSlotsFromPage(
            $page,
            array(
                'available_blocks' => $availableBlocks,
            )
        );

        $cmsBlocks = $templateRenderer->renderCmsBlocks(
            $blocks,
            $this->options["username"],
            array(
                'available_blocks' => $availableBlocks,
            )
        );

        return array(
            'slots' => $slots,
            'cms_blocks' => $cmsBlocks,
            'available_blocks' => $availableBlocks,
        );
    }

    /**
     * Initializes the template assets manager object
     *
     * @return TemplateAssetsManager
     */
    protected function initTemplateAssetsManager()
    {
        $templateAssetsManager = parent::initTemplateAssetsManager();

        $pluginManager = $this->options["plugin_manager"];
        $templateAssetsManager->add($pluginManager->getAssets());

        return $templateAssetsManager;
    }

}