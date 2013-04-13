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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues;

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\PageRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\SeoRepositoryInterface;
use AlphaLemon\ThemeEngineBundle\Core\Theme\AlActiveTheme;
use AlphaLemon\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection;

/**
 * Implements some static method to fetch the values used in the interfaces forms from 
 * the database
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
    
    public static function getPermalinks(SeoRepositoryInterface $seoRepository, $language, $withNoneOption = true)
    {
        if (empty($language)){
            return array();   
        }
        
        $seoAttributes = (is_numeric($language)) ? $seoRepository->fromLanguageId($language) : $seoRepository->fromLanguageName($language);

        $permalinks = array();
        if ($withNoneOption) $permalinks["none"] = " ";
        foreach ($seoAttributes as $seoAttribute) {
            $permalink = $seoAttribute->getPermalink();
            $permalinks[$permalink] = $permalink;
        }
        sort($permalinks);

        return $permalinks;
    }
}
