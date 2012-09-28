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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues;

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\PageRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface;
use AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveTheme;
use AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection;

/**
 * Retrieves form the database the values used in the forms
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class ChoiceValues
{
    public static function getPages(PageRepositoryInterface $pageRepository, $withNoneOption = true)
    {
        $result = array();
        if ($withNoneOption) $result["none"] = " ";
        $pages = $pageRepository->activePages();
        foreach ($pages as $page) {
            $result[$page->getId()] = $page->getPageName();
        }

        return $result;
    }

    public static function getLanguages(LanguageRepositoryInterface $languageRepository, $withNoneOption = true)
    {
        $result = array();
        if($withNoneOption) $result["none"] = " ";
        $languages = $languageRepository->activeLanguages();
        foreach ($languages as $language) {
            $result[$language->getId()] = $language->getLanguageName();
        }

        return $result;
    }

    public static function getTemplates(AlActiveTheme $activeTheme, AlThemesCollection $themes)
    {
        $theme = $themes->getTheme($activeTheme->getActiveTheme());

        $templates = array("none" => " ");
        foreach ($theme as $template) {
            $templateName = $template->getTemplateName();
            $templates[$templateName] = $templateName;
        }

        return $templates;
    }
}
