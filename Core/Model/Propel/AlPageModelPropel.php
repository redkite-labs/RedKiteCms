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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Page;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\PagesEvents;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Entities\PageModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;

/**
 *  Adds some filters to the AlPageQuery object
 *
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageModelPropel extends Base\AlPropelModel implements PageModelInterface
{
    /**
     * {@inheritdoc}
     */
    public function getModelObjectClassName()
    {
        return '\AlphaLemon\AlphaLemonCmsBundle\Model\AlPage';
    }

    /**
     * {@inheritdoc}
     */
    public function setModelObject($object = null)
    {
        if (null !== $object && !$object instanceof AlPage) {
            throw new InvalidParameterTypeException('AlPageModelPropel accepts only AlPage propel objects.');
        }

        return parent::setModelObject($object);
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
        if (!is_string($pageName))
        {
          throw new \InvalidArgumentException('This method accepts only strings');
        }

        return AlPageQuery::create()
                    ->filterByToDelete(0)
                    ->filterByPageName(AlToolkit::slugify($pageName))
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
}
