<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\ThemeChanger;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\ThemeChanger\Exception\ChangeSlotException;

/**
 * AlThemeChanger is deputated to change the website template
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlThemeChanger
{
    protected $templateManager;
    protected $factoryRepository;
    protected $languagesRepository;
    protected $pagesRepository;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager              $templateManager
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $factoryRepository
     */
    public function __construct(AlTemplateManager $templateManager, AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->templateManager = $templateManager;
        $this->factoryRepository = $factoryRepository;
        $this->languagesRepository = $this->factoryRepository->createRepository('Language');
        $this->pagesRepository = $this->factoryRepository->createRepository('Page');
    }

    /**
     * Changes the current theme
     *
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface $previousTheme
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface $theme
     * @param string                                                     $path
     * @param array                                                      $templatesMap
     */
    public function change(AlThemeInterface $previousTheme, AlThemeInterface $theme, $path, array $templatesMap)
    {
        $this->saveThemeStructure($previousTheme, $path);
        $this->changeTemplate($theme, $templatesMap);
    }

    /**
     * Changes the website templates with the new ones provided into the $templatesMap
     * array
     *
     * @param  \RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface $theme
     * @param  array                                                      $templatesMap
     * @throws \Exception
     */
    protected function changeTemplate(AlThemeInterface $theme, array $templatesMap)
    {
        $ignoreRepeatedSlots = false;
        foreach ($this->languagesRepository->activeLanguages() as $language) {
            foreach ($this->pagesRepository->activePages() as $page) {
                $templateName = $page->getTemplateName();
                if ( ! array_key_exists($templateName, $templatesMap)) {
                    continue;
                }

                $page->setTemplateName($templatesMap[$templateName]);
                $page->save();

                $template = $theme->getTemplate($page->getTemplateName());
                $this->templateManager
                    ->refresh($theme->getThemeSlots(), $template);

                $this->templateManager->populate($language->getId(), $page->getId(), $ignoreRepeatedSlots);
                $ignoreRepeatedSlots = true;
            }
        }
    }

    /**
     * Saves the current theme structure into a file
     *
     * @param  \RedKiteLabs\ThemeEngineBundle\Core\Theme\AlThemeInterface $theme
     * @param  type                                                       $themeStructureFile
     * @throws \Exception
     */
    protected function saveThemeStructure(AlThemeInterface $theme, $themeStructureFile)
    {
        $templates = array();
        foreach ($this->languagesRepository->activeLanguages() as $language) {
            foreach ($this->pagesRepository->activePages() as $page) {
                $key = $language->getId() . '-' . $page->getId();
                $templates[$key] = $page->getTemplateName();
            }
        }

        $themeName = $theme->getThemeName();
        $currentTheme = array(
            "Theme" => $themeName,
            "Templates" => $templates,
        );

        file_put_contents($themeStructureFile, json_encode($currentTheme));
    }
}