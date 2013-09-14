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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Language;

use RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\AlContentManagerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Base\AlContentManagerBase;
use RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\LanguageEvents;
use RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorLanguageManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\Language\LanguageExistsException;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\Language;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface;

/**
 * AlLanguageManager is the base object that wraps an AlLanguage object
 *
 * AlLanguageManager manages an AlLanguage object, implementig the base methods to add, edit
 * and delete that kind of object.
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlLanguageManager extends AlContentManagerBase implements AlContentManagerInterface
{
    /**
     * @var \RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage
     */
    protected $alLanguage = null;

    /**
     * @var \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface
     */
    protected $factoryRepository = null;

    /**
     * @var RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface
     */
    protected $languageRepository = null;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface                 $eventsHandler
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface        $factoryRepository
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorLanguageManager $validator
     *
     * @api
     */
    public function __construct(AlEventsHandlerInterface $eventsHandler, AlFactoryRepositoryInterface $factoryRepository, AlParametersValidatorLanguageManager $validator = null)
    {
        parent::__construct($eventsHandler, $validator);

        $this->factoryRepository = $factoryRepository;
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function get()
    {
        return $this->alLanguage;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function set($object = null)
    {
        if (null !== $object && !$object instanceof AlLanguage) {
            throw new General\InvalidArgumentTypeException('exception_only_language_objects_are_accepted');
        }

        $this->alLanguage = $object;

        return $this;
    }

    /**
     * Sets the language model object
     *
     *
     * @param  LanguageRepositoryInterface                                             $v
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Language\AlLanguageManager
     *
     * @api
     */
    public function setLanguageRepository(LanguageRepositoryInterface $v)
    {
        $this->languageRepository = $v;

        return $this;
    }

    /**
     * Returns the block model object associated with this object
     *
     * @return LanguageRepositoryInterface
     *
     * @api
     */
    public function getLanguageRepository()
    {
        return $this->languageRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $parameters)
    {
        if (null === $this->alLanguage || $this->alLanguage->getId() == null) {
            return $this->add($parameters);
        }

        return $this->edit($parameters);
    }

    /**
     * {@inheritdoc}
     *
     * @return boolean
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\Language\RemoveMainLanguageException
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\Language\LanguageExistsException
     *
     * @api
     */
    public function delete()
    {
        if (null === $this->alLanguage) {
            throw new General\ArgumentIsEmptyException('exception_no_languages_selected_delete_skipped');
        }

        if ($this->alLanguage->getMainLanguage() == 1) {
            throw new Language\RemoveMainLanguageException('exception_website_main_languages_cannot_be_delete');
        }

        $this->dispatchBeforeOperationEvent(
                '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageDeletingEvent',
                LanguageEvents::BEFORE_DELETE_LANGUAGE,
                array(),
                'exception_language_deleting_aborted'
        );

        try {
            $this->languageRepository->startTransaction();
            $result = $this->languageRepository
                            ->setRepositoryObject($this->alLanguage)
                            ->delete();

            if ($result) {
                $eventName = LanguageEvents::BEFORE_DELETE_LANGUAGE_COMMIT;
                $result = !$this->eventsHandler
                                ->createEvent($eventName, '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeDeleteLanguageCommitEvent', array($this, array()))
                                ->dispatch()
                                ->getEvent($eventName)
                                ->isAborted();
            }

            if (false !== $result) {
                $this->languageRepository->commit();

                $this->eventsHandler
                     ->createEvent(LanguageEvents::AFTER_DELETE_LANGUAGE, '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\AfterLanguageDeletedEvent', array($this))
                     ->dispatch();
                     
                return $result;
            }
                
            $this->languageRepository->rollBack();

            return $result;
        } catch (\Exception $e) {
            if (isset($this->languageRepository) && $this->languageRepository !== null) {
                $this->languageRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Adds a new AlLanguage object from the given params
     *
     * @param  array                                                                                   $values
     * @return type
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\Language\LanguageExistsException
     * @throws LanguageExistsException
     * @throws General\ArgumentIsEmptyException
     * 
     * @api
     */
    protected function add(array $values)
    {
        $values =
                $this->dispatchBeforeOperationEvent(
                        '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent',
                        LanguageEvents::BEFORE_ADD_LANGUAGE,
                        $values,
                        array(
                            'message' => 'exception_language_adding_aborted',
                            'domain' => 'exceptions',
                        )
                );

        try {
            $this->validator->checkEmptyParams($values);
            $this->validator->checkRequiredParamsExists(array('LanguageName' => ''), $values);
            if ($this->validator->languageExists($values['LanguageName'])) {
                throw new Language\LanguageExistsException('exception_language_already_exists');
            }

            if (empty($values['LanguageName'])) {
                throw new General\ArgumentIsEmptyException('exception_null_language_name');
            }

            $result = true;
            $this->languageRepository->startTransaction();

            $hasLanguages = $this->validator->hasLanguages();
            $values['MainLanguage'] = ($hasLanguages) ? (isset($values['MainLanguage'])) ? $values['MainLanguage'] : 0 : 1;
            if ($values['MainLanguage'] == 1 && $hasLanguages) $result = $this->resetMain();

            if ($result) {
                // Saves the language
                if (null === $this->alLanguage) {
                    $className = $this->languageRepository->getRepositoryObjectClassName();
                    $this->alLanguage = new $className();
                }

                $result = $this->languageRepository
                            ->setRepositoryObject($this->alLanguage)
                            ->save($values);
                if (false !== $result) {
                    $eventName = LanguageEvents::BEFORE_ADD_LANGUAGE_COMMIT;
                    $result = !$this->eventsHandler
                                    ->createEvent($eventName, '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent', array($this, $values))
                                    ->dispatch()
                                    ->getEvent($eventName)
                                    ->isAborted();
                }
            }

            if (false !== $result) {
                $this->languageRepository->commit();

                $this->eventsHandler
                     ->createEvent(LanguageEvents::AFTER_ADD_LANGUAGE, '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\AfterLanguageAddedEvent', array($this))
                     ->dispatch();
                     
                return $result;
            }   
                     
            $this->languageRepository->rollBack();

            return $result;
        } catch (\Exception $e) {
            if (isset($this->languageRepository) && $this->languageRepository !== null) {
                $this->languageRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Edits the managed language object
     *
     * @param  array                                                                                   $values
     * @return type
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\Language\LanguageExistsException
     *
     * @api
     */
    protected function edit(array $values)
    {
        $values =
            $this->dispatchBeforeOperationEvent(
                    '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent',
                    LanguageEvents::BEFORE_EDIT_LANGUAGE,
                    $values,
                    array(
                        'message' => 'exception_language_editing_aborted',
                        'domain' => 'exceptions',
                    )
            );

        try {
            $this->validator->checkEmptyParams($values);
            $this->validator->checkOnceValidParamExists(array('LanguageName' => '', 'MainLanguage' => ''), $values);

            $result = true;
            $this->languageRepository->startTransaction();

            if (isset($values["MainLanguage"]) && $values["MainLanguage"] == 1) {
                if ($this->alLanguage->getMainLanguage() == 1) {
                    // If the language is declared as main, resets the previuos
                    $result = $this->resetMain();
                }
            } else {
                unset($values["MainLanguage"]);
            }

            if (false !== $result) {
                if ( ! empty($values['LanguageName']) && $this->alLanguage->getLanguageName() == $values['LanguageName']) {
                    unset($values['LanguageName']);
                }

                if (empty($values)) {
                    $this->languageRepository->rollBack();
                    
                    return false;
                }
                
                $result = $this->languageRepository
                    ->setRepositoryObject($this->alLanguage)
                    ->save($values)
                ;

                if (false != $result) {
                    $eventName = LanguageEvents::BEFORE_EDIT_LANGUAGE_COMMIT;
                    $result = !$this->eventsHandler
                        ->createEvent($eventName, '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeEditLanguageCommitEvent', array($this, $values))
                        ->dispatch()
                        ->getEvent($eventName)
                        ->isAborted()
                    ;
                }
            }

            if (false !== $result) {
                $this->languageRepository->commit();

                $this->eventsHandler
                     ->createEvent(LanguageEvents::AFTER_EDIT_LANGUAGE, '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\AfterLanguageEditedEvent', array($this))
                     ->dispatch();
                     
                return $result;
            }
                
            $this->languageRepository->rollBack();

            return $result;
        } catch (\Exception $e) {
            if (isset($this->languageRepository) && $this->languageRepository !== null) {
                $this->languageRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Degrades the main language to normal language
     *
     * @return boolean
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\Language\LanguageExistsException
     *
     * @api
     */
    protected function resetMain()
    {
        try {
            $language = $this->languageRepository->mainLanguage();
            if (null !== $language) {
                $result = $this->languageRepository
                            ->setRepositoryObject($language)
                            ->save(array('MainLanguage' => 0));

                return $result;
            }

            return true;
        } catch (\Exception $e) {
            if (isset($this->languageRepository) && $this->languageRepository !== null) {
                $this->languageRepository->rollBack();
            }

            throw $e;
        }
    }
}
