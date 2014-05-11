<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\SitemapGenerator;

use Symfony\Component\Templating\EngineInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Deploy\PageTreeCollection\PageTreeCollection;

/**
 * SitemapGenerator is the object deputed to generate and write the website sitemap
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class SitemapGenerator implements SitemapGeneratorInterface
{
    /** @var PageTreeCollection */
    private $pageTreeCollection;
    /** @var EngineInterface */
    private $templating;

    /**
     * Constructor
     *
     * @param PageTreeCollection $pageTreeCollection
     * @param EngineInterface      $templating
     */
    public function __construct(PageTreeCollection $pageTreeCollection, EngineInterface $templating)
    {
        $this->pageTreeCollection = $pageTreeCollection;
        $this->templating = $templating;
    }

    /**
     * {@inheritdoc}
     */
    public function writeSiteMap($path, $websiteUrl)
    {
        $sitemap = $this->generateSiteMap($websiteUrl);

        return @file_put_contents($path . '/sitemap.xml', $sitemap);
    }

    /**
     * Generated the site map
     *
     * @param  string $websiteUrl
     * @return string
     */
    protected function generateSiteMap($websiteUrl)
    {
        $urls = array();
        foreach ($this->pageTreeCollection->getPages() as $pageTree) {
            $page = $pageTree->getPage();
            if ( ! $page->getIsPublished()) {
                continue;
            }

            $seo = $pageTree->getSeo();
            $permalink = "";
            if ( ! $pageTree->getLanguage()->getMainLanguage() || ! $page->getIsHome()) {
                $permalink = $seo->getPermalink();
            }

            $urls[] = array(
                'href' => $websiteUrl . $permalink,
                'frequency' => $seo->getSitemapChangefreq(),
                'priority' => $seo->getSitemapPriority()
            );
        }

        return $this->templating->render('RedKiteCmsBundle:Sitemap:sitemap.html.twig', array('urls' => $urls));
    }
}
