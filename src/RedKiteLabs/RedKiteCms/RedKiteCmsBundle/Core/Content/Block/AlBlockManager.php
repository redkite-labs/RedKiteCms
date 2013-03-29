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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\BlockEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;
use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;

/**
 * AlBlockManager is the base object that wraps an AlBlock object and implements an 
 * AlphaLemonCMS Block object
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

    /**
     * @var \AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock $alBlock
     */
    protected $alBlock = null;
    
    /**
     * @var \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $factoryRepository
     */
    protected $factoryRepository = null;
    
    /**
     * @var \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface $blockRepository
     */
    protected $blockRepository = null;
    
    /**
     * @var \AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree $pageTree
     */
    protected $pageTree = null;

    /**
     * Constructor
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface $eventsHandler
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $factoryRepository
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     * 
     * @api
     */
    public function __construct(AlEventsHandlerInterface $eventsHandler = null, AlFactoryRepositoryInterface $factoryRepository = null, AlParametersValidatorInterface $validator = null)
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
     *   - *Content*                The html content displayed on the page
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
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager
     * 
     * @api
     */
    public function setFactoryRepository(AlFactoryRepositoryInterface $v)
    {
        $this->doSetFactoryRepository($v);

        return $this;
    }

    /**
     * Returns the factory repository object associated with this object
     *
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface
     * 
     * @api
     */
    public function getFactoryRepository()
    {
        return $this->factoryRepository;
    }

    /**
     * Returns the block repository object associated with this object
     *
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\BlockRepositoryInterface
     * 
     * @api
     */
    public function getBlockRepository()
    {
        return $this->blockRepository;
    }
    
    /**
     * Sets the current page tree
     *  
     * @param type $v
     */
    public function setPageTree(AlPageTree $v)
    {
        $this->pageTree = $v;
    }
    
    /**
     * Defines the parameters passed to the block's editor
     * 
     * @return array
     */
    public function editorParameters()
    {
        return array();
    }

    /**
     * Defines when a block is internal, so it must not be available in the add blocks 
     * menu
     *
     * @return boolean
     * 
     * @api
     */
    public function getIsInternalBlock()
    {
        return false;
    }

    /**
     * Defines when a content is rendered or not in edit mode.
     *
     * By default the content is rendered when the edit mode is active. To hide the content, simply override
     * this method and return true
     *
     * @return boolean
     * 
     * @api
     */
    public function getHideInEditMode()
    {
        return false;
    }

    /**
     * Displays a message inside the default editor to suggest a page relead
     *
     * Return true to display a warnig on editor that suggest the used to reload the page when the block is added or edited
     *
     * @return boolean
     * 
     * @api
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
     * @return boolean
     * 
     * @api
     */
    public function getExecuteInternalJavascript()
    {
        return true;
    }

    /**
     * Returns the block's html content
     * 
     * This method must be overriden to display an elaborated version of the content
     * saved for the current Block
     *
     * @return string|array
     * 
     * @api
     */
    public function getHtml()
    {
        return array('RenderView' => array(
            'view' => 'AlphaLemonCmsBundle:Block:base_block.html.twig',
            'options' => array(
                'block' => $this->alBlock,
            ),
        ));
    }
    
    /**
     * Returns a string that contains the metatags required by the block
     * 
     * @return null|string
     */
    public function getMetaTags()
    {
        return null;
    }

    /**
     * Returns the html content when AlphaLemon CMS is active.
     *
     * By default the internal javascript is concatenated to the block's html content: this behavior
     * can be changed overriding the getExecuteInternalJavascript() method.
     *
     * @deprecated
     * @return string
     */
    final public function getHtmlCmsActive()
    {
        throw new \RuntimeException("getHtmlCmsActive has been deprecated and replaced by replaceHtmlCmsActive()");
    }

    /**
     * Returns the content displayed in the editor
     *
     * The editor that manages the content gets the content saved into the database.
     * Override this method to change the content to display
     *
     * @return string
     * 
     * @api
     */
    public function getContentForEditor()
    {
        return $this->getHtml();
    }

    /**
     * Returns the current saved ExternalJavascript value as array
     *
     * @return array
     * 
     * @api
     */
    public function getExternalJavascript()
    {
        if (null !== $this->alBlock) {
            $javascripts = trim($this->alBlock->getExternalJavascript());
        }

        return ($javascripts != "") ? explode(',', $javascripts) : array();
    }

    /**
     * Returns the current saved ExternalStylesheet value as array
     *
     * @return array
     * 
     * @api
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
     * @param boolean
     * @return string
     * 
     * @api
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
     * @return string
     * 
     * @api
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
        } 
        
        return $this->edit($parameters);
    }
    
    /**
     * {@inheritdoc}
     * 
     * @return boolean
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ParameterIsEmptyException
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     * 
     * @api
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
     * Adds some internal options to describe how to properly render the block
     * 
     * @return array
     * 
     * @api
     */
    public function toArray()
    {
        if (null === $this->alBlock) {
            return array();
        }
        
        $content = $this->replaceHtmlCmsActive();
        if (null === $content) {
            $content = $this->getHtml();
        }

        $blockManager = array();
        $blockManager["HideInEditMode"] = $this->getHideInEditMode();        
        $blockManager["ExecuteInternalJavascript"] = $this->getExecuteInternalJavascript();
        $blockManager["Content"] = $content;   
        $blockManager["ExternalJavascript"] = $this->getExternalJavascript();
        $blockManager["InternalJavascript"] = $this->getInternalJavascript();
        $blockManager["ExternalStylesheet"] = $this->getExternalStylesheet();
        $blockManager["InternalStylesheet"] = $this->getInternalStylesheet();
        $editorWidth = $this->getEditorWidth();
        $blockManager["EditorWidth"] = ($editorWidth != null && (int)$editorWidth > 0) ? $editorWidth : self::EDITOR_WIDTH;
        $blockManager["EditInline"] = $this->editInline();
        $blockManager["Block"] = $this->alBlock->toArray();
        
        return $blockManager;
    }
    
    /**
     * Edits the block using an inline editor
     * 
     * @return boolean
     */
    protected function editInline()
    {
        return false;
    }

    /**
     * Returns the width of the editor that manages the block
     *
     * @return int
     * 
     * @api
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
     * 
     * @api
     */
    protected function replaceHtmlCmsActive()
    {
        return null;
    }
    
    /**
     * @deprecated
     */
    protected function formatHtmlCmsActive()
    {
        return replaceHtmlCmsActive();
    }

    /**
     * Adds a new block to the AlBlock table
     * 
     * @param array $values An array where keys are the AlBlockField definition and values are the values to add
     * @return boolean
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\General\InvalidParameterTypeException
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     * 
     * @api
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
     * @param array $values An array where keys are the AlBlockField definition and values are the values to edit
     * @return boolean
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException
     * 
     * @api
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
