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

use RedKiteLabs\RedKiteCmsBundle\Model\AlBlock;
use RedKiteLabs\RedKiteCmsBundle\Model\AlBlockQuery;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the BlockRepositoryInterface to work with Propel
 *
 *  @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockRepositoryPropel extends Base\AlPropelRepository implements BlockRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\RedKiteLabs\RedKiteCmsBundle\Model\AlBlock';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof AlBlock) {
            throw new InvalidArgumentTypeException('AlBlockRepositoryPropel accepts only AlBlock propel objects');
        }

        return parent::setRepositoryObject($object);
    }

    /**
     * {@inheritdoc}
     */
    public function fromPK($id)
    {
        return AlBlockQuery::create()->findPk($id);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveContents($idLanguage, $idPage, $slotName = null, $toDelete = 0)
    {
        return AlBlockQuery::create()
                ->_if($idPage)
                    ->filterByPageId($idPage)
                ->_endif()
                ->_if($idLanguage)
                    ->filterByLanguageId($idLanguage)
                ->_endif()
                ->_if($slotName)
                    ->filterBySlotName($slotName)
                ->_endif()
                ->filterByToDelete($toDelete)
                ->orderBySlotName()
                ->orderByContentPosition()
                ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveContentsBySlotName($slotName, $toDelete = 0)
    {
        return AlBlockQuery::create('a')
                ->where('a.SlotName like ?', $slotName) 
                ->filterByToDelete($toDelete)
                ->orderBySlotName()
                ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fromLanguageId($languageId)
    {
        return AlBlockQuery::create()
                ->filterByLanguageId($languageId)
                ->filterByToDelete(0)
                ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fromPageId($pageId)
    {
        return AlBlockQuery::create()
                ->filterByPageId($pageId)
                ->filterByToDelete(0)
                ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fromContent($search)
    {
        return AlBlockQuery::create()
                ->filterByContent('%' . $search . '%')
                ->filterByToDelete(0)
                ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fromType($className, $operation = 'find')
    {
        return AlBlockQuery::create()
                ->filterByType($className)
                ->filterByToDelete(0)
                ->$operation();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteBlocks($idLanguage, $idPage, $remove = false)
    {
        $blocks = AlBlockQuery::create()
                ->_if($idLanguage)
                    ->filterByPageId($idLanguage)
                ->_endif()
                ->_if($idPage)
                    ->filterByLanguageId($idPage)
                ->_endif();

        if ($remove) {
            $blocks->delete();
        } else {
            $blocks->update(array('ToDelete' => '1'));
        }
    }
}
