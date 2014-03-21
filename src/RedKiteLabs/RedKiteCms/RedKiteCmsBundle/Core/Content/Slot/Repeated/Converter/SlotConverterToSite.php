<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Repeated\Converter;

/**
 * Converts a slot to site repeated status
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class SlotConverterToSite extends SlotConverterBase
{
    /**
     * {@inheritdoc}
     *
     * @return null|boolean
     * @throws \Exception
     *
     * @api
     */
    public function convert()
    {
        if (count($this->arrayBlocks) <= 0) {
            return null;
        }
        try {
            $this->blockRepository->startTransaction();
            $result = $this->deleteBlocks();
            if (false !== $result) {
                foreach ($this->arrayBlocks as $block) {
                    $result = $this->updateBlock($block, 1, 1);
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
