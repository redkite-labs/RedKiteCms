<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Deploy;

use Symfony\Component\DependencyInjection\ContainerInterface;

use RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTreeDeploy;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper;

/**
 * A collection of PageTree objects
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
class AlPageTreeCollection implements \Iterator, \Countable
{
    private $container = null;
    private $pages = array();
    private $factoryRepository = null;
    private $languageRepository = null;
    private $pageRepository = null;
    private $themesCollectionWrapper = null;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface                              $container
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface   $factoryRepository
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\ThemesCollectionWrapper\AlThemesCollectionWrapper $themesCollectionWrapper
     *
     * @api
     */
    public function  __construct(ContainerInterface $container,
            AlFactoryRepositoryInterface $factoryRepository,
            AlThemesCollectionWrapper $themesCollectionWrapper = null)
    {
        $this->container = $container;
        $this->themesCollectionWrapper = (null === $themesCollectionWrapper) ? $container->get('alpha_lemon_cms.themes_collection_wrapper') : $themesCollectionWrapper;
        $this->factoryRepository = $factoryRepository;
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
        $this->pageRepository = $this->factoryRepository->createRepository('Page');

        $this->setUp();
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->pages);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->pages);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->pages);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return reset($this->pages);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return current($this->pages) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->pages);
    }

    /**
     * Returns the AlPageTree object stored at the requird key
     *
     * @param  string                                                        $key
     * @return null|\RedKiteLabs\RedKiteCmsBundle\Core\PageTree\AlPageTree
     *
     * @api
     */
    public function at($key)
    {
        if (!array_key_exists($key, $this->pages)) {
            return null;
        }

        return $this->pages[$key];
    }

    /**
     * Fills up the PageTree collection traversing the saved languages and pages
     */
    protected function setUp()
    {
        $languages = $this->languageRepository->activeLanguages();
        $pages = $this->pageRepository->activePages();

        // Cycles all the website's languages
        foreach ($languages as $language) {
            // Cycles all the website's pages
            foreach ($pages as $page) {
                if ( ! $page->getIsPublished()) {
                    continue;
                }

                // Clones the current TemplateManager object and adds it to a new instance of
                // AlThemesCollectionWrapper, which will be passed to the new PageTree object
                $templateManager = clone($this->themesCollectionWrapper->getTemplateManager());
                $themesCollectionWrapper = new AlThemesCollectionWrapper(
                    $this->themesCollectionWrapper->getThemesCollection(),
                    $templateManager
                );

                $pageTree = new AlPageTreeDeploy(
                    $this->container,
                    $this->factoryRepository,
                    $themesCollectionWrapper
                );

                $pageTree
                    ->setExtraAssetsSuffixes()
                    ->refresh(
                        $language->getId(),
                        $page->getId()
                    );

                $this->pages[] = $pageTree;
            }
        }
    }
}
