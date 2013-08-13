<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Translator;

use Symfony\Component\Translation\TranslatorInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Configuration\AlConfigurationInterface;

/**
 * A base class to add translation capabilities to derived objects
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
class AlTranslator implements AlTranslatorInterface
{
    protected $translator;
    protected $configuration;
    
    /**
     * Constructor
     *
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
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
     * @param  \Symfony\Component\Translation\TranslatorInterface           $translator
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Translator\AlTranslator
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
     * @return \Symfony\Component\Translation\TranslatorInterface
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
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Configuration\AlConfigurationInterface $configuration
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Translator\AlTranslator
     */
    public function setConfiguration(AlConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Returns the Configuration object
     *
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Configuration\AlConfigurationInterface
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
    public function translate($message, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        if (null !== $this->configuration) {        
            if (null === $locale) {
                $locale = $this->configuration->read('language');
            }
            /**/
            if ($domain != 'messages') {
                //$domain = $locale . "_" . $domain;
                $domain = 'AlphaLemonCmsBundle';
            }
        }
        
        if (null !== $this->translator) {
            $message = $this->translator->trans($message, $parameters, $domain, $locale);
        }
        
        return $message;
    }
}
