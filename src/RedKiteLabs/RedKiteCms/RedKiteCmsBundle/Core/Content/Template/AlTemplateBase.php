<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Template;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Base\AlContentManagerBase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * Implements the base object that defines a template
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
abstract class AlTemplateBase extends AlContentManagerBase
{
    /** @var AlBlockManagerFactoryInterface */
    protected $blockManagerFactory;

    /**
     * Constructor
     *
     * @param AlEventsHandlerInterface       $eventsHandler
     * @param AlBlockManagerFactoryInterface $blockManagerFactory
     * @param AlParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(AlEventsHandlerInterface $eventsHandler, AlBlockManagerFactoryInterface $blockManagerFactory, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($eventsHandler, $validator);

        $this->blockManagerFactory = $blockManagerFactory;
    }

    /**
     * Sets the blockManager factory object
     *
     * @param  AlBlockManagerFactoryInterface $blockManagerFactory
     * @return self
     *
     * @api
     */
    public function setBlockManagerFactory(AlBlockManagerFactoryInterface $blockManagerFactory)
    {
        $this->blockManagerFactory = $blockManagerFactory;

        return $this;
    }

    /**
     * Returns the blockManager factory object
     *
     * @return AlBlockManagerFactoryInterface
     *
     * @api
     */
    public function getBlockManagerFactory()
    {
        return $this->blockManagerFactory;
    }
}
