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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Base;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\EventsHandler\EventsHandlerInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidator;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Event\EventAbortedException;

/**
 * The base class that defines a content manager object
 *
 * Several entities are considered "content" by RedKiteCms:
 *
 *   - Languages
 *   - Pages
 *   - Seo attributes
 *   - Templates
 *   - Slots
 *   - Blocks
 *
 * All of them extends this class
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class ContentManagerBase
{
    protected $eventsHandler;
    protected $validator;

    /**
     * Constructor
     *
     * @param EventsHandlerInterface       $eventsHandler
     * @param ParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(EventsHandlerInterface $eventsHandler = null, ParametersValidatorInterface $validator = null)
    {
        $this->eventsHandler = $eventsHandler;
        $this->validator = (null === $validator) ? new ParametersValidator() : $validator;
    }

    /**
     * Sets the event dispatcher object
     *
     * @param  EventsHandlerInterface $eventsHandler
     * @return ContentManagerBase
     *
     * @api
     */
    public function setEventsHandler(EventsHandlerInterface $eventsHandler)
    {
        $this->eventsHandler = $eventsHandler;

        return $this;
    }

    /**
     * Sets the parameters validator object
     *
     * @param  ParametersValidatorInterface $validator
     * @return ContentManagerBase
     *
     * @api
     */
    public function setValidator(ParametersValidatorInterface $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Returns the Event dispatcher object
     *
     * @return EventsHandlerInterface
     *
     * @api
     */
    public function getEventsHandler()
    {
        return $this->eventsHandler;
    }

    /**
     * Returns the ParameterValidator object
     *
     * @return ParametersValidatorInterface
     *
     * @api
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Dispatches a BeforeAction[*] event type
     *
     * @param  string                $eventClass
     * @param  string                $eventName
     * @param  array                 $values
     * @param  string                $exceptionMessage
     * @return array
     * @throws EventAbortedException
     */
    protected function dispatchBeforeOperationEvent($eventClass, $eventName, array $values, $exceptionMessage)
    {
        $event = $this->eventsHandler->createEvent($eventName, $eventClass, array($this, $values))
                                     ->dispatch()
                                     ->getEvent($eventName);

        if ($event->isAborted()) {
            if (is_array($exceptionMessage)) {
                $exceptionMessage = json_encode($exceptionMessage);
            }

            throw new EventAbortedException($exceptionMessage);
        }

        if (empty($values)) return $values;

        $changedValues = $event->getValues();
        if (null !== $changedValues) { //
            $values = $changedValues;
        }

        return $values;
    }
}
