<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageModelation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    GPL LICENSE Version 2.0
 * 
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Model\Orm;

/**
 * ModelInterface
 * 
 * @author alphalemon <webmaster@alphalemon.com>
 */
interface SeoModelInterface {
    public function fromPK($id);
    public function fromPageAndLanguage($languageId, $pageId);
    public function fromPermalink($permalink, $languageId);
    public function fromPageId($pageId);
    public function fromLanguageId($languageId);
    public function fromPageIdWithLanguages($pageId);
}