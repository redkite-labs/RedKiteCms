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
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Base;

use RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidator;
use Symfony\Component\Translation\TranslatorInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Event\EventAbortedException;

/**
 * The base class that defines a content manager object
 *
 * Several entities are considered "content" by AlphaLemon CMS:
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
abstract class AlContentManagerBase
{
    protected $eventsHandler;
    protected $validator;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface           $eventsHandler
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(AlEventsHandlerInterface $eventsHandler = null, AlParametersValidatorInterface $validator = null)
    {
        $this->eventsHandler = $eventsHandler;
        $this->validator = (null === $validator) ? new AlParametersValidator() : $validator;
    }

    /**
     * Sets the event dispatcher object
     *
     * @param  \RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface $eventsHandler
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Base\AlContentManagerBase
     *
     * @api
     */
    public function setEventsHandler(AlEventsHandlerInterface $eventsHandler)
    {
        $this->eventsHandler = $eventsHandler;

        return $this;
    }

    /**
     * Sets the parameters validator object
     *
     * @param  \RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Base\AlContentManagerBase
     *
     * @api
     */
    public function setValidator(AlParametersValidatorInterface $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Returns the Event dispatcher object
     *
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface
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
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface
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
