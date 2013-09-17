<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel;

use RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage;
use RedKiteLabs\RedKiteCmsBundle\Model\AlLanguageQuery;
use RedKiteLabs\RedKiteCmsBundle\Core\Event\Query\Language;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the LanguageRepositoryInterface to work with Propel
 *
 *  @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlLanguageRepositoryPropel extends Base\AlPropelRepository implements LanguageRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof AlLanguage) {
            throw new InvalidArgumentTypeException('exception_only_propel_language_objects_are_accepted');
        }

        return parent::setRepositoryObject($object);
    }

    /**
     * {@inheritdoc}
     */
    public function fromPK($id)
    {
        return AlLanguageQuery::create()->findPk($id);
    }

    /**
     * {@inheritdoc}
     */
    public function mainLanguage()
    {
        return AlLanguageQuery::create()
                    ->filterByMainLanguage(1)
                    ->filterByToDelete(0)
                    ->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function fromLanguageName($languageName)
    {
        if (null === $languageName) {
            return null;
        }

        if (!is_string($languageName)) {
            throw new InvalidArgumentTypeException('exception_invalid_value_for_fromLanguageName_method');
        }

        return AlLanguageQuery::create()
                    ->filterByToDelete(0)
                    ->filterByLanguageName($languageName)
                    ->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function activeLanguages()
    {
        return AlLanguageQuery::create()
                ->filterByToDelete(0)
                ->where('id > 1')
                ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function firstOne()
    {
        return AlLanguageQuery::create()
                    ->filterByToDelete(0)
                    ->where('id > 1')
                    ->findOne();
    }
}
