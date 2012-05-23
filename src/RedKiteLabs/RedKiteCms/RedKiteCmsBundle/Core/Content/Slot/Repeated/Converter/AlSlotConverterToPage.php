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

use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery;

class AlSlotConverterToPage extends AlSlotConverterBase
{ 
    public function convert()
    {
        if(count($this->arrayBlocks) > 0)
        {
            try
            {
                $result = null;
                $this->blockModel->startTransaction();
                $this->removeContents(); 

                $languages = $this->languageModel->activeLanguages();
                $pages = $this->pageModel->activePages();
                foreach($this->arrayBlocks as $block)
                {
                    foreach($languages as $language)
                    {
                        foreach($pages as $page)
                        {
                            $result = $this->updateBlock($block, $language->getId(), $page->getId());
                        }
                    }
                }

                if ($result)
                {
                    $this->blockModel->commit();
                }
                else
                {
                    $this->blockModel->rollBack();
                }

                return $result;
            }
            catch(\Exception $e)
            {
                if(isset($this->blockModel) && $this->blockModel !== null) {
                    $this->blockModel->rollBack();
                }

                throw $e;
            }
        }
        /*
        try
        {
            $rollBack = false;
            $this->connection->beginTransaction();

            $this->removeContents(); 

            $languages = AlLanguageQuery::create()->setContainer($this->container)->activeLanguages()->find(); 
            $pages = AlPageQuery::create()->setContainer($this->container)->activePages()->find();
            foreach($this->contents as $content)
            {
                foreach($languages as $language)
                {
                    foreach($pages as $page)
                    {
                        $newContent = $this->cloneAndAddContent($content, $language->getId(), $page->getId());
                        $result = $newContent->save();

                        if ($newContent->isModified() && $result == 0)
                        {
                            $rollBack = true;
                            break;
                        }
                    }

                    if($rollBack) break;
                }
            }

            if (!$rollBack)
            {
                $this->connection->commit();
                return true;
            }
            else
            {
                $this->connection->rollBack();
                return false;
            }
        }
        catch(\Exception $e)
        {
            if(isset($this->connection) && $this->connection !== null) $this->connection->rollBack();
            throw $e;
        }*/
    }
}