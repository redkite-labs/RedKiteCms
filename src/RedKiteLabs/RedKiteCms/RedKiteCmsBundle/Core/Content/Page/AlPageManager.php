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

use AlphaLemon\PageTreeBundle\Core\Tools\AlToolkit;
use AlphaLemon\ThemeEngineBundle\Core\Model\AlThemeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageAttributeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPageAttribute;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPageAttributePeer;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\Changer\AlTemplateChanger;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\AlContentManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\PageEvents;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content;


use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageAttributes\AlPageAttributesManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Event;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\Page;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Propel\AlPageModel;

/**
 * Defines the page content manager object, that implements the methods to manage an AlPage object
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageManager extends AlContentManagerBase implements AlContentManagerInterface
{
    //protected $alPage = null;
    protected $templateManager = null;
    protected $siteLanguages = array();
    protected $alPageModel = null;

    public function __construct(EventDispatcherInterface $dispatcher = null, TranslatorInterface $translator = null, AlParametersValidatorInterface $validator = null, AlTemplateManager $templateManager = null, AlPageModel $alPageModel = null)
    {
        parent::__construct($dispatcher, $translator, $validator);
        
        $this->templateManager = $templateManager;
        $this->alPageModel = $alPageModel;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->alPageModel->getModelObject();
    }

    /**
     * {@inheritdoc}
     */
    public function set(\BaseObject $propelObject = null)
    {
        $this->alPageModel->setModelObject($propelObject);
        
        /*
        if (null !== $propelObject && !$propelObject instanceof AlPage) {
            throw new General\InvalidParameterTypeException('AlPageManager accepts only AlPage propel objects.');
        }

        $this->alPage = $propelObject;*/
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $parameters)
    {
        $alPage = $this->alPageModel->getModelObject();
        if (null === $alPage || null === $alPage->getId()) {
            
            return $this->add($parameters);
        }
        else {
            
            return $this->edit($parameters);
        }
    }
    
    public function getTemplateManager()
    {
        return $this->templateManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $alPage = $this->alPageModel->getModelObject();
        if (null !== $alPage) {
            if (0 === $alPage->getIsHome()) {
                try {
                    if (null !== $this->dispatcher) {
                        $event = new  Content\Page\BeforePageDeletingEvent($this);
                        $this->dispatcher->dispatch(PageEvents::BEFORE_DELETE_PAGE, $event);

                        if ($event->isAborted()) {
                            throw new \RuntimeException($this->translator->trans("The page deleting action has been aborted", array(), 'al_page_manager_exceptions'));
                        }
                    }
            
                    /*
                    $rollBack = false;
                    $this->connection->beginTransaction();

                    $this->alPage->setToDelete(1);
                    $result = $this->alPage->save();
                    if ($this->alPage->isModified() && $result == 0) {
                        $rollBack = true;
                    }*/
                    
                    $rollBack = false;
                    $this->alPageModel->startTransaction();
                    
                    $rollBack = !$this->alPageModel->delete();
                    if (!$rollBack) {
                        if (null !== $this->dispatcher) {
                            $event = new  Content\Page\BeforeDeletePageCommitEvent($this);
                            $this->dispatcher->dispatch(PageEvents::BEFORE_DELETE_PAGE_COMMIT, $event);

                            if ($event->isAborted()) {
                                $rollBack = true;
                            }
                        }
                    }

                    if (!$rollBack) {
                        $this->alPageModel->commit();
                        
                        if (null !== $this->dispatcher) {
                            $event = new  Content\Page\AfterPageDeletedEvent($this);
                            $this->dispatcher->dispatch(PageEvents::AFTER_DELETE_PAGE, $event);
                        }
                
                        return true;
                    }
                    else {
                        $this->alPageModel->rollback();
                        
                        return false;
                    }
                }
                catch(\Exception $e) {
                    $this->alPageModel->rollback();
                    
                    throw $e;
                }
            }
            else {
                throw new Page\RemoveHomePageException($this->translator->trans('It is not allowed to remove the website\'s home page. Promote another page as the home of your website, then remove this one'));
            }
        }
        else {
            throw new General\ParameterIsEmptyException($this->translator->trans('Any page is actually managed, so there\'s nothing to remove'));
        }
    }
    
    /**
     * Adds a new AlPage object from the given params
     * 
     * @param array $values
     * @return bool 
     */
    protected function add(array $values)
    {
        try {
            if (null !== $this->dispatcher) {
                $event = new  Content\Page\BeforePageAddingEvent($this, $values);
                $this->dispatcher->dispatch(PageEvents::BEFORE_ADD_PAGE, $event);

                if ($event->isAborted()) {
                    throw new Event\EventAbortedException($this->translator->trans("The page adding action has been aborted", array(), 'al_page_manager_exceptions'));
                }

                if ($values !== $event->getValues()) {
                    $values = $event->getValues();
                }
            }
            
            $this->validator->checkEmptyParams($values);
            $this->validator->checkRequiredParamsExists(array('pageName' => '', 'template' => ''), $values);

            if (empty($values['pageName'])) {
                throw new General\ParameterIsEmptyException($this->translator->trans("The name to assign to the page cannot be null"));
            }

            if (empty($values['template'])) {
                throw new General\ParameterIsEmptyException($this->translator->trans("The page requires at least a template"));
            }
            
            if (!$this->validator->hasLanguages()) {
                throw new Page\AnyLanguageExistsException($this->translator->trans("The web site has any language inserted. Please add a new language before adding a page"));
            }

            $rollBack = false;
            //$this->connection->beginTransaction();
            $this->alPageModel->startTransaction();
                        
            $hasPages = $this->validator->hasPages();
            $values['isHome'] = ($hasPages) ? (isset($values['isHome'])) ? $values['isHome'] : 0 : 1;
            if ($values['isHome'] == 1 && $hasPages) $rollBack = !$this->resetHome();
            
            $values['pageName'] = AlToolkit::slugify($values['pageName']);
            if (!$rollBack) {
                $values['pageName'] = AlToolkit::slugify($values['pageName']);
                
                // Saves the page
                if (null === $this->alPageModel->getModelObject()) {
                    $this->alPageModel->setModelObject(new AlPage());
                }
                
                $rollBack = !$this->alPageModel->save($values);
                if (!$rollBack) {
                    if (null !== $this->dispatcher) {
                        $event = new  Content\Page\BeforeAddPageCommitEvent($this, $values);
                        $this->dispatcher->dispatch(PageEvents::BEFORE_ADD_PAGE_COMMIT, $event);
                        
                        if ($event->isAborted()) {
                            $rollBack = true;
                        }
                    }
                }
                
                /*
                $this->alPage->setTemplateName($values['template']);
                $this->alPage->setPageName(AlToolkit::slugify($values['pageName']));
                $this->alPage->setIsHome($values['isHome']);*/
                
                /*
                $this->alPage->fromArray($values);
                $result = $this->alPage->save();
                if ($this->alPage->isModified() && $result == 0) {
                    $rollBack = true;
                }
                else {
                    if (null !== $this->dispatcher) {
                        $event = new  Content\Page\BeforeAddPageCommitEvent($this, $values);
                        $this->dispatcher->dispatch(PageEvents::BEFORE_ADD_PAGE_COMMIT, $event);
                        
                        if ($event->isAborted()) {
                            $rollBack = true;
                        }
                    }
                }*/
            }
            
            if (!$rollBack) {
                $this->alPageModel->commit();
                
                if (null !== $this->dispatcher) {
                    $event = new  Content\Page\AfterPageAddedEvent($this);
                    $this->dispatcher->dispatch(PageEvents::AFTER_ADD_PAGE, $event);
                }
                
                return true;
            }
            else {
                $this->alPageModel->rollback();
                
                return false;
            }
        }
        catch(\Exception $e) {
            $this->alPageModel->rollback();
            
            throw $e;
        }
    }

    /**
     * Edits the managed page object
     * 
     * @param array $values
     * @return Boolean 
     */
    protected function edit(array $values)
    {
        try {
            if (null !== $this->dispatcher) {
                $event = new  Content\Page\BeforePageEditingEvent($this, $values);
                $this->dispatcher->dispatch(PageEvents::BEFORE_EDIT_PAGE, $event);

                if ($event->isAborted()) {
                    throw new \RuntimeException($this->translator->trans("The page editing action has been aborted", array(), 'al_page_manager_exceptions'));
                }

                if ($values !== $event->getValues()) {
                    $values = $event->getValues();
                }
            }

            $this->validator->checkEmptyParams($values);
            /*$attributeParams = array('permalink', 'title', 'description', 'keywords');
            $this->validator->checkOnceValidParamExists($attributeParams, );
            
            $idLanguage = 0;
            $attributeParams = array('permalink', 'title', 'description', 'keywords');
            if (count(array_intersect($attributeParams, array_keys($values))) > 0)  {
                $this->checkRequiredParamsExists(array('languageId' => ''), $values); 
                $idLanguage = $values['languageId'];
            }*/
            
            
            $alPage = $this->alPageModel->getModelObject();
            
            if (isset($values['pageName']) && $values['pageName'] != "" && $alPage->getPageName() != $values['pageName']) {
                $values['pageName'] = AlToolkit::slugify($values['pageName']);
            }
            
            $rollBack = false;
            $this->alPageModel->startTransaction();
            
            $templateChanged = '';
            if (isset($values['template']) && $values['template'] != "") {
                $templateChanged = $alPage->getTemplateName(); 
                if ($templateChanged != $values['template']) {
                    $alTemplateSlots = null; 
                    $templateManager = new AlTemplateManager($this->dispatcher, $this->translator, $alTemplateSlots, null, null, $this->connection);
                    //$templateChanger = new AlTemplateChanger($this->container, $this->templateManager, $templateManager);
                    //$rollBack = !$templateChanger->change();
                }
            }
            
            if (!$rollBack) {
                if (isset($values['isHome']) && $values['isHome'] != "" && $values['isHome'] != 0 && $this->validator->hasPages(1)) {
                    $rollBack = !$this->resetHome();
                }
                
                if (!$rollBack) {
                    $rollBack = !$this->alPageModel->save($values);
                    if (!$rollBack) {
                        if (null !== $this->dispatcher) {
                            $event = new  Content\Page\BeforeEditPageCommitEvent($this, $values);
                            $this->dispatcher->dispatch(PageEvents::BEFORE_EDIT_PAGE_COMMIT, $event);

                            if ($event->isAborted()) {
                                $rollBack = true;
                            }
                        }
                    }
                }
            }
            
            /*
            if (!$rollBack) {
                if (null !== $this->dispatcher) {
                   
                }
                    
                if ($idLanguage != 0) {
                    $c = new \Criteria();
                    $c->add(AlPageAttributePeer::TO_DELETE, 0);
                    $c->add(AlPageAttributePeer::LANGUAGE_ID, $idLanguage);
                    $pageAttributes = $this->alPage->getAlPageAttributes($c);  
                    if (count($pageAttributes) == 0) {                        
                        $rollBack = !$this->addPageAttributesAndBlocks(array_merge($values, array('idPage' => $this->alPage->getId(), 'idLanguage' => $idLanguage)));                        
                    }
                    else {
                        if ($templateChanged != '') {
                            $previousTemplate = new AlTemplateManager($this->container, null, null, null, $templateChanged);
                            $newTemplate = new AlTemplateManager($this->container, null, null, null, $values['template']);
                            $templateChanger = new AlTemplateChanger($this->container, $previousTemplate, $newTemplate);
                            $rollBack = !$templateChanger->change();
                        }
                        
                        if (!$rollBack) {
                            $alPageAttributesManager = $this->container->get('al_page_attributes_manager');
                            $alPageAttributesManager->set($pageAttributes[0]);
                            $result = $alPageAttributesManager->save(array_merge($values, array('idPage' => $this->alPage->getId(), 'idLanguage' => $idLanguage)));
                            if (null !== $result) {
                                $rollBack = !$result;
                            }
                        }
                    }
                }
            }*/

            if (!$rollBack) {
                $this->alPageModel->commit();
                
                if (null !== $this->dispatcher) {
                    $event = new  Content\Page\AfterPageEditedEvent($this);
                    $this->dispatcher->dispatch(PageEvents::AFTER_EDIT_PAGE, $event);
                }
                
                return true;
            }
            else {
                $this->alPageModel->rollback();
                
                return false;
            }
        }
        catch(\Exception $e) {
            $this->alPageModel->rollback();
            
            throw $e;
        }
    }
    
    /**
     * Adds the page attribute and contents for the current page
     * 
     * @param type $pageAttributesParameters
     * @return Boolean 
     *
    protected function addPageAttributesAndBlocks($pageAttributesParameters)
    {
        try {
            $rollBack = false;
            $this->connection->beginTransaction();
            $this->pageAttributes->set(null);
            $rollBack = !$this->pageAttributes->save($pageAttributesParameters); 
           
            if (!$rollBack) {
                $rollBack = !$this->templateManager->populate($pageAttributesParameters['idLanguage'], $pageAttributesParameters['idPage']);
            }
            
            if (!$rollBack) {
                $this->connection->commit();
                
                return true;
            }
            else {
                $this->connection->rollback();
                
                return false;
            }
        }
        catch(\Exception $e) {
            if (isset($this->connection) && $this->connection !== null) {
                $this->connection->rollback();
            }
            
            throw $e;
        }
    }

    /**
     * Degrades the home page to normal page
     * 
     * @return Boolean 
     */
    protected function resetHome()
    {
        try {
            $page = $this->alPageModel->homePage();   //AlPageQuery::create()->setDispatcher($this->dispatcher)->homePage()->findOne();
            if (null !== $page) {
                
                $alPage = $this->alPageModel->getModelObject();
                $result = $this->alPageModel->setModelObject($page)->save(array('IsHome', 0));
                $this->alPageModel->setModelObject($alPage);
                
                return $result;
                /*
                $page->setIsHome(0);
                $result = $page->save();

                if ($page->isModified() && $result == 0) return false;
                 * 
                 */
            }

            return true;
        }
        catch(\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
}