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
 
namespace AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\AlMediaBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\AlMediaBundle\Core\Media\AlMediaBuilder;

/**
 * AlBlockManagerMedia
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlBlockManagerMedia extends AlBlockManager
{
    public function getDefaultValue()
    {
        $default = array();
        $default["HtmlContent"] = "A generic media file";

        return $default;
    }

    /**
     * @see AlBlockManager::getHtmlContent()
     *
     */
    public function getHtmlContent()
    {
        $dv = $this->getDefaultValue();
		$media = $this->buildMedia();
		return (null !== $media) ? $media->render() : $this->alContent->getHtmlContent();
		
        return $dv["HtmlContent"];
    }
    
    protected function buildMedia()
    {
        $mediaBuilder = new AlMediaBuilder($this->container, $this->alContent->getHtmlContent());
        $mediaBuilder->createMedia();

        return $mediaBuilder->getMedia();
    }
    
    protected function edit($values)
    {
        // Just the filename is needed
        $fileName = basename($values['HtmlContent']);
        $values['HtmlContent'] = $fileName;
        
        return parent::edit($values);
    }
}
