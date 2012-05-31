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
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Listen to the onBeforeAddLanguageCommit event to copy the seo attributes from the main language
 * to the new one
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
    public function __construct(AlSeoManager $seoManager)
    {
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
                    ->getSeoModel()
                    ->fromLanguageId($this->mainLanguage->getId());
    }

    /**
     * { @inheritdoc }
     * 
     * @param array $values
     * @return boolean 
     */
    protected function copy(array $values)
    {
        $values['idLanguage'] = $this->languageManager->get()->getId();
        $values['languageName'] = $this->languageManager->get()->getLanguage();
        $result = $this->seoManager
                    ->set(null)
                    ->save($values);

        return $result;
    }
}

