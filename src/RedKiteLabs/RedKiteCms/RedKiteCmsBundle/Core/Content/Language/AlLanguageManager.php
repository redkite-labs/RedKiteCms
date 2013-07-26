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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\LanguageEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Language\LanguageExistsException;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Language;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface;

/**
 * AlLanguageManager is the base object that wraps an AlLanguage object
 *
 * AlLanguageManager manages an AlLanguage object, implementig the base methods to add, edit
 * and delete that kind of object.
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
class AlLanguageManager extends AlContentManagerBase implements AlContentManagerInterface
{
    /**
     * @var \AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage
     */
    protected $alLanguage = null;

    /**
     * @var \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface
     */
    protected $factoryRepository = null;

    /**
     * @var AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface
     */
    protected $languageRepository = null;

    /**
     * Constructor
     *
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface                 $eventsHandler
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface        $factoryRepository
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorLanguageManager $validator
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
            throw new General\InvalidArgumentTypeException('AlLanguageManager is able to manage only AlLanguage objects');
        }

        $this->alLanguage = $object;

        return $this;
    }

    /**
     * Sets the language model object
     *
     *
     * @param  LanguageRepositoryInterface                                             $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager
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
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Language\RemoveMainLanguageException
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Language\LanguageExistsException
     *
     * @api
     */
    public function delete()
    {
        if (null === $this->alLanguage) {
            throw new General\ArgumentIsEmptyException('Any language has been selected: delete operation aborted');
        }

        if ($this->alLanguage->getMainLanguage() == 1) {
            throw new Language\RemoveMainLanguageException('The website main language cannot be deleted. To delete this language promote another one as main language, then delete it again');
        }

        $this->dispatchBeforeOperationEvent(
                '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeLanguageDeletingEvent',
                LanguageEvents::BEFORE_DELETE_LANGUAGE,
                array(),
                'The language deleting action has been aborted'
        );

        try {
            $this->languageRepository->startTransaction();
            $result = $this->languageRepository
                            ->setRepositoryObject($this->alLanguage)
                            ->delete();

            if ($result) {
                $eventName = LanguageEvents::BEFORE_DELETE_LANGUAGE_COMMIT;
                $result = !$this->eventsHandler
                                ->createEvent($eventName, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeDeleteLanguageCommitEvent', array($this, array()))
                                ->dispatch()
                                ->getEvent($eventName)
                                ->isAborted();
            }

            if (false !== $result) {
                $this->languageRepository->commit();

                $this->eventsHandler
                     ->createEvent(LanguageEvents::AFTER_DELETE_LANGUAGE, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\AfterLanguageDeletedEvent', array($this))
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
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Language\LanguageExistsException
     * @throws LanguageExistsException
     * @throws General\ArgumentIsEmptyException
     * 
     * @api
     */
    protected function add(array $values)
    {
        $values =
                $this->dispatchBeforeOperationEvent(
                        '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeLanguageAddingEvent',
                        LanguageEvents::BEFORE_ADD_LANGUAGE,
                        $values,
                        array(
                            'message' => 'The language adding action has been aborted',
                            'domain' => 'exceptions',
                        )
                );

        try {
            $this->validator->checkEmptyParams($values);
            $this->validator->checkRequiredParamsExists(array('LanguageName' => ''), $values);
            if ($this->validator->languageExists($values['LanguageName'])) {
                throw new Language\LanguageExistsException('The language you are trying to add, already exists in the website');
            }

            if (empty($values['LanguageName'])) {
                throw new General\ArgumentIsEmptyException('A language cannot be null. Please provide a valid language name to add the language');
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
                                    ->createEvent($eventName, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent', array($this, $values))
                                    ->dispatch()
                                    ->getEvent($eventName)
                                    ->isAborted();
                }
            }

            if (false !== $result) {
                $this->languageRepository->commit();

                $this->eventsHandler
                     ->createEvent(LanguageEvents::AFTER_ADD_LANGUAGE, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\AfterLanguageAddedEvent', array($this))
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
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Language\LanguageExistsException
     *
     * @api
     */
    protected function edit(array $values)
    {
        $values =
            $this->dispatchBeforeOperationEvent(
                    '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeLanguageEditingEvent',
                    LanguageEvents::BEFORE_EDIT_LANGUAGE,
                    $values,
                    array(
                        'message' => 'The language editing action has been aborted',
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
                        ->createEvent($eventName, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeEditLanguageCommitEvent', array($this, $values))
                        ->dispatch()
                        ->getEvent($eventName)
                        ->isAborted()
                    ;
                }
            }

            if (false !== $result) {
                $this->languageRepository->commit();

                $this->eventsHandler
                     ->createEvent(LanguageEvents::AFTER_EDIT_LANGUAGE, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\AfterLanguageEditedEvent', array($this))
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
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Language\LanguageExistsException
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
