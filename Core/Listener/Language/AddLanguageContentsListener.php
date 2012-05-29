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
 * to the adding one
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AddLanguageContentsListener
{
    private $blockManager;
    private $languageManager = null;

    /**
     * Constructor
     *
     * @param LanguageModelInterface $languageModel
     */
    public function __construct(AlBlockManager $blockManager)
    {
        $this->blockManager = $blockManager;
    }

    /**
     * Adds the contents for the page when a new page is added, for each language of the site
     *
     * @param BeforeAddPageCommitEvent $event
     * @throws Exception
     */
    public function onBeforeAddLanguageCommit(BeforeAddLanguageCommitEvent $event)
    {
        if ($event->isAborted()) {
            return;
        }

        $this->languageManager = $event->getContentManager();
        $languageModel = $languageManager->getLanguageModel();

        $mainLanguage = $this->languageModel->getMainLanguage();
        $blocks = $this->blockManager
                        ->getBlockModel()
                        ->fromLanguageId($mainLanguage->getId())
                        ->find();
        if (count($blocks) > 0) {
            try {
                $languageModel->startTransaction();
                foreach($blocks as $block)
                {
                    $values = $block->toArray();
                    unset($values['Id']);
                    unset($values['CreatedAt']);
                    $values['HtmlContent'] = $this->fixInternalLinks($values['HtmlContent']);
                    $values['LanguageId'] = $this->alLanguage->getId();
                    $this->blockManager
                            ->set(null)
                            ->save($values);
                }
            }
            catch(\Exception $e) {
                $event->abort();
                if (isset($languageModel) && $languageModel !== null) {
                    $languageModel->rollBack();
                }

                throw $e;
            }
        }
    }

    /**
     * Fixes all the internal links according with the new language
     *
     * @param string $content
     * @return string
     */
    protected function fixInternalLinks($content)
    {
        if(null === $this->languageManager) {
            return $content;
        }

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

