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
 * Listen to the onBeforeAddLanguageCommit event to copy seo attributes from the
 * main language to the new one
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 * 
 * @api
 */
class AddLanguageSeoListener extends Base\AddLanguageBaseListener
{
    private $seoManager;

    /**
     * Constructor
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Seo\AlSeoManager $seoManager
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * 
     * @api
     */
    public function __construct(AlSeoManager $seoManager, ContainerInterface $container = null)
    {
        parent::__construct($container);

        $this->seoManager = $seoManager;
    }

    /**
     *{ @inheritdoc }
     */
    protected function setUpSourceObjects()
    {
        return $this->seoManager
                    ->getSeoRepository()
                    ->fromLanguageId($this->getBaseLanguage()->getId());
    }

    /**
     * { @inheritdoc }
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
