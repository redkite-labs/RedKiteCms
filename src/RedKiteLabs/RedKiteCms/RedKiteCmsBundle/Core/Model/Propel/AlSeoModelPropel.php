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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Seo;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\SeoEvents;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlSeoQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Entities\SeoModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;

/**
 *  Adds some filters to the AlSeoQuery object
 *
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlSeoModelPropel extends Base\AlPropelModel implements SeoModelInterface
{
    /**
     * {@inheritdoc}
     */
    public function getModelObjectClassName()
    {
        return '\AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo';
    }

    /**
     * {@inheritdoc}
     */
    public function setModelObject($object = null)
    {
        if (null !== $object && !$object instanceof AlSeo) {
            throw new InvalidParameterTypeException('AlSeoModel accepts only AlSeo propel objects.');
        }

        return parent::setModelObject($object);
    }

    /**
     * {@inheritdoc}
     */
    public function fromPK($id)
    {
        return AlSeoQuery::create()->findPk($id);
    }

    /**
     * {@inheritdoc}
     */
    public function fromPageAndLanguage($languageId, $pageId)
    {
        return AlSeoQuery::create()
                    ->filterByPageId($pageId)
                    ->filterByLanguageId($languageId)
                    ->filterByToDelete(0)
                    ->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function fromPermalink($permalink)
    {
        if (null === $permalink)
        {
            return null;
        }

        if (!is_string($permalink))
        {
            throw new \InvalidArgumentException('The permalink must be a string. The seo attribute cannot be retrieved');
        }
        
        return AlSeoQuery::create()
                    ->filterByPermalink($permalink)
                    ->filterByToDelete(0)
                    ->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function fromPageId($pageId)
    {
        return AlSeoQuery::create()
                    ->filterByPageId($pageId)
                    ->filterByToDelete(0)
                    ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fromLanguageId($languageId)
    {
        return AlSeoQuery::create()
                    ->filterByLanguageId($languageId)
                    ->filterByToDelete(0)
                    ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fromPageIdWithLanguages($pageId)
    {
        return AlSeoQuery::create()
                    ->joinAlLanguage()
                    ->filterByPageId($pageId)
                    ->filterByToDelete(0)
                    ->orderByLanguageId()
                    ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fetchSeoAttributesWithPagesAndLanguages()
    {
        return AlSeoQuery::create('a')
                    ->joinWith('a.AlPage')
                    ->joinWith('a.AlLanguage')
                    ->filterByToDelete(0)
                    ->orderByPageId()
                    ->orderByLanguageId()
                    ->find();
    }
}
