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
 * Description of AlThemeSlots
 *
 * @author alphalemon
 */
class AlTemplateSlots
{
    protected $container;
    protected $factoryRepository;
    private $pageBlocks;
    private $templateManager;
    private $slots = array();
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function setTemplateManager($v)
    {
        $this->templateManager = $v;
    }
    
    public function getTemplateManager()
    {
        return $this->templateManager;
    }
    
    public function setPageBlocks($v)
    {
        $this->pageBlocks = $v;
    }
    
    public function getPageBlocks()
    {
        return $this->pageBlocks;
    }
    
    public function getSlots()
    {
        return $this->slots;
    }
    
    public function run($languageId, $pageId)
    {   
        $previousThemeFile = $this->container->getParameter('kernel.root_dir') . '/Resources/previous_theme';
        if (file_exists($previousThemeFile)) {
            $this->factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
            $themes = $this->container->get('alpha_lemon_theme_engine.themes');
            $this->initPagesBlocks($languageId, $pageId);
            
            $previousThemeStructure = json_decode(file_get_contents($previousThemeFile), true);
            $previousThemeName = $previousThemeStructure['Theme'];
            $previousTemplateName = $previousThemeStructure["Templates"][$languageId . '-' . $pageId];
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

            $this->slots[$slotName] = array(
                'content' => implode("<br />", $slotContents),
                'used' => $toDelete,
            );
        }
    }
}
