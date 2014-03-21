<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlBlock;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\BlockEvents;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\AlContentManagerInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Base\AlContentManagerBase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\AlPageTree;

/**
 * AlBlockManager is the base object that wraps an AlBlock object and implements a
 * RedKiteCms Block object
 *
 *
 * AlBlockManager manages an AlBlock object, implementig the base methods to add, edit and delete
 * that kind of object and provides several methods to change the behavior of the block itself,
 * when it is rendered on the page.
 *
 * Every new block content must inherit from this class.
 *
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class AlBlockManager extends AlContentManagerBase implements AlContentManagerInterface, AlBlockManagerInterface
{
    /**
     * @var AlBlock $alBlock
     */
    protected $alBlock = null;

    /**
     * @var AlFactoryRepositoryInterface $factoryRepository
     */
    protected $factoryRepository = null;

    /**
     * @var \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface $blockRepository
     */
    protected $blockRepository = null;

    /**
     * @var AlPageTree $pageTree
     */
    protected $pageTree = null;

    /**
     * @var Boolean
     */
    protected $editorDisabled = false;

    protected $extraOptions = array();

    /**
     * Constructor
     *
     * @param AlEventsHandlerInterface       $eventsHandler
     * @param AlFactoryRepositoryInterface   $factoryRepository
     * @param AlParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(AlEventsHandlerInterface $eventsHandler = null, AlFactoryRepositoryInterface $factoryRepository = null, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($eventsHandler, $validator);

        $this->doSetFactoryRepository($factoryRepository);
    }

    /**
     * Defines the App-Block's default value
     *
     * Returns an array which contains the following key:
     *
     *   - *Content*                The html content displayed on the page
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
            throw new InvalidArgumentTypeException('exception_only_block_objects_are_accepted');
        }

        $this->alBlock = $object;

        return $this;
    }

    /**
     * Sets the factory repository
     *
     * @param  \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $v
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\AlBlockManager
     *
     * @api
     */
    public function setFactoryRepository(AlFactoryRepositoryInterface $v)
    {
        $this->doSetFactoryRepository($v);

        return $this;
    }

    /**
     * Returns editor disabled
     *
     * @return boolean
     */
    public function getEditorDisabled()
    {
        return $this->editorDisabled;
    }

    /**
     * Sets editor disabled
     *
     * @return boolean
     */
    public function setEditorDisabled($v)
    {
        $this->editorDisabled = $v;

        return $this;
    }

    /**
     * Returns the factory repository object associated with this object
     *
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface
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
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\BlockRepositoryInterface
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
     * Defines the parameters passed to the App-Block's editor
     *
     * @return array
     *
     * @api
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
     * Returns the block manager's extra options.
     *
     * @return array
     */
    public function blockExtraOptions()
    {
        return array();
    }

    /**
     * Sets extra options for rendering the block.
     *
     * This method should be used to pass options from a parent block to children blocks
     *
     * @param array $options
     */
    public function setBlockExtraOptions(array $options)
    {
        $this->extraOptions = $options;
    }

    /**
     * Returns the block's html content or an array which contains the view to be
     * rendered with its options
     *
     * @return string|array
     *
     * @api
     */
    final public function getHtml()
    {
        $result = $this->renderHtml();
        if (is_array($result) && array_key_exists('RenderView', $result)) {
            $result['RenderView']['options']['block_manager'] = $this;
        }

        return $result;
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
     * @throws \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
     * @throws \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     *
     * @api
     */
    public function delete()
    {
        if (null === $this->alBlock) {
            throw new General\ArgumentIsEmptyException('exception_no_blocks_set_delete_skipped');
        }

        $this->dispatchBeforeOperationEvent(
            '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockDeletingEvent',
            BlockEvents::BEFORE_DELETE_BLOCK,
            array(),
            'exception_block_removing_aborted'
        );

        try {
            $this->blockRepository->startTransaction();

            $result = $this->blockRepository
                        ->setRepositoryObject($this->alBlock)
                        ->delete();
            if ($result) {
                $this->blockRepository->commit();
                $this->eventsHandler
                     ->createEvent(BlockEvents::AFTER_DELETE_BLOCK, '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\AfterBlockDeletedEvent', array($this))
                     ->dispatch();

                return true;
            }

            $this->blockRepository->rollBack();

            return false;
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
        $blockManager["Content"] = $content;
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
     * Implements a method to let the derived class override it to format the content
     * to display when the Cms is active
     *
     * @return string|null
     *
     * @api
     */
    protected function replaceHtmlCmsActive()
    {
        return null;
    }

    /**
     * Renders the App-Block's content view
     *
     * This method must be overriden to display an elaborated version of the content
     * saved for the current Block
     *
     * @return string|array
     */
    protected function renderHtml()
    {
        return array('RenderView' => array(
            'view' => 'RedKiteCmsBundle:Block:base_block.html.twig',
            'options' => array(
                'block' => $this->alBlock,
            ),
        ));
    }

    /**
     * Adds a new block to the AlBlock table
     *
     * @param  array                        $values An array where keys are the AlBlockField definition and values are the values to add
     * @return boolean
     * @throws InvalidArgumentTypeException
     * @throws \Exception
     *
     * @api
     */
    protected function add(array $values)
    {
        $values =
            $this->dispatchBeforeOperationEvent(
                '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockAddingEvent',
                BlockEvents::BEFORE_ADD_BLOCK,
                $values,
                'exception_block_adding_aborted'
            );

        $this->validator->checkEmptyParams($values);
        $requiredParameters = array("PageId" => "", "LanguageId" => "", "SlotName" => "");
        $this->validator->checkRequiredParamsExists($requiredParameters, $values);

        // When the Content is null the dafault text is inserted
        if (!array_key_exists('Content', $values)) {
            $defaults = $this->getDefaultValue();
            if (!is_array($defaults)) {
                $exception = array(
                    'message' => 'exception_method_returns_invalid_value',
                    'parameters' => array(
                        '%className%' => get_class($this),
                    ),
                );
                throw new General\InvalidArgumentTypeException(json_encode($exception));
            }

            $mergedValues = array_merge($values, $defaults);
            $availableOptions = array(
                'Content' => '',
                'InternalJavascript' => '',
                'ExternalJavascript' => '',
                'InternalStylesheet' => '',
                'ExternalStylesheet' => '',
            );
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
                     ->createEvent(BlockEvents::AFTER_ADD_BLOCK, '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\AfterBlockAddedEvent', array($this))
                     ->dispatch();

                return $result;
            }

            $this->blockRepository->rollBack();

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
     * @param  array                                                                                     $values An array where keys are the AlBlockField definition and values are the values to edit
     * @return boolean
     * @throws \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     *
     * @api
     */
    protected function edit(array $values)
    {
         $values =
            $this->dispatchBeforeOperationEvent(
                '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\BeforeBlockEditingEvent',
                BlockEvents::BEFORE_EDIT_BLOCK,
                $values,
                'exception_block_editing_aborted'
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
                     ->createEvent(BlockEvents::AFTER_EDIT_BLOCK, '\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Block\AfterBlockEditedEvent', array($this))
                     ->dispatch();

                return $result;
            }

            $this->blockRepository->rollBack();

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
