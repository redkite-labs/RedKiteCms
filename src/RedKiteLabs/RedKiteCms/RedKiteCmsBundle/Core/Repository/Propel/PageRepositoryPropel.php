<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Propel;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Page\PageManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\PageQuery;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the PageRepositoryInterface to work with Propel
 *
 *  @author RedKite Labs <webmaster@redkite-labs.com>
 */
class PageRepositoryPropel extends Base\PropelRepository implements PageRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Page';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof Page) {
            throw new InvalidArgumentTypeException('exception_only_propel_page_objects_are_accepted');
        }

        return parent::setRepositoryObject($object);
    }

    /**
     * {@inheritdoc}
     */
    public function fromPK($id)
    {
        return PageQuery::create()->findPk($id);
    }

    /**
     * {@inheritdoc}
     */
    public function activePages()
    {
        return PageQuery::create()
                    ->filterByToDelete(0)
                    ->where('id > 1')
                    ->orderby('PageName')
                    ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fromPageName($pageName)
    {
        if (null === $pageName) {
            return null;
        }

        if (!is_string($pageName)) {
            throw new InvalidArgumentTypeException('exception_invalid_value_for_fromPageName_method');
        }

        return PageQuery::create()
                    ->filterByToDelete(0)
                    ->filterByPageName(PageManager::slugify($pageName))
                    ->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function homePage()
    {
        return PageQuery::create()
                    ->filterByIsHome(1)
                    ->filterByToDelete(0)
                    ->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function fromTemplateName($templateName, $once = false)
    {
        $query = PageQuery::create()
                    ->filterByTemplateName($templateName)
                    ->filterByToDelete(0);

        return ($once) ? $query->findOne() : $query->find();
    }

    /**
     * {@inheritdoc}
     */
    public function templatesInUse()
    {
        return PageQuery::create('a')
                    ->groupByTemplateName()
                    ->select('TemplateName')
                    ->where('a.Id > ?', 1)
                    ->filterByToDelete(0)
                    ->find()
        ;
    }
}
