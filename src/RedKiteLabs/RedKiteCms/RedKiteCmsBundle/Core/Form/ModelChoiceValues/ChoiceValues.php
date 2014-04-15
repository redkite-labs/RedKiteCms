<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\ModelChoiceValues;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\PageRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\SeoRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\ActiveTheme\ActiveThemeInterface;
use RedKiteLabs\ThemeEngineBundle\Core\ThemesCollection\ThemesCollection;

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

    public static function getTemplates(ActiveThemeInterface $activeTheme, ThemesCollection $themes)
    {
        $theme = $themes->getTheme($activeTheme->getActiveTheme()->getThemeName());

        $templates = array();
        foreach ($theme as $template) {
            $templateName = $template->getTemplateName();
            $templates[$templateName] = $templateName;
        }

        ksort($templates);
        $templates = array_merge(array("none" => " "), $templates);

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
