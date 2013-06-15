<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator;

use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface;

/**
 * AlParametersValidatorLanguageManager adds specific validations for languages
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
class AlParametersValidatorLanguageManager extends AlParametersValidator
{
    protected $factoryRepository = null;
    protected $languageRepository;

    /**
     * Constructor
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $factoryRepository
     * 
     * @api
     */
    public function __construct(AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->factoryRepository = $factoryRepository;
        $this->languageRepository = $this->factoryRepository->createRepository('Language');
    }
    
    /**
     * Sets the language model
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface $v
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorLanguageManagerConstructor
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
     * @return \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface
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
