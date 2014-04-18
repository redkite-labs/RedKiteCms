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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\BlocksAdderMenu;

/**
 * BlocksAdderMenuItem represents an item in the BlocksAdderMenu
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlocksAdderMenuItem
{
	private $label;
    private $filter;
    private $blockType;
	private $type;

    /**
     * Adds a menu header
     *
     * @param string $label
     */
    public function addHeader($label)
	{
		$this->label = $label;
		$this->type = 'header';
	}

    /**
     * Adds a menu element
     *
     * @param array $blockType
     * @param $element
     */
    public function addElement($blockType, array $element)
	{
        $this->blockType = $blockType;
		$this->label = $element["description"];
        $this->filter = $element["filter"];
		$this->type = 'element';
	}

    /**
     * Checks when item is an header
     *
     * @return bool
     */
    public function isHeader()
	{
		return $this->type == 'header';
	}

    /**
     * Returns the label value
     *
     * @return mixed
     */
    public function getLabel()
	{
		return $this->label;
	}

    /**
     * Returns the filter value
     *
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Returns the block type value
     *
     * @return mixed
     */
    public function getBlockType()
    {
        return $this->blockType;
    }
}