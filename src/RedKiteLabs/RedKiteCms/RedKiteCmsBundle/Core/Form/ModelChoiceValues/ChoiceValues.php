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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Form\ModelChoiceValues;

use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\SeoRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\ActiveTheme\AlActiveThemeInterface;
use RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\AlThemesCollection;

/**
 * Implements some static method to fetch the values used in the interfaces forms from
 * the database
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 * @codeCoverageIgnore
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

    public static function getTemplates(AlActiveThemeInterface $activeTheme, AlThemesCollection $themes)
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
        if (empty($language)) {
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
