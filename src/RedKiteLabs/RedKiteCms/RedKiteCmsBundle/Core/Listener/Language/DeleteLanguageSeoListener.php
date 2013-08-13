<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Listener\Language;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Seo\AlSeoManager;

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
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Seo\AlSeoManager $seoManager
     */
    public function __construct(AlSeoManager $seoManager)
    {
        $this->seoManager = $seoManager;
    }

    /**
     *{ @inheritdoc }
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
     */
    protected function delete($object)
    {
        $result = $this->seoManager
                    ->set($object)
                    ->delete();

        return $result;
    }
}
