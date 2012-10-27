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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Language;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Seo\AlSeoManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Listen to the onBeforeAddLanguageCommit event to copy blocks from a language
 * to the adding language
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AddLanguageSeoListener extends Base\AddLanguageBaseListener
{
    private $seoManager;

    /**
     * Constructor
     *
     * @param AlSeoManager $seoManager
     */
    public function __construct(AlSeoManager $seoManager, ContainerInterface $container = null)
    {
        parent::__construct($container);

        $this->seoManager = $seoManager;
    }

    /**
     *{ @inheritdoc }
     *
     * @return A model collection instance depending on the used ORM (i.e PropelCollection)
     */
    protected function setUpSourceObjects()
    {
        return $this->seoManager
                    ->getSeoRepository()
                    ->fromLanguageId($this->getBaseLanguage()->getId());
    }

    /**
     * { @inheritdoc }
     *
     * @param  array   $values
     * @return boolean
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
