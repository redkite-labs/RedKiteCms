<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\Block\NavigationMenuBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * AlBlockManagerNavigationMenu
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerNavigationMenu extends AlBlockManagerContainer
{
    private $urlManager = null;

    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($container, $validator);

        $this->languageRepository = $this->factoryRepository->createRepository('Language');
        $this->urlManager = $this->container->get('alpha_lemon_cms.url_manager');
    }

    /**
     *  {@inheritdoc}
     */
    public function getDefaultValue()
    {
        return array("Content" => "<ul><li>En</li></ul>");
    }

    /**
     *  {@inheritdoc}
     */
    protected function renderHtml()
    {
        $content = '';
        $page = $this->container->get('alpha_lemon_cms.page_tree')->getAlPage();
        $languages = $this->languageRepository->activeLanguages();
        foreach ($languages as $language) {
            $url = $this->urlManager
                        ->buildInternalUrl($language, $page)
                        ->getInternalUrl();
            if (null === $url)  $url = '#';

            $content .= sprintf('<li><a href="%s">%s</a></li>', $url, $language->getLanguageName());
        }

        return sprintf('<ul>%s</ul>', $content);
    }

    /**
     *  {@inheritdoc}
     */
    protected function replaceHtmlCmsActive()
    {
        $content = '';
        $page = $this->container->get('alpha_lemon_cms.page_tree')->getAlPage();
        $languages = $this->languageRepository->activeLanguages();
        foreach ($languages as $language) {
            $languageName = $language->getLanguageName();
            $url = $this->urlManager
                        ->buildInternalUrl($language, $page)
                        ->getInternalUrl();
            if (null === $url)  {
                $url = '#';
                $languageName .= " [Er]";
            }

            $content .= sprintf('<li><a href="%s">%s</a></li>', $url, $languageName);
        }

        return sprintf('<ul>%s</ul>', $content);
    }
}
