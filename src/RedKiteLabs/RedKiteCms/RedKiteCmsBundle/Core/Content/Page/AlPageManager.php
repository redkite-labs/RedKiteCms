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

use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
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

/**
 * Defines the page content manager object, that implements the methods to manage an AlPage object
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlPageManager extends AlContentManagerBase implements AlContentManagerInterface
{
    protected $alPage = null;

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
    public function set(\BaseObject $propelObject = null)
    {
        if(null !== $propelObject && !$propelObject instanceof AlPage)
        {
            throw new InvalidArgumentException('AlPageManager accepts only AlPage propel objects.');
        }

        $this->alPage = $propelObject;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $parameters)
    {
        if(null === $this->alPage)
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
        if ($this->alPage)
        {
            if(0 === $this->alPage->getIsHome())
            {
                try
                {
                    $dispatcher = $this->container->get('event_dispatcher');
                    if(null !== $dispatcher)
                    {
                        $event = new  Content\Page\BeforePageDeletingEvent($this);
                        $dispatcher->dispatch(PageEvents::BEFORE_DELETE_PAGE, $event);

                        if($event->isAborted())
                        {
                            throw new \RuntimeException(AlToolkit::translateMessage($this->container, "The page deleting action has been aborted", array(), 'al_page_manager_exceptions'));
                        }
                    }
            
                    $rollBack = false;
                    $this->connection->beginTransaction();

                    $this->alPage->setToDelete(1);
                    $result = $this->alPage->save();
                    if ($this->alPage->isModified() && $result == 0)
                    {
                        $rollBack = true;
                    }
                    else
                    {
                        $rollBack = !$this->deleteBlocksAndPageAttributes();
                    }

                    if (!$rollBack)
                    {
                        $this->connection->commit();
                        
                        if(null !== $dispatcher)
                        {
                            $event = new  Content\Page\AfterPageDeletedEvent($this);
                            $dispatcher->dispatch(PageEvents::AFTER_DELETE_PAGE, $event);
                        }
                
                        return true;
                    }
                    else
                    {
                        $this->connection->rollback();
                        return false;
                    }
                }
                catch(\Exception $e)
                {
                    $this->connection->rollback();
                    throw $e;
                }
            }
            else
            {
                if(isset($this->connection) && $this->connection !== null) $this->connection->rollback();
                throw new \RuntimeException(AlToolkit::translateMessage($this->container, 'It is not allowed to remove the website\'s home page. Promote another page as the home of your website, then remove this one'));
            }
        }
        else
        {
            if(isset($this->connection) && $this->connection !== null) $this->connection->rollback();
            throw new \RuntimeException(AlToolkit::translateMessage($this->container, 'A null page cannot be removed'));
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
        try
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new  Content\Page\BeforePageAddingEvent($this, $values);
                $dispatcher->dispatch(PageEvents::BEFORE_ADD_PAGE, $event);

                if($event->isAborted())
                {
                    throw new \RuntimeException(AlToolkit::translateMessage($this->container, "The page adding action has been aborted", array(), 'al_page_manager_exceptions'));
                }

                if($values !== $event->getValues())
                {
                    $values = $event->getValues();
                }
            }

            if(empty($values))
            {
                throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, 'The page cannot be added because any parameter has been given'));
            }

            $this->checkRequiredParamsExists(array('pageName' => '', 'template' => ''), $values);

            if (empty($values['pageName']))
            {
                throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, "The name to assign to the page cannot be null"));
            }

            if (empty($values['template']))
            {
                throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, "The page requires at least a template"));
            }

            if ($this->pageExists($values['pageName']))
            {
                throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, "The name to assign to the page already exists in the website. Page name must be unique."));
            }

            $alLanguages =  AlLanguageQuery::create()->setContainer($this->container)->activeLanguages()->find();
            if(count($alLanguages) == 0)
            {
                throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, "The web site has any language inserted. Please add a new language before adding a page"));
            }

            $rollBack = false;
            $this->connection->beginTransaction();
            
            if(0 === AlPageQuery::create()->setContainer($this->container)->activePages()->count())
            {
                $isHome = 1;
            }
            else
            {
                $isHome = (isset($values['isHome'])) ? $values['isHome'] : 0;

                // Resets the column mainLanguage is the new one will be the main language
                if ($isHome == 1) $rollBack = !$this->resetHome();
            }
            
            if(!$rollBack)
            {
                // Save page
                $alPage = new AlPage();
                $alPage->setTemplateName($values['template']);
                $alPage->setPageName(AlToolkit::slugify($values['pageName']));
                $alPage->setIsHome(($isHome == 1) ? $isHome : 0);
                $result = $alPage->save();
                if ($alPage->isModified() && $result == 0)
                {
                    $rollBack = true;
                }
                else
                {
                    $this->alPage = $alPage;
                    $idPage = $this->alPage->getId();
                    foreach ($alLanguages as $alLanguage)
                    {
                        $pageAttributesParam = array_merge($values, array('idPage' => $idPage, 'idLanguage' => $alLanguage->getId()));
                        if(!$alLanguage->getMainLanguage()) $pageAttributesParam['languageName'] = $alLanguage->getLanguage();
                        $rollBack = !$this->addPageAttributesAndBlocks($pageAttributesParam, $alLanguage);
                        if($rollBack)
                        {  
                            break;
                        }                        
                    }
                }
            }
            
            if (!$rollBack)
            {
                $this->connection->commit();
                
                if(null !== $dispatcher)
                {
                    $event = new  Content\Page\AfterPageAddedEvent($this);
                    $dispatcher->dispatch(PageEvents::AFTER_ADD_PAGE, $event);
                }
                
                return true;
            }
            else
            {
                $this->connection->rollback();
                return false;
            }
        }
        catch(\Exception $e)
        {
            if(isset($this->connection) && $this->connection !== null) $this->connection->rollback();
            throw $e;
        }
    }

    /**
     * Check if the given page already exists
     * 
     * @param string $pageName
     * @return Boolean 
     */
    protected function pageExists($pageName)
    {
        $pageName = AlToolkit::slugify($pageName);
        $alPage = AlPageQuery::create()->setContainer($this->container)->fromPageName($pageName)->findOne();
        
        return (isset($alPage)) ? true : false;
    }

    /**
     * Edits the managed page object
     * 
     * @param array $values
     * @return Boolean 
     */
    protected function edit(array $values)
    {
        try
        {
            $dispatcher = $this->container->get('event_dispatcher');
            if(null !== $dispatcher)
            {
                $event = new  Content\Page\BeforePageEditingEvent($this, $values);
                $dispatcher->dispatch(PageEvents::BEFORE_EDIT_PAGE, $event);

                if($event->isAborted())
                {
                    throw new \RuntimeException(AlToolkit::translateMessage($this->container, "The page editing action has been aborted", array(), 'al_page_manager_exceptions'));
                }

                if($values !== $event->getValues())
                {
                    $values = $event->getValues();
                }
            }

            $this->checkEmptyParams($values);
            
            $idLanguage = 0;
            $attributeParams = array('permalink', 'title', 'description', 'keywords');
            if(count(array_intersect($attributeParams, $values)) > 0)
            {
                $this->checkRequiredParamsExists(array('languageId' => ''), $values); 
                $idLanguage = $values['languageId'];
            }
        
            $rollBack = false;
            $this->connection->beginTransaction();
            
            $templateChanged = '';
            if(isset($values['template']) && $values['template'] != "")
            {
                $templateChanged = $this->alPage->getTemplateName(); 
                if($templateChanged == $values['template']) $templateChanged = '';
                $this->alPage->setTemplateName($values['template']);
            }
            
            if(isset($values['pageName']) && $values['pageName'] != "" && $this->alPage->getPageName() != $values['pageName'])
            {
                if ($this->pageExists($values['pageName']))
                {
                    throw new \InvalidArgumentException(AlToolkit::translateMessage($this->container, "The name to assign to the page already exists in the website. Page name must be unique."));
                }

                $this->alPage->setPageName(AlToolkit::slugify($values['pageName']));
            }
            
            if(isset($values['isHome']) && $values['isHome'] != "" && $values['isHome'] != 0)
            {
                $rollback = !$this->resetHome();
                if(!$rollback)
                {
                    $this->alPage->setIsHome(1);
                }
            }
            
            $result = $this->alPage->save(); 
            if ($this->alPage->isModified() && $result == 0)
            {
                $rollBack = true;
            }

            if (!$rollBack)
            {
                if($idLanguage != 0)
                {
                    $c = new \Criteria();
                    $c->add(AlPageAttributePeer::TO_DELETE, 0);
                    $c->add(AlPageAttributePeer::LANGUAGE_ID, $idLanguage);
                    $pageAttributes = $this->alPage->getAlPageAttributes($c);  
                    if(count($pageAttributes) == 0)
                    {                        
                        $rollBack = !$this->addPageAttributesAndBlocks(array_merge($values, array('idPage' => $this->alPage->getId(), 'idLanguage' => $idLanguage)));                        
                    }
                    else
                    {
                        if($templateChanged != '')
                        {
                            $previousTemplate = new AlTemplateManager($this->container, null, null, null, $templateChanged);
                            $newTemplate = new AlTemplateManager($this->container, null, null, null, $values['template']);
                            $templateChanger = new AlTemplateChanger($this->container, $previousTemplate, $newTemplate);
                            $rollBack = !$templateChanger->change();
                        }
                        
                        if(!$rollBack)
                        {
                            $alPageAttributesManager = $this->container->get('al_page_attributes_manager');
                            $alPageAttributesManager->set($pageAttributes[0]);
                            $result = $alPageAttributesManager->save(array_merge($values, array('idPage' => $this->alPage->getId(), 'idLanguage' => $idLanguage)));
                            if(null !== $result)
                            {
                                $rollBack = !$result;
                            }
                        }
                    }
                }
            }

            if (!$rollBack)
            {
                $this->connection->commit();
                
                if(null !== $dispatcher)
                {
                    $event = new  Content\Page\AfterPageEditedEvent($this);
                    $dispatcher->dispatch(PageEvents::AFTER_EDIT_PAGE, $event);
                }
                
                return true;
            }
            else
            {
                $this->connection->rollback();
                return false;
            }
        }
        catch(\Exception $e)
        {
            if(isset($this->connection) && $this->connection !== null) $this->connection->rollback();
            throw $e;
        }
    }
    
    /**
     * Deletes the blocks and page attributes for the given language
     * 
     * @param type $idLanguage
     * @return Boolean 
     */
    public function deleteBlocksAndPageAttributes($idLanguage = null)
    {
        try
        {
            $rollBack = false;
            $this->connection->beginTransaction();
            
            $c = new \Criteria();
            $c->add(AlPageAttributePeer::TO_DELETE, 0);
            if(null !== $idLanguage) $c->add(AlPageAttributePeer::LANGUAGE_ID, $idLanguage);
            $pageAttributes = $this->alPage->getAlPageAttributes($c);
            
            $pageAttributesManager = $this->container->get('al_page_attributes_manager');
            foreach($pageAttributes as $pageAttribute)
            {
                $pageAttributesManager->set($pageAttribute);
                $pageAttributesManager->delete();
            }
            
            $templateManager = $this->getTemplateManager();
            foreach($templateManager->getSlotManagers() as $slotManager)
            {
                if(strtolower($slotManager->getRepeated()) == 'page') $slotManager->deleteBlocks();
            }

            if (!$rollBack)
            {
                $this->connection->commit();
                return true;
            }
            else
            {
                $this->connection->rollback();
                return false;
            }
        }
        catch(\Exception $e)
        {
            if(isset($this->connection) && $this->connection !== null) $this->connection->rollback();
            throw $e;
        }
    }
    
    /**
     * Creates a new AlTemplateManager object from the current page and language
     * 
     * @param AlLanguage $alLanguage
     * @return AlTemplateManager 
     */
    protected function getTemplateManager(AlLanguage $alLanguage = null)
    {
        return new AlTemplateManager($this->container, $this->alPage, $alLanguage);
    }

    /**
     * Adds the page attribute and contents for the current page
     * 
     * @param type $pageAttributesParameters
     * @param AlLanguage $language  When null it uses the language stored in the current page tree object
     * @return Boolean 
     */
    protected function addPageAttributesAndBlocks($pageAttributesParameters, AlLanguage $language = null)
    {
        try
        {
            $rollBack = false;
            $this->connection->beginTransaction();
            $alPageAttributesManager = $this->container->get('al_page_attributes_manager');
            $alPageAttributesManager->set(null);
            $rollBack = !$alPageAttributesManager->save($pageAttributesParameters); 
           
            if(!$rollBack)
            {
                $templateManager = $this->getTemplateManager($language); 
                $rollBack = !$templateManager->populate();
            }
            
            if (!$rollBack)
            {
                $this->connection->commit();
                return true;
            }
            else
            {
                $this->connection->rollback();
                return false;
            }
        }
        catch(\Exception $e)
        {
            if(isset($this->connection) && $this->connection !== null) $this->connection->rollback();
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
        try
        {
            $page = AlPageQuery::create()->setContainer($this->container)->homePage()->findOne();
            if(null !== $page)
            {
                $page->setIsHome(0);
                $result = $page->save();

                if($page->isModified() && $result == 0) return false;
            }

            return true;
        }
        catch(\Exception $e)
        {
            throw new \RuntimeException($e->getMessage());
        }
    }
}