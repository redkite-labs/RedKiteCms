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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Page\AlPageManager;
use RedKiteLabs\RedKiteCmsBundle\Model\AlPage;
use RedKiteLabs\RedKiteCmsBundle\Model\AlPageQuery;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the PageRepositoryInterface to work with Propel
 *
 *  @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlPageRepositoryPropel extends Base\AlPropelRepository implements PageRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\RedKiteLabs\RedKiteCmsBundle\Model\AlPage';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof AlPage) {
            throw new InvalidArgumentTypeException('AlPageRepositoryPropel accepts only AlPage propel objects');
        }

        return parent::setRepositoryObject($object);
    }

    /**
     * {@inheritdoc}
     */
    public function fromPK($id)
    {
        return AlPageQuery::create()->findPk($id);
    }

    /**
     * {@inheritdoc}
     */
    public function activePages()
    {
        return AlPageQuery::create()
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
            throw new InvalidArgumentTypeException('fromPageName method accepts only string values');
        }

        return AlPageQuery::create()
                    ->filterByToDelete(0)
                    ->filterByPageName(AlPageManager::slugify($pageName))
                    ->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function homePage()
    {
        return AlPageQuery::create()
                    ->filterByIsHome(1)
                    ->filterByToDelete(0)
                    ->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function fromTemplateName($templateName, $once = false)
    {
        $query = AlPageQuery::create()
                    ->filterByTemplateName($templateName)
                    ->filterByToDelete(0);

        return ($once) ? $query->findOne() : $query->find();
    }

    /**
     * {@inheritdoc}
     */
    public function templatesInUse()
    {
        return AlPageQuery::create('a')
                    ->groupByTemplateName()
                    ->where('a.Id > ?', 1)
                    ->filterByToDelete(0)
                    ->find()
        ;
    }
}
