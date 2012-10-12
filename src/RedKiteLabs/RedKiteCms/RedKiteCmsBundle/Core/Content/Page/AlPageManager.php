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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\PageEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content;
use AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Page;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\PageRepositoryInterface;

/**
 * AlPageManager is the object responsible to an AlPage object
 *
 * AlPageManager manages an AlPage object, implementig the base methods to add, edit and delete
 * that kind of object.
 *
 *
 * @author alphalemon <webmaster@alphalemon.com>
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
     * @param AlEventsHandlerInterface       $eventsHandler
     * @param AlTemplateManager              $templateManager
     * @param PageRepositoryInterface        $pageRepository
     * @param AlParametersValidatorInterface $validator
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
            throw new General\InvalidParameterTypeException('AlPageManager is only able to manage only AlPage objects');
        }

        $this->alPage = $object;

        return $this;
    }

    /**
     * Sets the template manager object
     *
     *
     * @param  AlTemplateManager $templateManager
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager
     */
    public function setTemplateManager(AlTemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;

        return $this;
    }

    /**
     * Returns the template manager object associated with this object
     *
     *
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager
     */
    public function getTemplateManager()
    {
        return $this->templateManager;
    }

    /**
     * Sets the page model object
     *
     *
     * @param  PageRepositoryInterface                                         $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager
     */
    public function setPageRepository(PageRepositoryInterface $v)
    {
        $this->pageRepository = $v;

        return $this;
    }

    /**
     * Returns the page model object associated with this object
     *
     *
     * @return PageRepositoryInterface
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
        } else {
            return $this->edit($parameters);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        if (null !== $this->alPage) {
            if (0 === $this->alPage->getIsHome()) {

                $this->dispatchBeforeOperationEvent(
                        '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageDeletingEvent',
                        PageEvents::BEFORE_DELETE_PAGE,
                        array(),
                        "The page deleting action has been aborted"
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

                    if ($result) {
                        $this->pageRepository->commit();

                        $this->eventsHandler
                             ->createEvent(PageEvents::AFTER_DELETE_PAGE, '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\AfterPageDeletedEvent', array($this))
                             ->dispatch();
                    } else {
                        $this->pageRepository->rollBack();
                    }

                    return $result;
                } catch (\Exception $e) {
                    if (isset($this->pageRepository) && $this->pageRepository !== null) {
                        $this->pageRepository->rollBack();
                    }

                    throw $e;
                }
            } else {
                throw new Page\RemoveHomePageException($this->translate('It is not allowed to remove the website\'s home page. Promote another page as the home of your website, then remove this one'));
            }
        } else {
            throw new General\ParameterIsEmptyException($this->translate('Any page is actually managed, so there\'s nothing to remove'));
        }
    }

    /**
     * Slugifies a path
     *
     * Based on http://php.vrana.cz/vytvoreni-pratelskeho-url.php
     *
     * @param  type $text
     * @return type
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
     *
     * @param  array $values
     * @return bool
     */
    protected function add(array $values)
    {
        $values =
            $this->dispatchBeforeOperationEvent(
                    '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageAddingEvent',
                    PageEvents::BEFORE_ADD_PAGE,
                    $values,
                    "The page adding action has been aborted"
            );

        try {
            $this->validator->checkEmptyParams($values);
            $this->validator->checkRequiredParamsExists(array('PageName' => '', 'TemplateName' => ''), $values);

            if (empty($values['PageName'])) {
                throw new General\ParameterIsEmptyException($this->translate("The name to assign to the page cannot be null. Please provide a valid page name to add your page"));
            }

            if (empty($values['TemplateName'])) {
                throw new General\ParameterIsEmptyException($this->translate("The page requires at least a template. Please provide the template name to add your page"));
            }

            if ($this->validator->pageExists($values['PageName'])) {
                throw new Page\PageExistsException($this->translate("The web site already contains the page you are trying to add. Please use another name for that page"));
            }

            if (!$this->validator->hasLanguages()) {
                throw new Page\AnyLanguageExistsException($this->translate("The web site has any language inserted. Please add a new language before adding a page"));
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
            } else {
                $this->pageRepository->rollBack();
            }

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
     *
     * @param  array   $values
     * @return Boolean
     */
    protected function edit(array $values)
    {
        $values =
            $this->dispatchBeforeOperationEvent(
                    '\AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforePageEditingEvent',
                    PageEvents::BEFORE_EDIT_PAGE,
                    $values,
                    "The page editing action has been aborted"
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

            if (isset($values['IsPublished'])  && $values['IsPublished'] == "" && $values['IsPublished'] == $this->alPage->getIsPublished()) {
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
            } else {
                $this->pageRepository->rollBack();
            }

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
     *
     * @return Boolean
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
