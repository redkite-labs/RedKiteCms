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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager;

use Symfony\Component\HttpKernel\KernelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterException;

/**
 * Defines the methods to manages an url to be used when in CMS mode and for production mode
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface AlUrlManagerInterface
{
    /**
     * Returns the permalink
     *
     * @return string
     */
    public function getPermalink();

    /**
     * Returns the internal url
     *
     * @return string
     */
    public function getInternalUrl();

    /**
     * Returns the production route that will be generated for the url
     *
     * @return string
     */
    public function getProductionRoute();

    /**
     * Returns the error when something goes wrong
     *
     * @return string
     */
    public function getError();

    /**
     * Builds and internal url to be used when in CMS mode and fetches the information about the
     * url itself
     *
     * @param mixed $language | int, string, \AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage
     * @param mixed $page | int, string, \AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManager
     */
    public function buildInternalUrl($language, $page);

    /**
     * Fetches information from the given url
     *
     * @param string $url
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManager
     */
    public function fromUrl($url);
}