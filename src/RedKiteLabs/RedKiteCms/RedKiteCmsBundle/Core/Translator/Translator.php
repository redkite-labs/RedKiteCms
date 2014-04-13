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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Translator;

use Symfony\Component\Translation\TranslatorInterface as SymfonyTranslatorInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Configuration\ConfigurationInterface;

/**
 * A base class to add translation capabilities to derived objects
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class Translator implements TranslatorInterface
{
    /** @var SymfonyTranslatorInterface */
    protected $translator;
    /** @var ConfigurationInterface */
    protected $configuration;

    /**
     * Constructor
     *
     * @param SymfonyTranslatorInterface $translator
     * @param ConfigurationInterface     $configuration
     *
     * @api
     */
    public function __construct(SymfonyTranslatorInterface $translator = null, ConfigurationInterface $configuration = null)
    {
        $this->translator = $translator;
        $this->configuration = $configuration;
    }

    /**
     * Sets the tranlator object
     *
     * @param  TranslatorInterface $translator
     * @return Translator
     *
     * @api
     */
    public function setTranslator(SymfonyTranslatorInterface $translator)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * Returns the Translator object
     *
     * @return SymfonyTranslatorInterface
     *
     * @api
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Sets the configuration object
     *
     * @param  ConfigurationInterface $configuration
     * @return Translator
     */
    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Returns the Configuration object
     *
     * @return ConfigurationInterface
     *
     * @api
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function translate($message, array $parameters = array(), $domain = 'RedKiteCmsBundle', $locale = null)
    {
        if (null !== $this->configuration) {
            if (null === $locale) {
                $locale = $this->configuration->read('language');
            }
        }

        if (null !== $this->translator) {
            $message = $this->translator->trans($message, $parameters, $domain, $locale);
        }

        return $message;
    }
}
