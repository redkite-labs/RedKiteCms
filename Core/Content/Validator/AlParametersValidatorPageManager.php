<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator;

use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface;

/**
 * AlParametersValidatorPageManager adds specific validations for pages
 *
 * PageManager depends on website's languages, because before a page can be added
 * at least a language must esist. For this reason the AlParametersValidatorPageManager
 * inherits from AlParametersValidatorLanguageManager instead of the base validator
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlParametersValidatorPageManager extends AlParametersValidatorLanguageManager
{
    protected $pageRepository = null;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $factoryRepository
     * 
     * @api
     */
    public function __construct(AlFactoryRepositoryInterface $factoryRepository)
    {
        parent::__construct($factoryRepository);

        $this->pageRepository = $this->factoryRepository->createRepository('Page');
    }

    /**
     * Sets the page model object
     *
     * @param  \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface      $v
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorPageManager
     *
     * @api
     */
    public function setPageRepository(PageRepositoryInterface $v)
    {
        $this->pageRepository = $v;

        return $this;
    }

    /**
     * Returns the page model object
     *
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface
     *
     * @api
     */
    public function getPageRepository()
    {
        return $this->pageRepository;
    }

    /**
     * Checks if any page exists. When the min parameter is specified, checks thatthe number of existing pages
     * is greater than the given value
     *
     * @param  int     $min
     * @return boolean
     *
     * @api
     */
    public function hasPages($min = 0)
    {
        return (count($this->pageRepository->activePages()) > $min) ? true : false;
    }

    /**
     * Checks when the given page name exists
     *
     * @param  int     $pageName
     * @return boolean
     *
     * @api
     */
    public function pageExists($pageName)
    {
        return (count($this->pageRepository->fromPageName($pageName)) > 0) ? true : false;
    }
}
