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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Template;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\EventsHandler\EventsHandlerInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManagerFactoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Base\ContentManagerBase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorInterface;

/**
 * Implements the base object that defines a template
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
abstract class TemplateBase extends ContentManagerBase
{
    /** @var BlockManagerFactoryInterface */
    protected $blockManagerFactory;

    /**
     * Constructor
     *
     * @param EventsHandlerInterface       $eventsHandler
     * @param BlockManagerFactoryInterface $blockManagerFactory
     * @param ParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(EventsHandlerInterface $eventsHandler, BlockManagerFactoryInterface $blockManagerFactory, ParametersValidatorInterface $validator = null)
    {
        parent::__construct($eventsHandler, $validator);

        $this->blockManagerFactory = $blockManagerFactory;
    }

    /**
     * Sets the blockManager factory object
     *
     * @param  BlockManagerFactoryInterface $blockManagerFactory
     * @return self
     *
     * @api
     */
    public function setBlockManagerFactory(BlockManagerFactoryInterface $blockManagerFactory)
    {
        $this->blockManagerFactory = $blockManagerFactory;

        return $this;
    }

    /**
     * Returns the blockManager factory object
     *
     * @return BlockManagerFactoryInterface
     *
     * @api
     */
    public function getBlockManagerFactory()
    {
        return $this->blockManagerFactory;
    }
}
