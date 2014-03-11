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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Translator;

use Symfony\Component\Translation\TranslatorInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Configuration\AlConfigurationInterface;

/**
 * A base class to add translation capabilities to derived objects
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlTranslator implements AlTranslatorInterface
{
    /** @var TranslatorInterface */
    protected $translator;
    /** @var AlConfigurationInterface */
    protected $configuration;

    /**
     * Constructor
     *
     * @param TranslatorInterface      $translator
     * @param AlConfigurationInterface $configuration
     *
     * @api
     */
    public function __construct(TranslatorInterface $translator = null, AlConfigurationInterface $configuration = null)
    {
        $this->translator = $translator;
        $this->configuration = $configuration;
    }

    /**
     * Sets the tranlator object
     *
     * @param  TranslatorInterface $translator
     * @return AlTranslator
     *
     * @api
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * Returns the Translator object
     *
     * @return TranslatorInterface
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
     * @param  AlConfigurationInterface $configuration
     * @return AlTranslator
     */
    public function setConfiguration(AlConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Returns the Configuration object
     *
     * @return AlConfigurationInterface
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
