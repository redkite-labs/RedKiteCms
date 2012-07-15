<?php
/*
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\NavigationMenuBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPageAttributePeer;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;

/**
 * AlBlockManagerMenu
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerNavigationMenu extends AlBlockManager
{

    public function __construct(\Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher, AlFactoryRepositoryInterface $factoryRepository, AlParametersValidatorInterface $validator = null)
    {
        $blockRepository = $factoryRepository->createRepository('Block');
        parent::__construct($dispatcher, $blockRepository, $validator);

        $this->languageRepository = $factoryRepository->createRepository('Language');
        $this->blockRepository = (null === $blockRepository) ? new AlBlockRepositoryPropel() : $blockRepository;
    }

    /**
     *  {@inheritdoc}
     */
    public function getDefaultValue()
    {
        return array("HtmlContent" => "<ul><li>En</li></ul>");
    }

    public function getHtmlContent()
    {
        $content = '';
        $languages = \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlLanguageQuery::create()->setContainer($this->container)->activeLanguages()->find();
        foreach($languages as $language)
        {
            $permalink = "";
            $languageName = $language->getLanguage();

            try
            {
                $route = sprintf('_%s_%s', $language, str_replace('-', '_', $this->container->get('al_page_tree')->getAlPage()->getPageName()));
                $url = $this->container->get('router')->generate($route);

            }
            catch(\Exception $ex)
            {
                $url = "#";
                $languageName .= " Err!";
            }

            $content .= sprintf('<li><a href="%s">%s</a></li>', $url, $languageName);
        }

        return sprintf('<ul>%s</ul>', $content);
    }

    public function getHtmlContentCMSMode()
    {
        $content = '';
        $languages = \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlLanguageQuery::create()->setContainer($this->container)->activeLanguages()->find();
        foreach($languages as $language)
        {
            $frontController = $this->container->get('kernel')->getEnvironment() . '.php';
            $content .= sprintf('<li><a href="/%s/%s/%s">%s</a></li>', $frontController, $language->getLanguage(), $this->container->get('al_page_tree')->getAlPage()->getPageName(), $language->getLanguage());
        }

        return sprintf('<ul>%s</ul>', $content);
    }
}
