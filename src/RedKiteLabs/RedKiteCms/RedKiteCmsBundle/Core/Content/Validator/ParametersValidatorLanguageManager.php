<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface;

/**
 * ParametersValidatorLanguageManager adds specific validations for languages
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class ParametersValidatorLanguageManager extends ParametersValidator
{
    protected $factoryRepository = null;
    protected $languageRepository;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface $factoryRepository
     *
     * @api
     */
    public function __construct(FactoryRepositoryInterface $factoryRepository)
    {
        $this->factoryRepository = $factoryRepository;
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
    }

    /**
     * Sets the language model
     *
     * @param  \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface                 $v
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorLanguageManagerConstructor
     *
     * @api
     */
    public function setLanguageRepository(LanguageRepositoryInterface $v)
    {
        $this->languageRepository = $v;

        return $this;
    }

    /**
     * Gets the language model
     *
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface
     *
     * @api
     */
    public function getLanguageRepository()
    {
        return $this->languageRepository;
    }

    /**
     * Checks if any language exists
     *
     * @return boolean
     *
     * @api
     */
    public function hasLanguages()
    {
        return (count($this->languageRepository->activeLanguages()) > 0) ? true : false;
    }

    /**
     * Checks when the given language name exists
     *
     * @param  string  $laguageName
     * @return boolean
     *
     * @api
     */
    public function languageExists($laguageName)
    {
        $language = $this->languageRepository->fromLanguageName($laguageName);

        return (null !== $language && count($language) > 0) ? true : false;
    }
}
