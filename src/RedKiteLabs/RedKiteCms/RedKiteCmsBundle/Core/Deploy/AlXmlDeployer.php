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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Deploy;

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;

class AlXmlDeployer extends AlDeployer
{
    /**
     * @inheritDoc
     */
    protected function save(AlPageTree $pageTree)
    {
        $skeletonContents = file_get_contents(AlToolkit::locateResource($this->container, $this->container->getParameter('alcms.deploy.xml_skeleton'), true));

        // Writes template section
        $xml = new \SimpleXMLElement($skeletonContents);
        $theme = $xml->template->addChild('theme', $pageTree->getThemeName());
        $template = $xml->template->addChild('name', $pageTree->getTemplateName());

        $languageName = $pageTree->getAlLanguage()->getLanguage();
        $languageNameMetatag = $xml->header->metatags->addChild($languageName);
        $languageNameMetatag->addChild('title', $pageTree->getMetaTitle());
        $languageNameMetatag->addChild('description', $pageTree->getMetaDescription());
        $languageNameMetatag->addChild('keywords', $pageTree->getMetaKeywords());
        
        $globals['globals'] = $this->container->getParameter(sprintf('themes.%s_%s.stylesheets', \str_replace('bundle', '', \strtolower($pageTree->getThemeName())), $pageTree->getTemplateName()));

        $xml->header->internal_stylesheets->addChild($languageName, $pageTree->getInternalStylesheet());
        $xml->header->internal_javascripts->addChild($languageName, $pageTree->getInternalJavascript());

        // Writes contents section
        foreach($pageTree->getContents() as $slotName => $contents)
        {
            foreach($contents as $content)
            {
                $slot = $xml->body->contents->addChild('slot', urlencode($content['HtmlContent']));
                $slot->addAttribute('name', $slotName);
            }
        }
        
        $xml->asXML(sprintf('%s/%s.xml', $this->dataFolder, $pageTree->getAlPage()->getPageName()));
    }
}