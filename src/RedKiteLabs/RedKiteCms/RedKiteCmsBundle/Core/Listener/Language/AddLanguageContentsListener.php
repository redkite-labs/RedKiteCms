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

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Listen to the onBeforeAddLanguageCommit event to copy the contents from the main language
 * when a new language is adding
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AddLanguageContentsListener extends Base\AddLanguageBaseListener
{
    private $blockManager;

    /**
     * Constructor
     *
     * @param AlBlockManager $blockManager
     */
    public function __construct(AlBlockManager $blockManager)
    {
        $this->blockManager = $blockManager;
    }

    /**
     * {@inheritdoc}
     * 
     * @return A model collection instance depending on the used ORM (i.e PropelCollection) 
     */
    protected function setUpSourceObjects()
    {
        return $this->blockManager
                        ->getBlockModel()
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
        unset($values['Id']);
        unset($values['CreatedAt']);
        //TODO $values['HtmlContent'] = $this->fixInternalLinks($values['HtmlContent']);
        $values['LanguageId'] = $this->languageManager->get()->getId();
        $result = $this->blockManager
                    ->set(null)
                    ->save($values);

        return $result;
    }

    /**
     * TODO 
     * Fixes all the internal links according with the new language
     *
     * @param type $content
     * @return type
     */
    protected function fixInternalLinks($content)
    {
        if(null === $this->languageManager) {
            return $content;
        }
        
        //preg_match('/_(en)_[\w]+/s', $content, $matches);
        //print_r($matches);exit;

        $languageName =  $this->languageManager->get()->getLanguage();
        $content = preg_replace_callback('/(\<a[\s+\w+]href=[\"\'])(.*?)([\"\'])/s', function ($matches) use($router, $languageName) {

            $url = $matches[2];
            try
            {
                $tmpUrl = (empty($match) && substr($url, 0, 1) != '/') ? '/' . $url : $url;
                $params = $router->match($tmpUrl);

                $url = (!empty($params)) ? $languageName . '-' . $url : $url;
            }
            catch(ResourceNotFoundException $ex)
            {
                // Not internal route the link remains the same
            }

            return $matches[1] . $url . $matches[3];
        }, $content);

        return $content;
    }
}

