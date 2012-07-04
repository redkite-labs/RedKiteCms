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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\NavigationMenuBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPageAttributePeer;

/**
 * AlBlockManagerMenu
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerNavigationMenu extends AlBlockManager
{
 
    /**
     * @see AlBlockManager::getDefaultValue()
     *
     */
    public function getDefaultValue()
    {
        return array("HtmlContent" => "<ul><li>En</li></ul>");
    }
   
    public function getHtmlContent()
    {
        $content = '';
        $languages = \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlLanguageQuery::create()->setContainer($this->container)->activeLanguages()->find();
        foreach($languages as $language)
        {
            /*
            if(!$this->container->get('al_page_tree')->isCmsMode())
            {}
            else
            {   
                $frontController = $this->container->get('kernel')->getEnvironment() . '.php';            
                $content .= sprintf('<li><a href="/%s/%s/%s">%s</a></li>', $frontController, $language->getLanguage(), $this->container->get('al_page_tree')->getAlPage()->getPageName(), $language->getLanguage());
            }  */
            
                /* TODO
                $c = new \Criteria();
                $c->add(AlPageAttributePeer::TO_DELETE, 0);
                $c->add(AlPageAttributePeer::LANGUAGE_ID, $language->getId());
                $pageAttributes = $this->container->get('al_page_tree')->getAlPage()->getAlPageAttributes($c);            
                $permalink = $pageAttributes[0]->getPermalink();
                 * 
                 */
                //str_replace('-', '_', $language) . '_' . str_replace('-', '_', $this->container->get('al_page_tree')->getAlPage()->getPageName())
                $permalink = "";
                $languageName = $language->getLanguage();
                
                try
                {
                    $route = sprintf('_%s_%s', $language, str_replace('-', '_', $this->container->get('al_page_tree')->getAlPage()->getPageName()));
                    $url = $this->container->get('router')->generate($route);
                    
                }
                catch(\Exception $ex)
                {
                    $url = "#";
                    $languageName .= " Err!";
                }
                
                $content .= sprintf('<li><a href="%s">%s</a></li>', $url, $languageName);
              
                    /*
                $c = new \Criteria();
                $c->add(AlPageAttributePeer::TO_DELETE, 0);
                $c->add(AlPageAttributePeer::LANGUAGE_ID, $language->getId());
                $pageAttributes = $this->container->get('al_page_tree')->getAlPage()->getAlPageAttributes($c);            
                $permalink = $pageAttributes[0]->getPermalink();

                try
                {
                    $route = sprintf('_%s_%s', $language->getLanguage(), $pageAttributes = $this->container->get('al_page_tree')->getAlPage()->getPageName());
                    $url = $this->container->get('router')->generate($route);
                    $content .= sprintf('<li><a href="%s">%s</a></li>', $url, $language->getLanguage());
                }
                catch(\Exception $ex)
                {
                    $frontController = $this->container->get('kernel')->getEnvironment() . '.php';            
                    $content .= sprintf('<li><a href="/%s/%s/%s">%s</a></li>', $frontController, $language->getLanguage(), $this->container->get('al_page_tree')->getAlPage()->getPageName(), $language->getLanguage());
                }
                */
            
        }
        
        return sprintf('<ul>%s</ul>', $content);
    }
    
    public function getHtmlContentCMSMode()
    {
        $content = '';
        $languages = \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\AlLanguageQuery::create()->setContainer($this->container)->activeLanguages()->find();
        foreach($languages as $language)
        {
            $frontController = $this->container->get('kernel')->getEnvironment() . '.php';            
            $content .= sprintf('<li><a href="/%s/%s/%s">%s</a></li>', $frontController, $language->getLanguage(), $this->container->get('al_page_tree')->getAlPage()->getPageName(), $language->getLanguage());
        }
        
        return sprintf('<ul>%s</ul>', $content);
    }
}
