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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\BlockEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface;

/**
 * AlBlockManager is the object responsible to manage an AlBlock object.
 *
 *
 * AlBlockManager manages an AlBlock object, implementig the base methods to add, edit and delete
 * that kind of object and provides several methods to change the behavior of the block itself,
 * when it is rendered on the page.
 *
 * Every new block content must inherit from this class.
 *
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlBlockManager extends AlContentManagerBase implements AlContentManagerInterface, AlBlockManagerInterface
{
    const EDITOR_WIDTH = 800;

    protected $alBlock = null;
    protected $factoryRepository = null;
    protected $blockRepository = null;

    /**
     * Constructor
     *
     * @param AlEventsHandlerInterface       $eventsHandler
     * @param AlFactoryRepositoryInterface   $factoryRepository
     * @param AlParametersValidatorInterface $validator
     */
    public function __construct(AlEventsHandlerInterface $eventsHandler, AlFactoryRepositoryInterface $factoryRepository = null, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($eventsHandler, $validator);

        $this->doSetFactoryRepository($factoryRepository);
    }

    /**
     * Defines the default value of the managed block
     *
     *
     * Returns an array which may contain one or more of these keys:
     *
     *   - *Content*            The html content displayed on the page
     *   - *ExternalJavascript*     A comma separated external javascripts files
     *   - *InternalJavascript*     A javascript code
     *   - *ExternalStylesheet*     A comma separated external stylesheets files
     *   - *InternalStylesheet*     A stylesheet code
     *
     *
     * @return array
     */
    abstract public function getDefaultValue();

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->alBlock;
    }

    /**
     * {@inheritdoc}
     */
    public function set($object = null)
    {
        if (null !== $object && !$object instanceof AlBlock) {
            throw new InvalidParameterTypeException('AlBlockManager is only able to manage AlBlock objects');
        }

        $this->alBlock = $object;

        return $this;
    }

    /**
     * Sets the factory repository
     *
     * @param  AlFactoryRepositoryInterface                                      $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager
     */
    public function setFactoryRepository(AlFactoryRepositoryInterface $v)
    {
        $this->doSetFactoryRepository($v);

        return $this;
    }

    /**
     * Returns the factory repository object associated with this object
     *
     * @return BlockRepositoryInterface
     */
    public function getFactoryRepository()
    {
        return $this->factoryRepository;
    }

    /**
     * Returns the block repository object associated with this object
     *
     * @return BlockRepositoryInterface
     */
    public function getBlockRepository()
    {
        return $this->blockRepository;
    }

    /**
     * Defines when a content is rendered or not in edit mode.
     *
     *
     * By default the content is rendered when the edit mode is active. To hide the content, simply override
     * this method and return true
     *
     *
     * @return Boolean
     */
    public function getHideInEditMode()
    {
        return false;
    }

    /**
     * Displays a message inside the editor to suggest a page relead
     *
     * Return true to display a warnig on editor that suggest the used to reload the page when the block is added or edited
     *
     *
     * @return Boolean
     */
    public function getReloadSuggested()
    {
        return false;
    }

    /**
     * When true, attaches the internal javascript code to html when the editor is active
     *
     * Return false to avoid AlphaLemon add the internal javascript code to html when the content is displayed on the
     * web page
     *
     *
     * @return Boolean
     */
    public function getExecuteInternalJavascript()
    {
        return true;
    }

    /**
     * Returns the block's html content
     *
     * @return string
     */
    public function getHtml()
    {
        return (null !== $this->alBlock) ? $this->alBlock->getContent() : "";
    }

    /**
     * Returns the html content when AlphaLemon CMS is active.
     *
     * By default the internal javascript is concatenated to the block's html content: this behavior
     * can be changed overriding the getExecuteInternalJavascript() method.
     *
     *
     * @return string
     */
    final public function getHtmlCmsActive()
    {
        $content = $this->formatHtmlCmsActive();
        if(null === $content) $content = $this->getHtml();

        // Attaches the content a javascript code that saves the block's content to restore it when block must be hidden
        $scriptForHideContents = ($this->getHideInEditMode()) ? sprintf("$('#block_%s').data('block', '%s');", $this->alBlock->getId(), rawurlencode($content)) : '';
        $internalJavascript = (string)$this->getInternalJavascript();
        $internalJavascript = ($internalJavascript != "" && $this->getExecuteInternalJavascript()) ? $internalJavascript : '';
        if ($scriptForHideContents != '' || $internalJavascript != '') {
            $content .= sprintf('<script type="text/javascript">$(document).ready(function(){%s%s});</script>', $scriptForHideContents, $internalJavascript);
        }

        return $content;
    }

    /**
     * Returns the content displayed in the editor
     *
     * The editor that manages the content gets the content saved into the database.
     * Override this method to change the content to display
     *
     *
     * @return string
     */
    public function getContentForEditor()
    {
        return $this->getHtml();
    }

    /**
     * Returns the current saved ExternalJavascript value
     *
     *
     * @return array
     */
    public function getExternalJavascript()
    {
        if (null !== $this->alBlock) {
            $javascripts = trim($this->alBlock->getExternalJavascript());
        }

        return ($javascripts != "") ? explode(',', $javascripts) : array();
    }

    /**
     * Returns the current saved ExternalStylesheet value
     *
     *
     * @return array
     */
    public function getExternalStylesheet()
    {
        if (null !== $this->alBlock) {
            $stylesheets = trim($this->alBlock->getExternalStylesheet());
        }

        return ($stylesheets != "") ? explode(',', $stylesheets) : array();
    }

    /**
     * Returns the current saved InternalJavascript.
     *
     * By default the values is encapsulated into a try/catch block to avoid breaking the execution.
     * To get only the internal javascript, call the method with the safe argument as false
     *
     *
     * @param boolean
     * @return string
     */
    public function getInternalJavascript($safe = true)
    {
        $internalJavascript = '';
        if (null === $this->alBlock) return $internalJavascript;

        $savedJavascript = $this->alBlock->getInternalJavascript();
        if (null !== $this->alBlock && trim($savedJavascript) != '') {
            $internalJavascript = $savedJavascript;
            if ($safe) {
                $safeInternalJavascript = "try {\n";
                $safeInternalJavascript .= $savedJavascript;
                $safeInternalJavascript .= "\n} catch (e) {\n";
                $safeInternalJavascript .= sprintf("alert('The javascript added to the slot %s has been generated an error, which reports: ' + e);\n", $this->alBlock->getSlotName());
                $safeInternalJavascript .= "}\n";

                return $safeInternalJavascript;
            }
        }

        return $internalJavascript;
    }

    /**
     * Returns the current saved InternalStylesheet
     *
     *
     * @return string
     */
    public function getInternalStylesheet()
    {
        return (null !== $this->alBlock) ? $this->alBlock->getInternalStylesheet() : "";
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $parameters)
    {
        if (null === $this->alBlock || $this->alBlock->getId() == null) {
            return $this->add($parameters);
        } else {
            return $this->edit($parameters);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        if (null === $this->alBlock) {
            throw new General\ParameterIsEmptyException($this->translate("Any valid block has been setted. Nothing to delete", array()));
        }

        $this->dispatchBeforeOperationEvent(
                '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Block\BeforeBlockDeletingEvent',
                BlockEvents::BEFORE_DELETE_BLOCK,
                array(),
                "The content deleting action has been aborted"
        );

        try {
            $this->blockRepository->startTransaction();

            $result = $this->blockRepository
                        ->setRepositoryObject($this->alBlock)
                        ->delete();
            if ($result) {
                $this->blockRepository->commit();
                $this->eventsHandler
                     ->createEvent(BlockEvents::AFTER_DELETE_BLOCK, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Block\AfterBlockDeletedEvent', array($this))
                     ->dispatch();

                return true;
            } else {
                $this->blockRepository->rollBack();

                return false;
            }
        } catch (\Exception $e) {
            if (isset($this->blockRepository) && $this->blockRepository !== null) {
                $this->blockRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Converts the AlBlockManager object into an array
     *
     *
     * @return array
     */
    public function toArray()
    {
        if (null === $this->alBlock) {
            return array();
        }

        $blockManager = array();
        $blockManager["HideInEditMode"] = $this->getHideInEditMode();
        $blockManager["Content"] = $this->getHtmlCmsActive();
        $blockManager["ExternalJavascript"] = $this->getExternalJavascript();
        $blockManager["InternalJavascript"] = $this->getInternalJavascript();
        $blockManager["ExternalStylesheet"] = $this->getExternalStylesheet();
        $blockManager["InternalStylesheet"] = $this->getInternalStylesheet();
        $editorWidth = $this->getEditorWidth();
        $blockManager["EditorWidth"] = ($editorWidth != null && (int)$editorWidth > 0) ? $editorWidth : self::EDITOR_WIDTH;
        $blockManager["Block"] = $this->alBlock->toArray();

        return $blockManager;
    }

    /**
     * Returns the width of the editor that manages the block
     *
     * @return int
     */
    protected function getEditorWidth()
    {
        return self::EDITOR_WIDTH;
    }

    /**
     * Implements a method to let the derived class override it to format the content
     * to display when the Cms is active
     *
     * @return null
     */
    protected function formatHtmlCmsActive()
    {
        return null;
    }


    /**
     * Adds a new block to the AlBlock table
     *
     *
     * @param  array                     $values An array where keys are the AlBlockField definition and values are the values to add
     * @throws \InvalidArgumentException When the expected parameters are invalid
     * @throws \RuntimeException         When the action is aborted by a calling event
     * @return Boolean
     */
    protected function add(array $values)
    {
        $values =
            $this->dispatchBeforeOperationEvent(
                    '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent',
                    BlockEvents::BEFORE_ADD_BLOCK,
                    $values,
                    "The current block adding action has been aborted"
            );

        $this->validator->checkEmptyParams($values);
        $requiredParameters = array("PageId" => "", "LanguageId" => "", "SlotName" => "");
        $this->validator->checkRequiredParamsExists($requiredParameters, $values);

        // When the Content is null the dafault text is inserted
        if (!array_key_exists('Content', $values)) {
            $defaults = $this->getDefaultValue();
            if (!is_array($defaults)) {
                throw new General\InvalidParameterTypeException($this->translate('The abstract method getDefaultValue() defined for the object %className% must return an array', array('%className%' => get_class($this), 'al_content_manager_exceptions')));
            }

            $mergedValues = array_merge($values, $defaults);
            $availableOptions = array('Content' => '', 'InternalJavascript' => '', 'ExternalJavascript' => '', 'InternalStylesheet' => '', 'ExternalStylesheet' => '');
            $this->validator->checkOnceValidParamExists($availableOptions, $mergedValues);
            $values = $mergedValues;
        }

        try {
            $this->blockRepository->startTransaction();

            // Saves the content
            if (null === $this->alBlock) {
                $className = $this->blockRepository->getRepositoryObjectClassName();
                $this->alBlock = new $className();
            }

            $result = $this->blockRepository
                    ->setRepositoryObject($this->alBlock)
                    ->save($values);
            if (false !== $result) {
                $this->blockRepository->commit();
                $this->eventsHandler
                     ->createEvent(BlockEvents::AFTER_ADD_BLOCK, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Block\AfterBlockAddedEvent', array($this))
                     ->dispatch();

            } else {
                $this->blockRepository->rollBack();
            }

            return $result;
        } catch (\Exception $e) {
            if (isset($this->blockRepository) && $this->blockRepository !== null) {
                $this->blockRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Edits the current block object
     *
     *
     * @param  array                     $values An array where keys are the AlBlockField definition and values are the values to edit
     * @throws \InvalidArgumentException When the expected parameters are invalid
     * @throws \RuntimeException         When the action is aborted by a calling event
     * @return Boolean
     */
    protected function edit(array $values)
    {
         $values =
                $this->dispatchBeforeOperationEvent(
                        '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Block\BeforeBlockEditingEvent',
                        BlockEvents::BEFORE_EDIT_BLOCK,
                        $values,
                        "The content editing action has been aborted"
                );

        try {
            $this->validator->checkEmptyParams($values);

            // Edits the source content
            $this->blockRepository->startTransaction();
            $this->blockRepository->setRepositoryObject($this->alBlock);
            $result = $this->blockRepository->save($values);
            if (false !== $result) {
                $this->blockRepository->commit();

                $this->eventsHandler
                     ->createEvent(BlockEvents::AFTER_EDIT_BLOCK, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Block\AfterBlockEditedEvent', array($this))
                     ->dispatch();
            } else {
                $this->blockRepository->rollBack();
            }

            return $result;
        } catch (\Exception $e) {
            if (isset($this->blockRepository) && $this->blockRepository !== null) {
                $this->blockRepository->rollBack();
            }

            throw $e;
        }
    }

    private function doSetFactoryRepository($factoryRepository)
    {
        $this->factoryRepository = $factoryRepository;
        $this->blockRepository = (null !== $this->factoryRepository) ? $this->factoryRepository->createRepository('Block') : null;
    }
}
