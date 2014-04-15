<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface;

/**
 * ParametersValidatorPageManager adds specific validations for pages
 *
 * PageManager depends on website's languages, because before a page can be added
 * at least a language must esist. For this reason the ParametersValidatorPageManager
 * inherits from ParametersValidatorLanguageManager instead of the base validator
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class ParametersValidatorPageManager extends ParametersValidatorLanguageManager
{
    protected $pageRepository = null;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface $factoryRepository
     *
     * @api
     */
    public function __construct(FactoryRepositoryInterface $factoryRepository)
    {
        parent::__construct($factoryRepository);

        $this->pageRepository = $this->factoryRepository->createRepository('Page');
    }

    /**
     * Sets the page model object
     *
     * @param  \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface      $v
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorPageManager
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
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface
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
