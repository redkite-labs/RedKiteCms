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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Language;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Seo\AlSeoManager;

/**
 * Listen to the onBeforeDeleteLanguageCommit event to delete the seo attributes which
 * belongs the language to remove
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class DeleteLanguageSeoListener extends Base\DeleteLanguageBaseListener
{
    private $seoManager;

    /**
     * Constructor
     *
     * @param AlSeoManager $seoManager
     */
    public function __construct(AlSeoManager $seoManager)
    {
        $this->seoManager = $seoManager;
    }

    /**
     *{ @inheritdoc }
     *
     * @return A model collection instance (i.e PropelCollection)
     */
    protected function setUpSourceObjects()
    {
        $language = $this->languageManager->get();
        if (null === $language) {
            return null;
        }

        return $this->seoManager
                    ->getSeoRepository()
                    ->fromLanguageId($language->getId());
    }

    /**
     * {@inheritdoc}
     *
     * @param AlSeo
     * @return boolean
     */
    protected function delete($object)
    {
        $result = $this->seoManager
                    ->set($object)
                    ->delete();

        return $result;
    }
}
