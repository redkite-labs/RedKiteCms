<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Listener\Language;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Listen to the onBeforeAddLanguageCommit event to copy blocks from a language
 * to the adding language
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AddLanguageBlocksListener extends Base\AddLanguageBaseListener
{
    private $blockManager;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager $blockManager
     * @param \Symfony\Component\DependencyInjection\ContainerInterface       $container
     *
     * @api
     */
    public function __construct(AlBlockManager $blockManager, ContainerInterface $container = null)
    {
        parent::__construct($container);

        $this->blockManager = $blockManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpSourceObjects()
    {
        $baseLanguage = $this->getBaseLanguage();

        return $this->blockManager
                        ->getBlockRepository()
                        ->fromLanguageId($baseLanguage->getId());
    }

    /**
     * {@inheritdoc}
     */
    protected function copy(array $values)
    {
        unset($values['Id']);
        unset($values['CreatedAt']);
        $values['Content'] = $this->configurePermalinkForNewLanguage($values['Content']);
        $values['LanguageId'] = $this->languageManager->get()->getId();
        $result = $this->blockManager
                    ->set(null)
                    ->save($values);

        return $result;
    }

    /**
     * Configures the permalink for the new language.
     *
     * The content is parsed to find links. When at least a link is found it is retrieved and matched to find
     * if it is an internal link. When it is an internal link, it is prefixed with the new language as follows:
     * [new_language]-[permalink], otherwise it is left untouched
     *
     * @param  string $content
     * @return string
     */
    protected function configurePermalinkForNewLanguage($content)
    {
        if (null === $this->languageManager || null === $this->container) {
            return $content;
        }

        $urlManager = $this->container->get('red_kite_cms.url_manager');
        $languageName =  $this->languageManager->get()->getLanguageName();

        return preg_replace_callback('/(\<a[^\>]+href[="\'\s]+)([^"\'\s]+)?([^\>]+\>)/s', function ($matches) use ($urlManager, $languageName) {
            $url = $urlManager
                ->fromUrl($matches[2])
                ->getInternalUrl();

            return (null !== $url) ? $matches[1] . $languageName . '-' . $url . $matches[3] : $matches[1] . $matches[2] . $matches[3];
        }, $content);

        return $content;
    }
}
