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
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockModel;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\BlockEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel;
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
    protected $alBlock = null;
    protected $factoryRepository = null;
    protected $blockRepository = null;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $dispatcher
     * @param AlFactoryRepositoryInterface $factoryRepository
     * @param AlParametersValidatorInterface $validator
     */
    public function __construct(EventDispatcherInterface $dispatcher, AlFactoryRepositoryInterface $factoryRepository = null, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($dispatcher, $validator);

        $this->doSetFactoryRepository($factoryRepository);
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
     *
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

        return $this;
    }

    /**
     * Sets the factory repository
     *
     * @param AlFactoryRepositoryInterface $v
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
     * Displays a message inside the editor to suggest a page relead
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
     * Returns the content that must be displayed on the page
     *
     * The content that is displayed on the page not always is the same saved in the database.
     *
     *
     * @return string
     */
    public function getHtmlContent()
    {
        $content = $this->getHtmlContentForDeploy();
        if ((string)$this->getInternalJavascript() != "") {
            $scriptForHideContents = ($this->getHideInEditMode()) ? sprintf("$('#block_%1\$s').data('block', $('#block_%1\$s').html());", $this->alBlock->getId()) : '';
            $internalJavascript = ($this->getExecuteInternalJavascript()) ? $this->getInternalJavascript() : '';
            if ($scriptForHideContents != '' || $internalJavascript != '') {
                $content .= sprintf('<script type="text/javascript">$(document).ready(function(){%s%s});</script>', $scriptForHideContents, $internalJavascript);
            }
        }

        return $content;
    }

    public function getHtmlContentForDeploy()
    {
        return (null !== $this->alBlock) ? $this->alBlock->getHtmlContent() : "";
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
    public function getHtmlContentForEditor()
    {
        return (null !== $this->alBlock) ? $this->alBlock->getHtmlContent() : "";
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
     * When the values is setted, it is encapsulated in a try/catch
     * block to avoid breaking the execution of AlphaLemon javascripts
     *
     *
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
                $function .= sprintf("alert('The javascript added to the slot %s has been generated an error, which reports: ' + e);\n", $this->alBlock->getSlotName());
                $function .= "}\n";
            }
        }

        return $function;
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

            $this->blockRepository->startTransaction();

            $result = $this->blockRepository
                        ->setRepositoryObject($this->alBlock)
                        ->delete();
            if ($result) {
                $this->blockRepository->commit();

                if (null !== $this->dispatcher) {
                    $event = new  Content\Block\AfterBlockDeletedEvent($this);
                    $this->dispatcher->dispatch(BlockEvents::AFTER_DELETE_BLOCK, $event);
                }

                return true;
            }
            else {
                $this->blockRepository->rollBack();

                return false;
            }
        }
        catch(\Exception $e)
        {
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
        $blockManager["HtmlContent"] = $this->getHtmlContent();
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
     *
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
            $this->blockRepository->startTransaction();

            // Saves the content
            if (null === $this->alBlock) {
                //$this->alBlock = new AlBlock();
                $className = $this->blockRepository->getRepositoryObjectClassName();
                $this->alBlock = new $className();
            }

            $result = $this->blockRepository
                    ->setRepositoryObject($this->alBlock)
                    ->save($values);
            if ($result) {
                $this->blockRepository->commit();

                if (null !== $this->dispatcher) {
                    $event = new  Content\Block\AfterBlockAddedEvent($this);
                    $this->dispatcher->dispatch(BlockEvents::AFTER_ADD_BLOCK, $event);
                }
            }
            else {
                $this->blockRepository->rollBack();
            }

            return $result;
        }
        catch(\Exception $e) {
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
            $this->blockRepository->startTransaction();
            $this->blockRepository->setRepositoryObject($this->alBlock);
            $result = $this->blockRepository->save($values);
            if ($result) {
                $this->blockRepository->commit();

                if (null !== $this->dispatcher) {
                    $event = new  Content\Block\AfterBlockEditedEvent($this);
                    $this->dispatcher->dispatch(BlockEvents::AFTER_EDIT_BLOCK, $event);
                }
            }
            else {
                $this->blockRepository->rollBack();
            }
            return $result;
        }
        catch(\Exception $e)
        {
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