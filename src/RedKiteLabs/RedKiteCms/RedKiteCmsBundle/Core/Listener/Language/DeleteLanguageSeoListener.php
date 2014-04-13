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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Listener\Language;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Seo\SeoManager;

/**
 * Listen to the onBeforeDeleteLanguageCommit event to delete the seo attributes which
 * belongs the language to remove
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class DeleteLanguageSeoListener extends Base\DeleteLanguageBaseListener
{
    /** @var SeoManager */
    private $seoManager;

    /**
     * Constructor
     *
     * @param SeoManager $seoManager
     */
    public function __construct(SeoManager $seoManager)
    {
        $this->seoManager = $seoManager;
    }

    /**
     *{@inheritdoc}
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
