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
 * BlocksAdderMenu builds the blocks adder menu structure
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlocksAdderMenu
{
	private $itemsPerColumns = 15;
	private $headerScore = 1.5;
	private $elementScore = 1;

    /**
     * Builds the menu
     * @param array $elements
     * @return array
     */
    public function build(array $elements)
	{
		$parsedElements = $this->parse($elements); 
		$result = $this->group($parsedElements);

        return $result;
	}

    /**
     * Sets the items displayed per column
     * @param int $value
     * @return self
     */
    public function itemsPerColumn($value)
    {
        $this->itemsPerColumns = $value;

        return $this;
    }

    /**
     * Sets the header score
     *
     * @param float $value
     * @return self
     */
    public function headerScore($value)
    {
        $this->headerScore = $value;

        return $this;
    }

    /**
     * Sets the element score
     *
     * @param float $value
     * @return self
     */
    public function elementScore($value)
    {
        $this->elementScore = $value;

        return $this;
    }
	
	private function parse(array $elements)
	{
		$result = array();
		foreach($elements as $name => $children) {
			$item = new BlocksAdderMenuItem();
			$item->addHeader($name);
			$result[] = $item;
			$result = array_merge($result, $this->addElements($children));
		}
		
		return $result;
	}
		
	private function addElements($elements)
	{
		$result = array();
		foreach($elements as $blockType => $element) {
			$item = new BlocksAdderMenuItem();
			$item->addElement($blockType, $element);
			$result[] = $item;
		}
		
		return $result;
	}
		
	private function group(array $elements)
	{
		$score = 0;
		$x = array();
		$result = array();
		foreach ($elements as $item) {
			$isHeader = $item->isHeader();
			$points = $isHeader ? $this->headerScore : $this->elementScore;
			if ($score + $points > $this->itemsPerColumns || ($isHeader && $score + $points + $this->elementScore > $this->itemsPerColumns)) {
				$result[] = $x;
				$x = array();
				$score = 0;
			}
			$x[] = $item;
			$score += $points;
		}
		$result[] = $x;
		
		return $result;
	}
}