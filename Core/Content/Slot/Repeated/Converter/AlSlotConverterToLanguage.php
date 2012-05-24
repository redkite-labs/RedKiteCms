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

class AlSlotConverterToLanguage extends AlSlotConverterBase
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
                $this->blockModel->startTransaction();
                $result = $this->deleteBlocks(); 
                if(false !== $result) {
                    $languages = $this->languageModel->activeLanguages();
                    foreach($this->arrayBlocks as $block)
                    {
                        foreach($languages as $language)
                        {
                            $result = $this->updateBlock($block, $language->getId(), 1);
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
                }
                
                return $result;
            }
            catch(\Exception $e)
            {
                if(isset($this->blocksModel) && $this->blocksModel !== null) {
                    $this->blocksModel->rollBack();
                }

                throw $e;
            }
        }
    }
}