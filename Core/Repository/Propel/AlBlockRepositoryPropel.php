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

use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Content;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;

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
            throw new InvalidParameterTypeException('AlBlockRepositoryPropel accepts only AlBlock propel objects');
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
    public function retrieveContents($idLanguage, $idPage, $slotName = null)
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
                ->filterByToDelete(0)
                ->orderBySlotName()
                ->orderByContentPosition()
                ->find();
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveContentsBySlotName($slotName)
    {
        return AlBlockQuery::create()
                ->filterBySlotName($slotName)
                ->filterByToDelete(0)
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
}
