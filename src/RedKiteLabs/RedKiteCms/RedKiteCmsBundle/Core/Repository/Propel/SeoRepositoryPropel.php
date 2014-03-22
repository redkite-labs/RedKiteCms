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

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Seo;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\SeoQuery;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\SeoRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the SeoRepositoryInterface to work with Propel
 *
 *  @author RedKite Labs <webmaster@redkite-labs.com>
 */
class SeoRepositoryPropel extends Base\PropelRepository implements SeoRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Seo';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof Seo) {
            throw new InvalidArgumentTypeException('exception_only_propel_seo_objects_are_accepted');
        }

        return parent::setRepositoryObject($object);
    }

    /**
     * {@inheritdoc}
     */
    public function fromPK($id)
    {
        return SeoQuery::create()->findPk($id);
    }

    /**
     * {@inheritdoc}
     */
    public function fromPageAndLanguage($languageId, $pageId)
    {
        return SeoQuery::create()
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

        return SeoQuery::create('a')
                    ->joinWith('a.Page')
                    ->joinWith('a.Language')
                    ->filterByPermalink($permalink)
                    ->filterByToDelete(0)
                    ->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function fromPageId($pageId)
    {
        return SeoQuery::create()
                    ->filterByPageId($pageId)
                    ->filterByToDelete(0)
                    ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fromLanguageId($languageId)
    {
        return SeoQuery::create()
                    ->filterByLanguageId($languageId)
                    ->filterByToDelete(0)
                    ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fromPageIdWithLanguages($pageId)
    {
        return SeoQuery::create()
                    ->joinLanguage()
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
        return SeoQuery::create('a')
                    ->joinWith('a.Page')
                    ->joinWith('a.Language')
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
        return SeoQuery::create('a')
                    ->joinWith('a.Language')
                    ->where('Language.languageName = ?', $languageName)
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
        return SeoQuery::create('a')
                ->useLanguageQuery()
                    ->filterByLanguageName($languageName)
                  ->endUse()
                ->usePageQuery()
                    ->filterByPageName($pageName)
                ->endUse()
                ->with('Language')
                ->with('Page')
                ->filterByToDelete(0)
                ->findOne();
    }
}
