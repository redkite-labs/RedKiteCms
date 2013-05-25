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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\ThemeChanger;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocksTemplateChanger;
use AlphaLemon\AlphaLemonCmsBundle\Core\ThemeChanger\Exception\ThemeSlotsInvalidConfigurationException;

/**
 * AlTemplateSlots is deputated to fetch the slots from the previous theme structure
 * and group them by repeated status
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlTemplateSlots
{
    protected $container;
    protected $factoryRepository;
    private $pageBlocks;
    private $templateManager;
    private $slots = array();
    
    /**
     * Constructor
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * Returns the slots
     * 
     * @return array
     */
    public function getSlots()
    {
        return $this->slots;
    }
    
    /**
     * Runs the process
     * 
     * @param int $languageId
     * @param int $pageId
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\ThemeChanger\AlTemplateSlots
     */
    public function run($languageId, $pageId)
    {   
        $previousThemeFile = $this->container->getParameter('alpha_lemon_cms.theme_structure_file');
        if (file_exists($previousThemeFile)) {
            $this->factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
            $themes = $this->container->get('alpha_lemon_theme_engine.themes');
            $this->initPagesBlocks($languageId, $pageId);
            
            $previousThemeStructure = json_decode(file_get_contents($previousThemeFile), true);
            $previousThemeName = $previousThemeStructure['Theme'];
            $templateKey = $languageId . '-' . $pageId;
            if ( ! array_key_exists($templateKey, $previousThemeStructure["Templates"])) {
                return $this;
            }
            $previousTemplateName = $previousThemeStructure["Templates"][$templateKey];
            $previousTheme = $themes->getTheme($previousThemeName);   
            $template = $previousTheme->getTemplate($previousTemplateName);                         
            $this->initTemplateManager($template);
            
            $this->setUpSlots();
        }
        
        return $this;
    }

    private function initPagesBlocks($languageId, $pageId)
    {
        $this->pageBlocks = new AlPageBlocksTemplateChanger($this->factoryRepository);            
        $this->pageBlocks
            ->setIdLanguage($languageId)
            ->setIdPage($pageId)
            ->refresh()
        ;
    }
    
    private function initTemplateManager($template)
    {
        $this->templateManager = new AlTemplateManager(
            $this->container->get('alpha_lemon_cms.events_handler'),
            $this->factoryRepository,
            $template,
            $this->pageBlocks,
            $this->container->get('alpha_lemon_cms.block_manager_factory')    
        );
    }
    
    private function setUpSlots()
    {
        if (null === $this->pageBlocks || null === $this->templateManager) {
            throw new ThemeSlotsInvalidConfigurationException();
        }
    
        $viewsRenderer = $this->container->get('alpha_lemon_cms.view_renderer');
            
        $slotManagers = $this->templateManager
            ->refresh()
            ->getSlotManagers(true)
        ;
        
        foreach($slotManagers as $slotManager) {
            $slotName = $slotManager->getSlotName();
            $blockManagers = $slotManager->getBlockManagers();
            
            if (empty($blockManagers)) {
                continue;
            }
            
            $toDelete = 0;
            $slotContents = array();
            foreach($blockManagers as $blockManager) {
                if (null !== $blockManager) {
                    $content = $blockManager
                        ->setEditorDisabled(true)
                        ->getHtml()
                    ;
                    
                    $slotContents[] = (is_array($content)) ? $viewsRenderer->render($content['RenderView']) : $content; 
                    $toDelete = $blockManager->get()->getToDelete();
                }
            }
            
            $repeated = $slotManager->getRepeated();
            $this->slots[$repeated][$slotName] = array(
                'content' => implode("<br />", $slotContents),
                'used' => $toDelete,
            );
        }
    }
}
