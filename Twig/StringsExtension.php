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

namespace RedKiteLabs\RedKiteCmsBundle\Twig;

/**
 * Adds some functions to Twig engine to manupulate strings from twig
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class StringsExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'strings';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'left' => new \Twig_Function_Method($this, 'left', array('is_safe' => array('html'),)),
            'right' => new \Twig_Function_Method($this, 'right', array('is_safe' => array('html'),)),
            'truncate' => new \Twig_Function_Method($this, 'truncate', array('is_safe' => array('html'),)),
        );
    }

    /**
     * Returns the left part of a string according the given length
     *
     * @param string $text
     * @param int $length
     * @return string
     */
    public function left($text, $length)
    {
        if (!$this->isValidLength($text, $length)) return $text;

        return \substr($text, 0, $length);
    }

    /**
     * Returns the right part of a string according the given length
     *
     * @param string $text
     * @param int $length
     * @return string
     */
    public function right($text, $length)
    {
        if (!$this->isValidLength($text, $length)) return $text;

        $textLength = strlen($text);

        return \substr($text, $textLength - $length, $textLength);
    }

    /**
     * Truncates the string at the given length
     *
     * @param string $text
     * @param int $length
     * @return string
     */
    public function truncate($text, $length = 15)
    {
        if (!$this->isValidLength($text, $length)) return $text;

        return (\strlen($text) > $length) ? $this->left($text, $length) . '...' : $text;
    }

    /**
     * Checks if given length is valid
     *
     * @param string $text
     * @param int $length
     * @return boolean
     */
    protected function isValidLength($text, $length)
    {
        return ($length <= 0 || $length > strlen($text)) ? false : true;
    }
}
