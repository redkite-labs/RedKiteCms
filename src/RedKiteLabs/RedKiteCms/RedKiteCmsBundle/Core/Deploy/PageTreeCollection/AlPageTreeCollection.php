<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\PageTreeCollection;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\AlPageTree;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\DataManager\DataManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ActiveTheme\AlActiveThemeInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocksInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlLanguage;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlPage;
use RedKiteLabs\ThemeEngineBundle\Core\Template\AlTemplate;

/**
 * A collection of PageTree objects
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlPageTreeCollection
{
    private $pages = array();
    /** @var TemplateAssetsManager */
    private $assetsManager;
    /** @var AlActiveThemeInterface */
    private $activeTheme;
    /** @var null|\RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface */
    private $theme;
    /** @var AlTemplateManager */
    private $templateManager;
    /** @var AlPageBlocksInterface */
    private $pageBlocks;
    /** @var null|AlFactoryRepositoryInterface */
    private $factoryRepository = null;
    /** @var null|\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface */
    private $languageRepository = null;
    /** @var null|\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface */
    private $pageRepository = null;
    /** @var null|\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface */
    private $blockRepository = null;

    /**
     * Constructor
     *
     * @param TemplateAssetsManager        $templateAssetsManager
     * @param AlActiveThemeInterface       $activeTheme
     * @param AlTemplateManager            $templateManager
     * @param AlPageBlocksInterface        $pageBlocks
     * @param AlFactoryRepositoryInterface $factoryRepository
     *
     * @api
     */
    public function __construct(TemplateAssetsManager $templateAssetsManager, AlActiveThemeInterface $activeTheme, AlTemplateManager $templateManager, AlPageBlocksInterface $pageBlocks, AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->assetsManager = $templateAssetsManager;
        $this->activeTheme = $activeTheme;
        $this->theme = $activeTheme->getActiveTheme();
        $this->templateManager = $templateManager;
        $this->pageBlocks = $pageBlocks;
        $this->factoryRepository = $factoryRepository;
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
        $this->pageRepository = $this->factoryRepository->createRepository('Page');
        $this->blockRepository = $this->factoryRepository->createRepository('Block');
    }

    /**
     * Return the PageTree to generate base templates
     *
     * @return array
     */
    public function getBasePages()
    {
        return $this->pages['base'];
    }

    /**
     * Return the PageTree to generate page templates
     *
     * @return AlPageTree[]
     */
    public function getPages()
    {
        return $this->pages['page'];
    }

    /**
     * Fills up the PageTree collection
     */
    public function fill()
    {
        $languages = $this->languageRepository->activeLanguages();
        $this->initPages($languages);
        $this->initBasePages($languages);
    }

    private function initPages($languages)
    {
        $pages = $this->pageRepository->activePages();

        // Cycles all the website's languages
        foreach ($languages as $language) {
            // Cycles all the website's pages
            foreach ($pages as $page) {
                $this->pages['page'][] = $this->initPageTree($language, $page);
            }
        }
    }

    private function initBasePages($languages)
    {
        $templates = $this->theme->getTemplates();
        foreach ($languages as $language) {
            $blocks = $this->blockRepository->retrieveContents(array(1, $language->getId()), 1);
            foreach ($templates as $template) {
                $this->pageBlocks->setAlBlocks($blocks);

                $this->pages['base'][] = $this->initPageTree($language, null, $template);
            }
        }
    }

    private function initPageTree(AlLanguage $language, AlPage $page = null, AlTemplate $template = null)
    {
        $dataManager = new DataManager($this->factoryRepository);
        $dataManager->fromEntities($language, $page);

        $pageBlocks = clone($this->pageBlocks);
        if (null !== $page) {
            $pageBlocks->refresh($language->getId(), $page->getId());
        }

        $assetsManager = clone($this->assetsManager);
        $assetsManager->setPageBlocks($pageBlocks);

        $pageTree = new AlPageTree(
            $assetsManager,
            null,
            $dataManager
        );

        $pageTree
            ->productionMode(true)
            ->setUp($this->theme, clone($this->templateManager), $pageBlocks, $template)
        ;

        return $pageTree;
    }
}
