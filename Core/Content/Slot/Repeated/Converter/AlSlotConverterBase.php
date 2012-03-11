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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter;

use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory;

abstract class AlSlotConverterBase extends AlSlotManager implements AlSlotConverterInterface
{
    protected $contents;

    public function __construct(ContainerInterface $container, AlSlot $slot, AlPage $alPage, AlLanguage $alLanguage)
    {
        parent::__construct($container, $slot, $alPage, $alLanguage);
        
        $this->contents = AlBlockQuery::create()
                            ->setContainer($this->container)
                            ->retrieveContents(array(1, $this->alLanguage->getId()), array(1, $this->alPage->getId()), $this->slot->getSlotName())
                            ->find();
    }
    
    protected function removeContents()
    {
        AlBlockQuery::create()
                    ->setContainer($this->container)
                    ->retrieveContentsBySlotName($this->slot->getSlotName())
                    ->delete();        
    }
    
    protected function cloneAndAddContent($content, $idLanguage, $idPage)
    {
        $alBlockManager = AlBlockManagerFactory::createBlock($this->container, $content->getClassName()); 
        $contentValue = array(
            "PageId"                => $idPage,
            "LanguageId"            => $idLanguage,
            "SlotName"              => $content->getSlotName(),
            "ClassName"             => $content->getClassName(),
            "HtmlContent"           => $content->getHtmlContent(),
            "InternalJavascript"    => $content->getInternalJavascript(),
            "InternalStylesheet"    => $content->getInternalStylesheet(),
            "ExternalJavascript"    => $content->getExternalJavascript(),
            "ExternalStylesheet"    => $content->getExternalStylesheet(),
            "ContentPosition"       => $content->getContentPosition()
        );
        $alBlockManager->save($contentValue);
        
        return $alBlockManager->get();
    }
}