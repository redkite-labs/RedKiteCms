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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\PageEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Page;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\PageRepositoryInterface;

/**
 * AlPageManager is the base object that wraps an AlPage object
 *
 * AlPageManager manages an AlPage object, implementig the base methods to add, edit
 * and delete that kind of object.
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
class AlPageManager extends AlContentManagerBase implements AlContentManagerInterface
{
    protected $templateManager = null;
    protected $siteLanguages = array();
    protected $factoryRepository = null;
    protected $pageRepository;
    protected $alPage;

    /**
     * Constructor
     *
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface           $eventsHandler
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager               $templateManager
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface  $factoryRepository
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(AlEventsHandlerInterface $eventsHandler, AlTemplateManager $templateManager, AlFactoryRepositoryInterface $factoryRepository, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($eventsHandler, $validator);

        $this->templateManager = $templateManager;
        $this->factoryRepository = $factoryRepository;
        $this->pageRepository = $this->factoryRepository->createRepository('Page');
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->alPage;
    }

    /**
     * {@inheritdoc}
     */
    public function set($object = null)
    {
        if (null !== $object && !$object instanceof AlPage) {
            $exception = array(
                'message' => 'AlPageManager is able to manage only AlPage objects',
                'domain' => 'exceptions',
            );
            throw new General\InvalidArgumentTypeException(json_encode($exception));
        }

        $this->alPage = $object;

        return $this;
    }

    /**
     * Sets the template manager object
     *
     * @param  \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager $templateManager
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager
     *
     * @api
     */
    public function setTemplateManager(AlTemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;

        return $this;
    }

    /**
     * Returns the template manager object associated with this object
     *
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager
     *
     * @api
     */
    public function getTemplateManager()
    {
        return $this->templateManager;
    }

    /**
     * Sets the page model object
     *
     * @param  \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\PageRepositoryInterface $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager
     *
     * @api
     */
    public function setPageRepository(PageRepositoryInterface $v)
    {
        $this->pageRepository = $v;

        return $this;
    }

    /**
     * Returns the page model object associated with this object
     *
     * @return PageRepositoryInterface
     *
     * @api
     */
    public function getPageRepository()
    {
        return $this->pageRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $parameters)
    {
        if (null === $this->alPage || $this->alPage->getId() == null) {
            return $this->add($parameters);
        }

        return $this->edit($parameters);
    }

    /**
     * {@inheritdoc}
     *
     * @return boolean
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\Exception
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Page\RemoveHomePageException
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
     * 
     * @api
     */
    public function delete()
    {
        if (null === $this->alPage) {
            $exception = array(
                'message' => 'Any page is actually managed, so there is nothing to remove',
                'domain' => 'exceptions',
            );
            throw new General\ArgumentIsEmptyException(json_encode($exception));
        }
        
        if (0 !== $this->alPage->getIsHome()) {
            $exception = array(
                'message' => "It is not allowed to remove the website's home page. Promote another page as the home of your website, then remove this one",
                'domain' => 'exceptions',
            );
            throw new Page\RemoveHomePageException(json_encode($exception));
        }
        
        $this->dispatchBeforeOperationEvent(
            '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageDeletingEvent',
            PageEvents::BEFORE_DELETE_PAGE,
            array(),
            array(
                'message' => 'The page deleting action has been aborted',
                'domain' => 'exceptions',
            )
        );

        try {
            $this->pageRepository->startTransaction();
            $this->pageRepository->setRepositoryObject($this->alPage);
            $result = $this->pageRepository->delete();
            if ($result) {
                $eventName = PageEvents::BEFORE_DELETE_PAGE_COMMIT;
                $result = !$this->eventsHandler
                                ->createEvent($eventName, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeDeletePageCommitEvent', array($this, array()))
                                ->dispatch()
                                ->getEvent($eventName)
                                ->isAborted();
            }

            if (false !== $result) {
                $this->pageRepository->commit();

                $this->eventsHandler
                     ->createEvent(PageEvents::AFTER_DELETE_PAGE, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\AfterPageDeletedEvent', array($this))
                     ->dispatch();
                     
                return $result;
            }
            $this->pageRepository->rollBack();

            return $result;
        } catch (\Exception $e) {
            if (isset($this->pageRepository) && $this->pageRepository !== null) {
                $this->pageRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Slugifies a path
     *
     * Based on http://php.vrana.cz/vytvoreni-pratelskeho-url.php
     *
     * @param  string $text
     * @return string
     *
     * @api
     */
    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * Adds a new AlPage object from the given params
     *
     * @param  array                                                                                    $values
     * @return boolean
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\Exception
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Page\PageExistsException
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Page\AnyLanguageExistsException
     *
     * @api
     */
    protected function add(array $values)
    {
        $values =
            $this->dispatchBeforeOperationEvent(
                '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent',
                PageEvents::BEFORE_ADD_PAGE,
                $values,
                array(
                    'message' => 'The page adding action has been aborted',
                    'domain' => 'exceptions',
                )
            );

        try {
            $this->validator->checkEmptyParams($values);
            $this->validator->checkRequiredParamsExists(array('PageName' => '', 'TemplateName' => ''), $values);

            if (empty($values['PageName'])) {
                $exception = array(
                    'message' => "The name to assign to the page cannot be null. Please provide a valid page name to add your page",
                    'domain' => 'exceptions',
                );
                throw new General\ArgumentIsEmptyException(json_encode($exception));
            }

            if (empty($values['TemplateName'])) {
                $exception = array(
                    'message' => "The page requires at least a template. Please provide the template name to add your page",
                    'domain' => 'exceptions',
                );
                throw new General\ArgumentIsEmptyException(json_encode($exception));
            }

            if ($this->validator->pageExists($values['PageName'])) {
                $exception = array(
                    'message' => "The web site already contains the page you are trying to add. Please use another name for that page",
                    'domain' => 'exceptions',
                );
                throw new Page\PageExistsException(json_encode($exception));
            }

            if (!$this->validator->hasLanguages()) {
                $exception = array(
                    'message' => "The web site has any language inserted. Please add a new language before adding a page",
                    'domain' => 'exceptions',
                );
                throw new Page\AnyLanguageExistsException(json_encode($exception));
            }

            $result = true;
            $this->pageRepository->startTransaction();
            if (null === $this->alPage) {
                $className = $this->pageRepository->getRepositoryObjectClassName();
                $this->alPage = new $className();
            }

            $hasPages = $this->validator->hasPages();
            $values['IsHome'] = ($hasPages) ? (isset($values['IsHome'])) ? $values['IsHome'] : 0 : 1;
            if ($values['IsHome'] == 1 && $hasPages) {
                $result = $this->resetHome();
            }

            if (false !== $result) {
                $values['PageName'] = $this->slugify($values['PageName']);

                // Saves the page
                $result = $this->pageRepository
                               ->setRepositoryObject($this->alPage)
                               ->save($values);
                if (false !== $result) {
                    $eventName = PageEvents::BEFORE_ADD_PAGE_COMMIT;
                    $result = !$this->eventsHandler
                                    ->createEvent($eventName, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent', array($this, $values))
                                    ->dispatch()
                                    ->getEvent($eventName)
                                    ->isAborted();
                }
            }

            if (false !== $result) {
                $this->pageRepository->commit();

                $this->eventsHandler
                     ->createEvent(PageEvents::AFTER_ADD_PAGE, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\AfterPageAddedEvent', array($this))
                     ->dispatch();
                     
                return $result;
            } 
                
            $this->pageRepository->rollBack();

            return $result;
        } catch (\Exception $e) {
            if (isset($this->pageRepository) && $this->pageRepository !== null) {
                $this->pageRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Edits the managed page object
     *
     * @param  array                                                       $values
     * @return boolean
     * @throws \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\Exception
     *
     * @api
     */
    protected function edit(array $values)
    {
        $values =
            $this->dispatchBeforeOperationEvent(
                    '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageEditingEvent',
                    PageEvents::BEFORE_EDIT_PAGE,
                    $values,
                    array(
                        'message' => 'The page editing action has been aborted',
                        'domain' => 'exceptions',
                    )
            );

        try {
            $this->validator->checkEmptyParams($values);
            $this->pageRepository->startTransaction();

            if (isset($values['PageName']) && $values['PageName'] != "" && $this->alPage->getPageName() != $values['PageName']) {
                $values['PageName'] = $this->slugify($values['PageName']);
            } else {
                unset($values['PageName']);
            }

            $templateChanged = '';
            if (isset($values['TemplateName']) && $values['TemplateName'] != "") {
                $templateChanged = $this->alPage->getTemplateName();
                if ($templateChanged != $values['TemplateName']) {
                     $values['oldTemplateName'] = $templateChanged;
                }
            } else {
                unset($values['TemplateName']);
            }

            $result = true;
            if (isset($values['IsHome']) && $values['IsHome'] != "" && $values['IsHome'] != 0 && $this->validator->hasPages(1)) {
                $result = $this->resetHome();
            } else {
                unset($values['IsHome']);
            }

            if (empty($values['IsPublished']) || $values['IsPublished'] == $this->alPage->getIsPublished()) {
                unset($values['IsPublished']);
            }

            if ($result !== false) {
                if (!empty($values)) {
                    $result = $this->pageRepository
                                ->setRepositoryObject($this->alPage)
                                ->save($values);
                }

                if (false !== $result) {
                    $eventName = PageEvents::BEFORE_EDIT_PAGE_COMMIT;
                    $result = !$this->eventsHandler
                                        ->createEvent($eventName, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeEditPageCommitEvent', array($this, $values))
                                        ->dispatch()
                                        ->getEvent($eventName)
                                        ->isAborted();
                }
            }

            if (false !== $result) {
                $this->pageRepository->commit();

                $this->eventsHandler
                     ->createEvent(PageEvents::AFTER_EDIT_PAGE, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\AfterPageEditedEvent', array($this))
                     ->dispatch();
                     
                return $result;
            } 
                
            $this->pageRepository->rollBack();

            return $result;
        } catch (\Exception $e) {
            if (isset($this->pageRepository) && $this->pageRepository !== null) {
                $this->pageRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Degrades the home page to normal page
     *
     * @return boolean
     */
    protected function resetHome()
    {
        try {
            $page = $this->pageRepository->homePage();
            if (null !== $page) {
                return $this->pageRepository
                            ->setRepositoryObject($page)
                            ->save(array('IsHome' => 0));
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
