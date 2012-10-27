<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\PageRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;

/**
 *  Implements the PageRepositoryInterface to work with Propel
 *
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageRepositoryPropel extends Base\AlPropelRepository implements PageRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\AlphaLemon\AlphaLemonCmsBundle\Model\AlPage';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof AlPage) {
            throw new InvalidParameterTypeException('AlPageRepositoryPropel accepts only AlPage propel objects.');
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
          throw new \InvalidArgumentException('The name of the page must be a string. The page cannot be retrieved');
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
}
