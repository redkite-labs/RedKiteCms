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
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlBlockModel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\BlockEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm\BlockModelInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;

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
 * @api
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlBlockManager extends AlContentManagerBase implements AlContentManagerInterface
{
    protected $alBlock = null;
    protected $blockModel = null;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $dispatcher
     * @param BlockModelInterface $blockModel
     * @param AlParametersValidatorInterface $validator
     */
    public function __construct(EventDispatcherInterface $dispatcher, BlockModelInterface $blockModel, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($dispatcher, $validator);

        $this->blockModel = $blockModel;
    }

    /**
     * Defines the default value of the managed block
     *
     *
     * Returns an array which may contain one or more of these keys:
     *
     *   - *HtmlContent*            The html content displayed on the page
     *   - *ExternalJavascript*     A comma separated external javascripts files
     *   - *InternalJavascript*     A javascript code
     *   - *ExternalStylesheet*     A comma separated external stylesheets files
     *   - *InternalStylesheet*     A stylesheet code
     *
     * @api
     * @return array
     */
    abstract function getDefaultValue();

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
    }

    /**
     * Sets the block model object
     *
     * @api
     * @param BlockModelInterface $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager
     */
    public function setBlockModel(BlockModelInterface $v)
    {
        $this->blockModel = $v;

        return $this;
    }

    /**
     * Returns the block model object associated with this object
     *
     * @api
     * @return BlockModelInterface
     */
    public function getBlockModel()
    {
        return $this->blockModel;
    }

    /**
     * Defines when a content is rendered or not in edit mode.
     *
     *
     * By default the content is rendered when the edit mode is active. To hide the content, simply override
     * this method and return true
     *
     * @api
     * @return Boolean
     */
    public function getHideInEditMode()
    {
        return false;
    }

    /**
     * Displays a message inside the editor to suggest a page relead
     *
     * Return true tu display a warnig on editor that suggest the used to reload the page when the block is added or edited
     *
     * @api
     * @return Boolean
     */
    public function getReloadSuggested()
    {
        return false;
    }

    /**
     * Returns the content that must be displayed on the page
     *
     * The content that is displayed on the page not always is the same saved in the database.
     *
     * @api
     * @return string
     */
    public function getHtmlContent()
    {
        return (null !== $this->alBlock) ? $this->alBlock->getHtmlContent() : "";
    }

    /**
     * Returns the content to display, when the site is in CMS mode
     *
     * When the CMS mode is active, AlphaLemon CMS renders the same content displayed on the page.
     * Override this method to change the content to display
     *
     * @api
     * @return string
     */
    public function getHtmlContentCMSMode()
    {
        return $this->getHtmlContent();
    }

    /**
     * Returns the content displayed in the editor
     *
     * The editor that manages the content gets the content saved into the database.
     * Override this method to change the content to display
     *
     * @api
     * @return string
     */
    public function getHtmlContentForEditor()
    {
        return (null !== $this->alBlock) ? $this->alBlock->getHtmlContent() : "";
    }

    /**
     * Returns the current saved ExternalJavascript value
     *
     * @api
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
     * @api
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
     * When the values is setted, it is encapsulated in a try/catch
     * block to avoid breaking the execution of AlphaLemon javascripts
     *
     * @api
     * @return string
     */
    public function getInternalJavascript()
    {
        $function = '';
        if (null !== $this->alBlock) {
            if (trim($this->alBlock->getInternalJavascript()) != '') {
                $function .= "try{\n";
                $function .= $this->alBlock->getInternalJavascript();
                $function .= "\n} catch(e){\n";
                $function .= sprintf("alert('The javascript added to the slot %s has been generated an error, which reports:\n\n' + e);\n", $this->alBlock->getSlotName());
                $function .= "}\n";
            }
        }

        return $function;
    }

    /**
     * Returns the current saved InternalStylesheet
     *
     * @api
     * @return string
     */
    public function getInternalStylesheet()
    {
        return (null !== $this->alBlock) ? $this->alBlock->getInternalStylesheet() : "";
    }

    /**
     * Returns the current saved InternalStylesheet displayed in the editor
     *
     * @return string
     */
    public function getInternalJavascriptForEditor()
    {
        return (null !== $this->alBlock) ? $this->alBlock->getInternalJavascript() : "";
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $parameters)
    {
        if (null === $this->alBlock || $this->alBlock->getId() == null) {

            return $this->add($parameters);
        }
        else {

            return $this->edit($parameters);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        try
        {
            if (null === $this->alBlock) {
                throw new General\ParameterIsEmptyException($this->translate("Any valid block has been setted. Nothing to delete", array()));
            }

            if (null !== $this->dispatcher) {
                $event = new  Content\Block\BeforeBlockDeletingEvent($this);
                $this->dispatcher->dispatch(BlockEvents::BEFORE_DELETE_BLOCK, $event);

                if ($event->isAborted()) {
                    throw new Event\EventAbortedException($this->translate("The content deleting action has been aborted", array()));
                }
            }

            $this->blockModel->startTransaction();

            $result = $this->blockModel
                        ->setModelObject($this->alBlock)
                        ->delete();
            if ($result) {
                $this->blockModel->commit();

                if (null !== $this->dispatcher) {
                    $event = new  Content\Block\AfterBlockDeletedEvent($this);
                    $this->dispatcher->dispatch(BlockEvents::AFTER_DELETE_BLOCK, $event);
                }

                return true;
            }
            else {
                $this->blockModel->rollBack();

                return false;
            }
        }
        catch(\Exception $e)
        {
            if (isset($this->blockModel) && $this->blockModel !== null) {
                $this->blockModel->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Converts the AlBlockManager object into an array
     *
     * @api
     * @return array
     */
    public function toArray()
    {
        if (null === $this->alBlock) {
            return array();
        }

        $blockManager = array();
        $blockManager["HideInEditMode"] = $this->getHideInEditMode();
        $blockManager["HtmlContent"] = $this->getHtmlContent();
        $blockManager["HtmlContentCMSMode"] = $this->getHtmlContentCMSMode();
        $blockManager["ExternalJavascript"] = $this->getExternalJavascript();
        $blockManager["InternalJavascript"] = $this->getInternalJavascript();
        $blockManager["ExternalStylesheet"] = $this->getExternalStylesheet();
        $blockManager["InternalStylesheet"] = $this->getInternalStylesheet();
        $blockManager["Block"] = $this->alBlock->toArray();

        return $blockManager;
    }


    /**
     * Adds a new block to the AlBlock table
     *
     * @api
     * @param array  $values      An array where keys are the AlBlockField definition and values are the values to add
     * @throws \InvalidArgumentException  When the expected parameters are invalid
     * @throws \RuntimeException  When the action is aborted by a calling event
     * @return Boolean
     */
    protected function add(array $values)
    {
        if (null !== $this->dispatcher) {
            $event = new Content\Block\BeforeBlockAddingEvent($this, $values);
            $this->dispatcher->dispatch(BlockEvents::BEFORE_ADD_BLOCK, $event);

            if ($event->isAborted()) {
                throw new Event\EventAbortedException($this->translate("The current block adding action has been aborted", array(), 'exceptions'));
            }

            if ($values !== $event->getValues()) {
                $values = $event->getValues();
            }
        }

        $this->validator->checkEmptyParams($values);

        $requiredParameters = array("PageId" => "", "LanguageId" => "", "SlotName" => "");
        $this->validator->checkRequiredParamsExists($requiredParameters, $values);

        // When the Content is null the dafault text is inserted
        if (!array_key_exists('HtmlContent', $values)) {
            $defaults = $this->getDefaultValue();
            if (!is_array($defaults)) {
                throw new General\InvalidParameterTypeException($this->translate('The abstract method getDefaultValue() defined for the object %className% must return an array', array('%className%' => get_class($this), 'al_content_manager_exceptions')));
            }

            $mergedValues = array_merge($values, $defaults);
            $availableOptions = array('HtmlContent' => '', 'InternalJavascript' => '', 'ExternalJavascript' => '', 'InternalStylesheet' => '', 'ExternalStylesheet' => '');
            $this->validator->checkOnceValidParamExists($availableOptions, $mergedValues);
            $values = $mergedValues;
        }

        try {
            $this->blockModel->startTransaction();

            // Saves the content
            if (null === $this->alBlock) {
                //$this->alBlock = new AlBlock();
                $className = $this->blockModel->getModelObjectClassName();
                $this->alBlock = new $className();
            }

            $result = $this->blockModel
                    ->setModelObject($this->alBlock)
                    ->save($values);
            if ($result) {
                $this->blockModel->commit();

                if (null !== $this->dispatcher) {
                    $event = new  Content\Block\AfterBlockAddedEvent($this);
                    $this->dispatcher->dispatch(BlockEvents::AFTER_ADD_BLOCK, $event);
                }
            }
            else {
                $this->blockModel->rollBack();
            }

            return $result;
        }
        catch(\Exception $e) {
            if (isset($this->blockModel) && $this->blockModel !== null) {
                $this->blockModel->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Edits the current block object
     *
     * @api
     * @param array  $values  An array where keys are the AlBlockField definition and values are the values to edit
     * @throws \InvalidArgumentException  When the expected parameters are invalid
     * @throws \RuntimeException  When the action is aborted by a calling event
     * @return Boolean
     */
    protected function edit(array $values)
    {
        try
        {
            if (null !== $this->dispatcher) {
                $event = new  Content\Block\BeforeBlockEditingEvent($this, $values);
                $this->dispatcher->dispatch(BlockEvents::BEFORE_EDIT_BLOCK, $event);

                if ($event->isAborted()) {
                    throw new Event\EventAbortedException($this->translate("The content editing action has been aborted", array(), 'al_content_manager_exceptions'));
                }

                if ($values !== $event->getValues()) {
                    $values = $event->getValues();
                }
            }

            $this->validator->checkEmptyParams($values);

            // Edits the source content
            $this->blockModel->startTransaction();
            $this->blockModel->setModelObject($this->alBlock);
            $result = $this->blockModel->save($values); 
            if ($result) {
                $this->blockModel->commit();

                if (null !== $this->dispatcher) {
                    $event = new  Content\Block\AfterBlockEditedEvent($this);
                    $this->dispatcher->dispatch(BlockEvents::AFTER_EDIT_BLOCK, $event);
                }
            }
            else {
                $this->blockModel->rollBack();
            }
            return $result;
        }
        catch(\Exception $e)
        {
            if (isset($this->blockModel) && $this->blockModel !== null) {
                $this->blockModel->rollBack();
            }

            throw $e;
        }
    }
}