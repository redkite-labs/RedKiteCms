<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\ImagesBlock;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;

/**
 * AlBlockManagerImages
 *
 * @author alphalemon
 */
abstract class AlBlockManagerImages extends AlBlockManager
{
    protected function edit(array $values)
    {
        if(array_key_exists('HtmlContent', $values)) {
            
            $content = $values["HtmlContent"];
            
            if(!array_key_exists('remove', $values)) {
                $imageFile = preg_replace('/http?:\/\/[^\/]+/', '', $content);
                if(false === strpos($this->alBlock->getHtmlContent(), $imageFile))
                {
                    $values["HtmlContent"] = $this->alBlock->getHtmlContent() . ',' . $imageFile;
                }
                else
                {
                    throw new \Exception("File already added");
                }
            }
            else {
                $file = $this->container->getParameter('kernel.root_dir') . '/../' . $this->container->getParameter('alphalemon_cms.web_folder') . $content;
                $files = array_flip(explode(',', $this->alBlock->getHtmlContent()));
                unset($files[$content]);
                $values["HtmlContent"] = implode(',', array_flip($files));
                @unlink($file);
            }
        }
        
        return parent::edit($values);
    }
}