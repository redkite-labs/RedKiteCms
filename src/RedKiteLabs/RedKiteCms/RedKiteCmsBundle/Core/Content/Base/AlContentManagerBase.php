<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base;

use AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidator;
use AlphaLemon\AlphaLemonCmsBundle\Core\Translator\AlTranslator;
use Symfony\Component\Translation\TranslatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event\EventAbortedException;

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
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlContentManagerBase
{
    protected $eventsHandler;
    protected $validator;

    /**
     * Constructor
     *
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface $eventsHandler
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
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
     * @param  \AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface $eventsHandler
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase
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
     * @param  \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase
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
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface
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
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface
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
     * @param string $eventClass
     * @param string $eventName
     * @param array $values
     * @param string $exceptionMessage
     * @return array
     * @throws EventAbortedException
     */
    protected function dispatchBeforeOperationEvent($eventClass, $eventName, array $values, $exceptionMessage)
    {
        $event = $this->eventsHandler->createEvent($eventName, $eventClass, array($this, $values))
                                     ->dispatch()
                                     ->getEvent($eventName);

        if ($event->isAborted()) {
            throw new EventAbortedException(json_encode($exceptionMessage));
        }

        if (empty($values)) return $values;

        $changedValues = $event->getValues();
        if (null !== $changedValues) { //
            $values = $changedValues;
        }

        return $values;
    }
}
