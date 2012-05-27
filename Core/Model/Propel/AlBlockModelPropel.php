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

use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Content;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\ContentsEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\BlockModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;

/**
 *  Adds some filters to the AlBlockQuery object
 *
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockModelPropel extends Base\AlPropelModel implements BlockModelInterface
{
    public function getModelObjectClassName()
    {
        return '\AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock';
    }

    public function setModelObject($object = null)
    {
        if (null !== $object && !$object instanceof AlBlock) {
            throw new InvalidParameterTypeException('AlBlockModelPropel accepts only AlBlock propel objects');
        }

        return parent::setModelObject($object);
    }

    public function fromPK($id)
    {
        $query = AlBlockQuery::create();

        if(null !== $this->dispatcher)
        {
            /*TODO
            $event = new Content\FromPageIdQueringEvent($query);
            $this->dispatcher->dispatch(ContentsEvents::FROM_PAGE_ID, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }*/
        }

        return $query->findPk($id);
    }

    public function retrieveContents($idLanguage, $idPage, $slotName = null)
    {
        $query = AlBlockQuery::create()
                ->filterByPageId($idPage)
                ->filterByLanguageId($idLanguage)
                ->_if($slotName)
                    ->filterBySlotName($slotName)
                ->_endif()
                ->filterByToDelete(0)
                ->orderBySlotName()
                ->orderByContentPosition();

        if(null !== $this->dispatcher)
        {
            $event = new Content\RetrieveContentsQueringEvent($query);
            $this->dispatcher->dispatch(ContentsEvents::RETRIEVE_CONTENTS, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }

        return $query->find();
    }

    public function retrieveContentsBySlotName($slotName)
    {
        $query = AlBlockQuery::create()
                ->filterBySlotName($slotName)
                ->filterByToDelete(0);

        if(null !== $this->dispatcher)
        {
            $event = new Content\RetrieveContentsBySlotNameQueringEvent($query);
            $this->dispatcher->dispatch(ContentsEvents::RETRIEVE_CONTENTS_BY_SLOT_NAME, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }

        return $query->find();
    }

    public function fromLanguageId($languageId)
    {
        $query = AlBlockQuery::create()
                ->filterByLanguageId($languageId)
                ->filterByToDelete(0);

        if(null !== $this->dispatcher)
        {
            $event = new Content\FromLanguageIdQueringEvent($query);
            $this->dispatcher->dispatch(ContentsEvents::FROM_LANGUAGE_ID, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }

        return $query->find();
    }

    public function fromPageId($pageId)
    {
        $query = AlBlockQuery::create()
                ->filterByPageId($pageId)
                ->filterByToDelete(0);

        if(null !== $this->dispatcher)
        {
            $event = new Content\FromPageIdQueringEvent($query);
            $this->dispatcher->dispatch(ContentsEvents::FROM_PAGE_ID, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }

        return $query->find();
    }

    public function fromPageIdAndSlotName($pageId, $slotName)
    {
        $query = AlBlockQuery::create()
                ->filterByPageId($pageId)
                ->filterBySlotName($slotName)
                ->filterByToDelete(0);

        if(null !== $this->dispatcher)
        {
            $event = new Content\FromPageIdAndSlotNameQueringEvent($query);
            $this->dispatcher->dispatch(ContentsEvents::FROM_PAGE_ID_AND_SLOT_NAME, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }

        return $query->find();
    }

    public function fromHtmlContent($search)
    {
        $query = AlBlockQuery::create()
                ->filterByHtmlContent('%' . $search . '%')
                ->filterByToDelete(0);

        if(null !== $this->dispatcher)
        {
            $event = new Content\FromPageIdAndSlotNameQueringEvent($query);
            $this->dispatcher->dispatch(ContentsEvents::FROM_PAGE_ID_AND_SLOT_NAME, $event);

            if($query !== $event->getQuery())
            {
                $query = $event->getQuery();
            }
        }

        return $query->find();
    }
}
