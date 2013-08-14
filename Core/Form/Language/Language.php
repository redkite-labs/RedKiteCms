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
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Core\Form\Language;

use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Defines the embedded object mapped with the languages form
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class Language
{
    /**
     * @Assert\NotBlank(message = "The language name value should not be blank")
     */
    protected $language;

    /**
     * @Assert\Type("boolean")
     */
    protected $isMain = false;

    /**
     * Fetches the language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Sets the language
     *
     * @return string
     */
    public function setLanguage($v)
    {
        $this->language = $v;
    }

    public function getIsMain()
    {
        return $this->isMain;
    }

    public function setIsMain($v)
    {
        $this->isMain = $v;
    }
}
