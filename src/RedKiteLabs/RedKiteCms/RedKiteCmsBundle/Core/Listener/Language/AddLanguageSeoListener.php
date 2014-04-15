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
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Listen to the onBeforeAddLanguageCommit event to copy seo attributes from the
 * main language to the new one
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AddLanguageSeoListener extends Base\AddLanguageBaseListener
{
    /** @var SeoManager */
    private $seoManager;

    /**
     * Constructor
     *
     * @param SeoManager       $seoManager
     * @param ContainerInterface $container
     *
     * @api
     */
    public function __construct(SeoManager $seoManager, ContainerInterface $container = null)
    {
        parent::__construct($container);

        $this->seoManager = $seoManager;
    }

    /**
     *{@inheritdoc}
     */
    protected function setUpSourceObjects()
    {
        return $this->seoManager
                    ->getSeoRepository()
                    ->fromLanguageId($this->getBaseLanguage()->getId());
    }

    /**
     * {@inheritdoc}
     */
    protected function copy(array $values)
    {
        unset($values['Id']);
        $language = $this->languageManager->get();
        $languageName = $language->getLanguageName();
        $values['LanguageId'] = $language->getId();
        $values['LanguageName'] = $languageName;
        $values['Permalink'] = $languageName . '-' . $values['Permalink'];
        $result = $this->seoManager
                    ->set(null)
                    ->save($values);

        return $result;
    }
}
