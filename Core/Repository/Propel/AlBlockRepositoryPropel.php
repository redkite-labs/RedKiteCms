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

use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the BlockRepositoryInterface to work with Propel
 *
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockRepositoryPropel extends Base\AlPropelRepository implements BlockRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof AlBlock) {
            $exception = array(
                'message' => 'AlBlockRepositoryPropel accepts only AlBlock propel objects',
                'domain' => 'exceptions',
            );
            throw new InvalidArgumentTypeException(json_encode($exception));
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
    public function retrieveRepeatedContents()
    {
        return AlBlockQuery::create()
                ->filterByPageId(1)
                ->_or()
                ->filterByLanguageId(1)
                ->filterByToDelete(0)
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
    public function deleteIncludedBlocks($key)
    {
        return AlBlockQuery::create()
                ->filterBySlotName($key . '%')
                ->filterByToDelete(0)
                ->update(array('ToDelete' => '1'));
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
        }
        else {
            $blocks->update(array('ToDelete' => '1'));
        }
    }
}
