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

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlPageQuery;

class AlSlotConverterToPage extends AlSlotConverterBase
{ 
    /**
     * {inheritdoc}
     * 
     * @return null|boolean
     * @throws Exception 
     */
    public function convert()
    {
        if(count($this->arrayBlocks) > 0)
        {
            try
            {
                $this->blockRepository->startTransaction();
                $result = $this->deleteBlocks(); 
                if(false !== $result) {
                    $languages = $this->languageRepository->activeLanguages();
                    $pages = $this->pageRepository->activePages();
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
                        $this->blockRepository->commit();
                    }
                    else
                    {
                        $this->blockRepository->rollBack();
                    }
                }
                return $result;
            }
            catch(\Exception $e)
            {
                if(isset($this->blockRepository) && $this->blockRepository !== null) {
                    $this->blockRepository->rollBack();
                }

                throw $e;
            }
        }
    }
}