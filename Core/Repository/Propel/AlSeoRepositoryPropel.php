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

use AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlSeoQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\SeoRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the SeoRepositoryInterface to work with Propel
 *
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlSeoRepositoryPropel extends Base\AlPropelRepository implements SeoRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof AlSeo) {
            throw new InvalidArgumentTypeException('AlSeoRepositoryPropel accepts only AlSeo propel objects');
        }

        return parent::setRepositoryObject($object);
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
        if (null === $permalink) {
            return null;
        }

        if (!is_string($permalink)) {
            throw new InvalidArgumentTypeException('fromPermalink method accepts only string values');
        }

        return AlSeoQuery::create('a')
                    ->joinWith('a.AlPage')
                    ->joinWith('a.AlLanguage')
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

    /**
     * {@inheritdoc}
     */
    public function fromLanguageName($languageName)
    {   
        return AlSeoQuery::create('a')
                    ->joinWith('a.AlLanguage')
                    ->where('AlLanguage.languageName = ?', $languageName)
                    ->filterByToDelete(0)
                    /*, $ordered = true
                     * 
                     * ->orderByAlLanguageLanguageName()
                    
                    ->_if($ordered)
                        ->orderBy('AlLanguage.languageName')
                    ->_endif()*/
                    ->find();
    }
}
