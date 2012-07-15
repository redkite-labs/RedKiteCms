<?php
/*
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


use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\LanguageEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content;
use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageAttributes\AlPageAttributesManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Language\LanguageExistsException;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Language;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface;

/**
 * Defines the language content manager object, that implements the methods to manage an AlLanguage object
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlLanguageManager extends AlContentManagerBase implements AlContentManagerInterface
{
    protected $alLanguage = null;
    protected $factoryRepository = null;
    protected $languageRepository = null;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $dispatcher
     * @param AlFactoryRepositoryInterface $factoryRepository
     * @param AlParametersValidatorLanguageManager $validator
     */
    public function __construct(EventDispatcherInterface $dispatcher, AlFactoryRepositoryInterface $factoryRepository, AlParametersValidatorLanguageManager $validator = null)
    {
        parent::__construct($dispatcher, $validator);

        $this->factoryRepository = $factoryRepository;
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->alLanguage;
    }

    /**
     * {@inheritdoc}
     */
    public function set($object = null)
    {
        if (null !== $object && !$object instanceof AlLanguage) {
            throw new General\InvalidParameterTypeException('AlLanguageManager is only able to manage only AlLanguage objects');
        }

        $this->alLanguage = $object;

        return $this;
    }

    /**
     * Sets the language model object
     *
     *
     * @param LanguageRepositoryInterface $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager
     */
    public function setLanguageModel(LanguageRepositoryInterface $v)
    {
        $this->languageRepository = $v;

        return $this;
    }

    /**
     * Returns the block model object associated with this object
     *
     * @return LanguageRepositoryInterface
     */
    public function getLanguageModel()
    {
        return $this->languageRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $parameters)
    {
        if (null === $this->alLanguage || $this->alLanguage->getId() == null)
        {
            return $this->add($parameters);
        }
        else
        {
            return $this->edit($parameters);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        if (null === $this->alLanguage) {
            throw new General\ParameterIsEmptyException($this->translate("Any language has been assigned to the LanguageManager. Delete operation aborted", array(), 'al_language_manager_exceptions'));
        }

        if ($this->alLanguage->getMainLanguage() == 1) {
            throw new Language\RemoveMainLanguageException($this->translate("The website main language cannot be deleted. To delete this language promote another one as main language, then delete it again", array(), 'al_language_manager_exceptions'));
        }

        try
        {
            if (null !== $this->dispatcher) {
                $event = new  Content\Language\BeforeLanguageDeletingEvent($this);
                $this->dispatcher->dispatch(LanguageEvents::BEFORE_DELETE_LANGUAGE, $event);

                if ($event->isAborted())
                {
                    throw new \RuntimeException($this->translate("The language deleting action has been aborted", array(), 'al_language_manager_exceptions'));
                }
            }

            $this->languageRepository->startTransaction();
            $result = $this->languageRepository
                            ->setRepositoryObject($this->alLanguage)
                            ->delete();

            if ($result) {
                if (null !== $this->dispatcher) {
                    $event = new  Content\Language\BeforeDeleteLanguageCommitEvent($this);
                    $this->dispatcher->dispatch(LanguageEvents::BEFORE_DELETE_LANGUAGE_COMMIT, $event);

                    if ($event->isAborted()) {
                        $result = false;
                    }
                }
            }

            if ($result)
            {
                $this->languageRepository->commit();

                if (null !== $this->dispatcher) {
                    $event = new  Content\Language\AfterLanguageDeletedEvent($this);
                    $this->dispatcher->dispatch(LanguageEvents::AFTER_DELETE_LANGUAGE, $event);
                }
            }
            else
            {
                $this->languageRepository->rollBack();
            }

            return $result;
        }
        catch(\Exception $e) {
            if (isset($this->languageRepository) && $this->languageRepository !== null) {
                $this->languageRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Adds a new AlLanguage object from the given params
     *
     * @param array $values
     * @return boolean
     * @throws Exception
     * @throws \RuntimeException
     * @throws LanguageExistsException
     */
    protected function add(array $values)
    {
        try
        {
            if (null !== $this->dispatcher) {
                $event = new  Content\Language\BeforeLanguageAddingEvent($this, $values);
                $this->dispatcher->dispatch(LanguageEvents::BEFORE_ADD_LANGUAGE, $event);

                if ($event->isAborted())
                {
                    throw new \RuntimeException($this->translate("The language adding action has been aborted", array(), 'al_language_manager_exceptions'));
                }

                if ($values !== $event->getValues())
                {
                    $values = $event->getValues();
                }
            }

            $this->validator->checkEmptyParams($values);
            $this->validator->checkRequiredParamsExists(array('Language' => ''), $values);
            if($this->validator->languageExists($values["Language"])) {
                throw new LanguageExistsException($this->translate("The language you are trying to add, already exists in the website"));
            }

            if (empty($values['Language'])) {
                throw new General\ParameterIsEmptyException($this->translate("A language cannot be null. Please provide a valid language name to add the language"));
            }

            $result = true;
            $this->languageRepository->startTransaction();

            $hasLanguages = $this->validator->hasLanguages();
            $values['MainLanguage'] = ($hasLanguages) ? (isset($values['MainLanguage'])) ? $values['MainLanguage'] : 0 : 1;
            if ($values['MainLanguage'] == 1 && $hasLanguages) $result = $this->resetMain();

            if ($result)
            {
                // Saves the language
                if (null === $this->alLanguage) {
                    $className = $this->languageRepository->getRepositoryObjectClassName();
                    $this->alLanguage = new $className();
                }

                $result = $this->languageRepository
                            ->setRepositoryObject($this->alLanguage)
                            ->save($values);
                if ($result) {
                    if (null !== $this->dispatcher) {
                        $event = new Content\Language\BeforeAddLanguageCommitEvent($this, $values);
                        $this->dispatcher->dispatch(LanguageEvents::BEFORE_ADD_LANGUAGE_COMMIT, $event);

                        if ($event->isAborted()) {
                            $result = false;
                        }
                    }
                }
            }

            if ($result)
            {
                $this->languageRepository->commit();

                if (null !== $this->dispatcher) {
                    $event = new  Content\Language\AfterLanguageAddedEvent($this);
                    $this->dispatcher->dispatch(LanguageEvents::AFTER_ADD_LANGUAGE, $event);
                }
            }
            else
            {
                $this->languageRepository->rollBack();
            }

            return $result;
        }
        catch(\Exception $e)
        {
            if (isset($this->languageRepository) && $this->languageRepository !== null) {
                $this->languageRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Edits the managed language object
     *
     * @param array $values
     * @return boolean
     * @throws Exception
     * @throws \RuntimeException
     */
    protected function edit(array $values)
    {
        try
        {
            if (null !== $this->dispatcher)
            {
                $event = new  Content\Language\BeforeLanguageEditingEvent($this, $values);
                $this->dispatcher->dispatch(LanguageEvents::BEFORE_EDIT_LANGUAGE, $event);

                if ($event->isAborted())
                {
                    throw new \RuntimeException($this->translate("The language editing action has been aborted", array(), 'al_language_manager_exceptions'));
                }

                if ($values !== $event->getValues())
                {
                    $values = $event->getValues();
                }
            }

            $this->validator->checkEmptyParams($values);
            $this->validator->checkOnceValidParamExists(array('Language' => '', 'MainLanguage' => ''), $values);

            $result = true;
            $this->languageRepository->startTransaction();

            if (isset($values["MainLanguage"]) && $values["MainLanguage"] == 1)
            {
                if ($this->alLanguage->getMainLanguage() == 1)
                {
                    // If the language is declared as main, resets the previuos
                    $result = $this->resetMain();
                }
            }
            else {
                unset($values["MainLanguage"]);
            }

            if ($result)
            {
                if (!empty($values["Language"]) && $this->alLanguage->getLanguage() == $values["Language"])
                {
                    unset($values["Language"]);
                }

                if (!empty($values)) {
                    $result = $this->languageRepository
                                ->setRepositoryObject($this->alLanguage)
                                ->save($values);
                }
                else {
                    $result = false;
                }

                if ($result && null !== $this->dispatcher) {
                    $event = new  Content\Language\BeforeEditLanguageCommitEvent($this, $values);
                    $this->dispatcher->dispatch(LanguageEvents::BEFORE_EDIT_LANGUAGE_COMMIT, $event);

                    if ($event->isAborted()) {
                        $result = false;
                    }
                }
            }

            if ($result)
            {
                $this->languageRepository->commit();

                if (null !== $this->dispatcher)
                {
                    $event = new  Content\Language\AfterLanguageEditedEvent($this);
                    $this->dispatcher->dispatch(LanguageEvents::AFTER_EDIT_LANGUAGE, $event);
                }
            }
            else
            {
                $this->languageRepository->rollBack();
            }

            return $result;
        }
        catch(\Exception $e)
        {
            if (isset($this->languageRepository) && $this->languageRepository !== null)
            {
                $this->languageRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Deletes the blocks and page attributes for the current language
     *
     * @return type
     */
    protected function deleteBlocksAndPageAttributes()
    {
        try
        {
            $rollBack = false;
            $this->connection->beginTransaction();

            $contents = AlBlockQuery::create()
                        ->setContainer($this->container)
                        ->fromLanguageId($this->alLanguage->getId())
                        ->find();
            foreach($contents as $content)
            {
                $content->setToDelete(1);
                $result = $content->save();
                if ($content->isModified() && $result == 0)
                {
                    $rollBack = true;
                    break;
                }
            }

            $pageAttributes = AlPageAttributeQuery::create()
                                ->setContainer($this->container)
                                ->fromLanguageId($this->alLanguage->getId())
                                ->find();
            foreach($pageAttributes as $pageAttribute)
            {
                $pageAttribute->setToDelete(1);
                $result = $pageAttribute->save();
                if ($pageAttribute->isModified() && $result == 0)
                {
                    $rollBack = true;
                    break;
                }
            }

            if (!$rollBack)
            {
                $this->connection->commit();
                return true;
            }
            else
            {
                $this->connection->rollBack();
                return false;
            }
        }
        catch(\Exception $e)
        {
            if (isset($this->connection) && $this->connection !== null) $this->connection->rollBack();
            throw $e;
        }
    }

    /**
     * Degrades the main language to normal
     *
     * @return boolean
     * @throws Exception
     */
    protected function resetMain()
    {
        try
        {
            $language = $this->languageRepository->mainLanguage();
            if (null !== $language)
            {
                $result = $this->languageRepository
                            ->setRepositoryObject($language)
                            ->save(array('MainLanguage' => 0));

                return $result;
            }

            return true;
        }
        catch(\Exception $e)
        {
            if (isset($this->languageRepository) && $this->languageRepository !== null) {
                $this->languageRepository->rollBack();
            }

            throw $e;
        }
    }
}