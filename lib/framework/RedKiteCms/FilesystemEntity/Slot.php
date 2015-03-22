<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\FilesystemEntity;

use JMS\Serializer\SerializerInterface;
use RedKiteCms\Contribution\ContributionManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Slot is the object deputed to handle a website slot
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\FilesystemEntity
 */
class Slot extends Entity implements RenderableInterface
{
    /**
     * @type string
     */
    private $slotName = null;
    /**
     * @type string
     */
    private $username = null;
    /**
     * @type string
     */
    private $sourceDir = null;
    /**
     * @type \RedKiteCms\FilesystemEntity\SlotParser
     */
    private $slotParser;

    /**
     * Constructor
     *
     * @param \JMS\Serializer\SerializerInterface $serializer
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $optionsResolver
     * @param \RedKiteCms\FilesystemEntity\SlotParser $slotParser
     */
    public function __construct(
        SerializerInterface $serializer,
        OptionsResolver $optionsResolver,
        SlotParser $slotParser
    ) {
        parent::__construct($serializer, $optionsResolver);

        $this->slotParser = $slotParser;
    }

    /**
     * Returns the slot name
     *
     * @return string
     */
    public function getSlotName()
    {
        return $this->slotName;
    }

    /**
     * Refreshes the slot entity
     */
    public function refresh()
    {
        if (null === $this->options || null === $this->sourceDir) {
            return;
        }

        $this->render($this->sourceDir, $this->options, $this->username);
    }

    /**
     * {@inheridoc}
     */
    public function render($sourceDir, array $options, $username = null)
    {
        $this->sourceDir = $sourceDir;
        $this->options = $options;
        $this->username = $username;
        $this->slotName = $options["slot"];
        $this->init($sourceDir, $options, $username);

        $this->productionEntities = $this->slotParser->fetchBlocks(
            $this->productionDir,
            $this->productionDir,
            $this->slotName
        );
        $this->contributorEntities = $this->slotParser->fetchBlocks(
            $this->productionDir,
            $this->contributorDir,
            $this->slotName
        );
    }
}