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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Deploy\SitemapGenerator;

use Symfony\Component\Templating\EngineInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\PageTreeCollection\AlPageTreeCollection;

/**
 * SitemapGenerator is the object deputated to generate and write the website sitemap
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class SitemapGenerator implements SitemapGeneratorInterface
{
    /** @var AlPageTreeCollection */
    private $pageTreeCollection;
    /** @var EngineInterface */
    private $templating;

    /**
     * Constructor
     *
     * @param AlPageTreeCollection $pageTreeCollection
     * @param EngineInterface      $templating
     */
    public function __construct(AlPageTreeCollection $pageTreeCollection, EngineInterface $templating)
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
            $page = $pageTree->getAlPage();
            if ( ! $page->getIsPublished()) {
                continue;
            }

            $seo = $pageTree->getAlSeo();
            $permalink = "";
            if ( ! $pageTree->getAlLanguage()->getMainLanguage() || ! $page->getIsHome()) {
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
