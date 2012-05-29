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


use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;
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
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\LanguageModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Language;

/**
 * Defines the language content manager object, that implements the methods to manage an AlLanguage object
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlLanguageManager extends AlContentManagerBase implements AlContentManagerInterface
{
    protected $alLanguage = null;
    protected $languageModel = null;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $dispatcher
     * @param LanguageModelInterface $languageModel
     * @param AlParametersValidatorLanguageManager $validator
     */
    public function __construct(EventDispatcherInterface $dispatcher, LanguageModelInterface $languageModel, AlParametersValidatorLanguageManager $validator = null)
    {
        parent::__construct($dispatcher, $validator);

        $this->languageModel = $languageModel;
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
     * @api
     * @param LanguageModelInterface $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager
     */
    public function setLanguageModel(LanguageModelInterface $v)
    {
        $this->languageModel = $v;

        return $this;
    }

    /**
     * Returns the block model object associated with this object
     *
     * @return LanguageModelInterface
     */
    public function getLanguageModel()
    {
        return $this->languageModel;
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

        if ($this->languageModel->mainLanguage() == 1) {
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

            $this->languageModel->startTransaction();
            $result = $this->languageModel
                            ->setModelObject($this->alLanguage)
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
                $this->languageModel->commit();

                if (null !== $this->dispatcher) {
                    $event = new  Content\Language\AfterLanguageDeletedEvent($this);
                    $this->dispatcher->dispatch(LanguageEvents::AFTER_DELETE_LANGUAGE, $event);
                }
            }
            else
            {
                $this->languageModel->rollBack();
            }

            return $result;
        }
        catch(\Exception $e) {
            if (isset($this->languageModel) && $this->languageModel !== null) {
                $this->languageModel->rollBack();
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
                throw new LanguageExistsException($this->translate("The language you are trying to add, already exists in the website."));
            }

            $result = true;
            $this->languageModel->startTransaction();

            $hasLanguages = $this->languageModel->activeLanguages();
            $values['MainLanguage'] = ($hasLanguages) ? (isset($values['MainLanguage'])) ? $values['MainLanguage'] : 0 : 1;
            if ($values['MainLanguage'] == 1 && $hasLanguages) $result = $this->resetMain();

            if ($result)
            {
                // Saves the language
                if (null === $this->alLanguage) {
                    $className = $this->languageModel->getModelObjectClassName();
                    $this->alLanguage = new $className();
                }

                $result = $this->languageModel
                            ->setModelObject($this->alLanguage)
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
                $this->languageModel->commit();

                if (null !== $this->dispatcher) {
                    $event = new  Content\Language\AfterLanguageAddedEvent($this);
                    $this->dispatcher->dispatch(LanguageEvents::AFTER_ADD_LANGUAGE, $event);
                }
            }
            else
            {
                $this->languageModel->rollBack();
            }

            return $result;
        }
        catch(\Exception $e)
        {
            if (isset($this->languageModel) && $this->languageModel !== null) {
                $this->languageModel->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Adds the page attribute and contents for the language identified by the given param
     *
     * @param int $idLanguage The current language's id
     * @return Boolean
     */
    protected function addPageAttributesAndBlocks($idLanguage)
    {
        try
        {
            $rollBack = false;
            $this->connection->beginTransaction();

            // Copies the page attributes to the new language
            $pagesAttributes = AlPageAttributeQuery::create()
                                //->setContainer($this->container)
                                ->fromLanguageId($idLanguage)
                                ->find();
            foreach($pagesAttributes as $pageAttributes)
            {
                $values = array('idPage' => $pageAttributes->getPageId(),
                                'idLanguage' => $this->alLanguage->getId(),
                                'permalink' => $pageAttributes->getPermalink(),
                                'languageName' => $this->alLanguage->getLanguage(),
                                'title' => $pageAttributes->getMetaTitle(),
                                'description' => $pageAttributes->getMetaDescription(),
                                'keywords' => $pageAttributes->getMetaKeywords());
                $this->pageAttributesManager->set(null);
                $rollBack = !$this->pageAttributesManager->save($values);
                if ($rollBack)
                {
                    return false;
                }
            }

            // Copies the contents to the new language
            $contents = AlBlockQuery::create()
                                //->setContainer($this->container)
                                ->fromLanguageId($idLanguage)
                                ->find();
            foreach($contents as $content)
            {
                //$newContent = new AlBlock();
                $values = $content->toArray();
                unset($values['Id']);
                unset($values['CreatedAt']);
                //$values['HtmlContent'] = $this->fixInternalLinks($values['HtmlContent']);
                $values['LanguageId'] = $this->alLanguage->getId();
                $this->blockManager->save($values);
                //$newContent->fromArray($values);
                //$newContent->setLanguageId($this->alLanguage->getId());
                //$newContent->save($this->connection);
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
     * Fixes all the internal links according with the new language
     *
     * @param string $content
     * @return string
     */
    protected function fixInternalLinks($content)
    {
        $container = $this->container;
        $languageName =  $this->alLanguage->getLanguage();
        $content = preg_replace_callback('/(\<a[\s+\w+]href=[\"\'])(.*?)([\"\'])/s', function ($matches) use($container, $languageName) {

            $url = $matches[2];
            try
            {
                $tmpUrl = (empty($match) && substr($url, 0, 1) != '/') ? '/' . $url : $url;
                $params = $container->get('router')->match($tmpUrl);

                $url = (!empty($params)) ? $languageName . '-' . $url : $url;
            }
            catch(\Symfony\Component\Routing\Exception\ResourceNotFoundException $ex)
            {
                // Not internal route the link remains the same
            }

            return $matches[1] . $url . $matches[3];
        }, $content);

        return $content;
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
            $this->languageModel->startTransaction();

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
                    $result = $this->languageModel
                                ->setModelObject($this->alLanguage)
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
                $this->languageModel->commit();

                if (null !== $this->dispatcher)
                {
                    $event = new  Content\Language\AfterLanguageEditedEvent($this);
                    $this->dispatcher->dispatch(LanguageEvents::AFTER_EDIT_LANGUAGE, $event);
                }
            }
            else
            {
                $this->languageModel->rollBack();
            }

            return $result;
        }
        catch(\Exception $e)
        {
            if (isset($this->languageModel) && $this->languageModel !== null)
            {
                $this->languageModel->rollBack();
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
            $language = $this->languageModel->mainLanguage();
            if (null !== $language)
            {
                $result = $this->languageModel
                            ->setModelObject($language)
                            ->save(array('IsHome' => 0));

                return $result;
            }

            return true;
        }
        catch(\Exception $e)
        {
            if (isset($this->languageModel) && $this->languageModel !== null) {
                $this->languageModel->rollBack();
            }

            throw $e;
        }
    }
}