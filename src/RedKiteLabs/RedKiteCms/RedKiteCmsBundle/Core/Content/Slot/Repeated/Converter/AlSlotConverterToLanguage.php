<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter;

class AlSlotConverterToLanguage extends AlSlotConverterBase
{
    /**
     * {@inheritdoc}
     *
     * @return null|boolean
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter\Exception
     *
     * @api
     */
    public function convert()
    {
        if (count($this->arrayBlocks) > 0) {
            try {
                $this->blockRepository->startTransaction();
                $result = $this->deleteBlocks();
                if (false !== $result) {
                    $languages = $this->languageRepository->activeLanguages();
                    foreach ($this->arrayBlocks as $block) {
                        foreach ($languages as $language) {
                            $result = $this->updateBlock($block, $language->getId(), 1);
                        }
                    }

                    if ($result) {
                        $this->blockRepository->commit();
                    } else {
                        $this->blockRepository->rollBack();
                    }
                }

                return $result;
            } catch (\Exception $e) {
                if (isset($this->blockRepository) && $this->blockRepository !== null) {
                    $this->blockRepository->rollBack();
                }

                throw $e;
            }
        }
    }
}
