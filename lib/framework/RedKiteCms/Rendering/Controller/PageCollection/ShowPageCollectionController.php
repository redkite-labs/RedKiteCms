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
namespace RedKiteCms\Rendering\Controller\PageCollection;

use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Core\RedKiteCms\Core\Form\PageCollection\PageType;
use RedKiteCms\Core\RedKiteCms\Core\Form\PageCollection\SeoType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ShowPageCollectionController is the object deputed to show the page collection dashboard interface
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Page
 */
abstract class ShowPageCollectionController extends BasePageCollectionController
{
    /**
     * Implements the action to show the page collection dashboard interface
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);

        $pagesParser = $this->options["pages_collection_parser"];
        $pages = $pagesParser
            ->contributor($this->options["username"])
            ->parse()
            ->pages();

        $theme = $options["plugin_manager"]->getActiveTheme();
        $this->options["theme_manager"]->boot($theme);
        $this->options["template_assets"]->boot('dashboard');

        $templates = $this->options["theme_manager"]->templates();
        $formFactory = $this->options['form_factory'];
        $form = $formFactory->create(new PageType(array_combine($templates, $templates)));
        $pageForm = $form->createView();
        $form = $formFactory->create(new SeoType());
        $seoForm = $form->createView();

        $template = 'RedKiteCms/Resources/views/Dashboard/pages.html.twig';

        return $options["twig"]->render(
            $template,
            array(
                "template_assets_manager" => $this->options["template_assets"],
                "pages" => rawurlencode(json_encode($pages)),
                "pageForm" => $pageForm,
                "seoForm" => $seoForm,
                "version" => ConfigurationHandler::getVersion(),
            )
        );
    }

    /**
     * Configures the options for the resolver
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(
            array(
                'pages_collection_parser',
                'plugin_manager',
                'theme_manager',
                'template_assets',
                'form_factory',
                'twig',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'pages_collection_parser' => '\RedKiteCms\Content\PageCollection\PagesCollectionParser',
                'plugin_manager' => '\RedKiteCms\Plugin\PluginManager',
                'template_assets' => '\RedKiteCms\Rendering\TemplateAssetsManager\TemplateAssetsManager',
                'theme_manager' => '\RedKiteCms\Content\Theme\ThemeSlotsManager',
                'form_factory' => '\Symfony\Component\Form\FormFactory',
                'twig' => '\Twig_Environment',
            )
        );
    }
}