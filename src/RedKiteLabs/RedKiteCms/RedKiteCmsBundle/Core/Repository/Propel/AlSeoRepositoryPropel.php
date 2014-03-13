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

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlSeo;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlSeoQuery;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\SeoRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the SeoRepositoryInterface to work with Propel
 *
 *  @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlSeoRepositoryPropel extends Base\AlPropelRepository implements SeoRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlSeo';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof AlSeo) {
            throw new InvalidArgumentTypeException('exception_only_propel_seo_objects_are_accepted');
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
            throw new InvalidArgumentTypeException('exception_invalid_value_for_fromPermalink_method');
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
    public function fromLanguageName($languageName, $ordered = true)
    {
        return AlSeoQuery::create('a')
                    ->joinWith('a.AlLanguage')
                    ->where('AlLanguage.languageName = ?', $languageName)
                    ->filterByToDelete(0)
                    ->_if($ordered)
                        ->orderByPermalink()
                    ->_endif()
                    ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fromLanguageAndPageNames($languageName, $pageName)
    {
        return AlSeoQuery::create('a')
                ->useAlLanguageQuery()
                    ->filterByLanguageName($languageName)
                  ->endUse()
                ->useAlPageQuery()
                    ->filterByPageName($pageName)
                ->endUse()
                ->with('AlLanguage')
                ->with('AlPage')
                ->filterByToDelete(0)
                ->findOne();
    }
}
