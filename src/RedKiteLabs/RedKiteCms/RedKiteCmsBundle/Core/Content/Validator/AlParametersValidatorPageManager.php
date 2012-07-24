<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator;

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\PageRepositoryInterface;

/**
 * AlParametersValidatorPageManager adds specific validations for pages
 *
 * PageManager depends on website's languages, because before a page can be added
 * at least a language must esist. For this reason the AlParametersValidatorPageManager
 * inherits from AlParametersValidatorLanguageManager instead of the base validator
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlParametersValidatorPageManager extends AlParametersValidatorLanguageManager
{
    protected $pageRepository = null;

    /**
     * Constructor
     *
     * @param LanguageRepositoryInterface $languageRepository
     * @param AlFactoryRepositoryInterface $factoryRepository
     */
    public function __construct(AlFactoryRepositoryInterface $factoryRepository)
    {
        parent::__construct($factoryRepository);

        $this->pageRepository = $this->factoryRepository->createRepository('Page');
    }

    /**
     * Sets the page model object
     *
     * @param PageRepositoryInterface $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager
     */
    public function setPageRepository(PageRepositoryInterface $v)
    {
        $this->pageRepository = $v;

        return $this;
    }

    /**
     * Returns the page model object
     *
     * @return PageRepositoryInterface
     */
    public function getPageRepository()
    {
        return $this->pageRepository;
    }

    /**
     * Checks if any page exists. When the min parameter is specified, checks thatthe number of existing pages
     * is greater than the given value
     *
     * @param int $min
     * @return boolean
     */
    public function hasPages($min = 0)
    {
        return (count($this->pageRepository->activePages()) > $min) ? true : false;
    }

    /**
     * Checks when the given page name exists
     *
     * @param int $pageName
     * @return boolean
     */
    public function pageExists($pageName)
    {
        return (count($this->pageRepository->fromPageName($pageName)) > 0) ? true : false;
    }
}