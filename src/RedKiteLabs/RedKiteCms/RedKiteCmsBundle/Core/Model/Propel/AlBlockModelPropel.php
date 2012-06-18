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
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Entities\BlockModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;

/**
 *  Adds some filters to the AlBlockQuery object
 *
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockModelPropel extends Base\AlPropelModel implements BlockModelInterface
{
    /**
     * {@inheritdoc}
     */
    public function getModelObjectClassName()
    {
        return '\AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock';
    }

    /**
     * {@inheritdoc}
     */
    public function setModelObject($object = null)
    {
        if (null !== $object && !$object instanceof AlBlock) {
            throw new InvalidParameterTypeException('AlBlockModelPropel accepts only AlBlock propel objects');
        }

        return parent::setModelObject($object);
    }

    /**
     * {@inheritdoc}
     */
    public function fromPK($id)
    {
        $query = AlBlockQuery::create();
        $this->dispatchQueryEvent('\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Content\FromPKQueringEvent', ContentsEvents::FROM_PK, $query);

        return $query->findPk($id);
    }

    /**
     * {@inheritdoc}
     */
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
        $this->dispatchQueryEvent('\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Content\RetrieveContentsQueringEvent', ContentsEvents::RETRIEVE_CONTENTS, $query);

        return $query->find();
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveContentsBySlotName($slotName)
    {
        $query = AlBlockQuery::create()
                ->filterBySlotName($slotName)
                ->filterByToDelete(0);
        $this->dispatchQueryEvent('\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Content\RetrieveContentsBySlotNameQueringEvent', ContentsEvents::RETRIEVE_CONTENTS_BY_SLOT_NAME, $query);

        return $query->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fromLanguageId($languageId)
    {
        $query = AlBlockQuery::create()
                ->filterByLanguageId($languageId)
                ->filterByToDelete(0);
        $this->dispatchQueryEvent('\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Content\FromLanguageIdQueringEvent', ContentsEvents::FROM_LANGUAGE_ID, $query);

        return $query->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fromPageId($pageId)
    {
        $query = AlBlockQuery::create()
                ->filterByPageId($pageId)
                ->filterByToDelete(0);
        $this->dispatchQueryEvent('\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Content\FromPageIdQueringEvent', ContentsEvents::FROM_PAGE_ID, $query);

        return $query->find();
    }

    /**
     * {@inheritdoc}
     */
    public function fromHtmlContent($search)
    {
        $query = AlBlockQuery::create()
                ->filterByHtmlContent('%' . $search . '%')
                ->filterByToDelete(0);
        $this->dispatchQueryEvent('\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Query\Content\FromHtmlContentQueringEvent', ContentsEvents::FROM_HTML_CONTENT, $query);

        return $query->find();
    }
}
