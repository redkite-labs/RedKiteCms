<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\ThemeEngineBundle\Core\PageTree\PageBlocks;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\Entities\BlockModelInterface;
use AlphaLemon\ThemeEngineBundle\Core\Exception\InvalidArgumentException;
use AlphaLemon\ThemeEngineBundle\Core\PageTree\Exception\AnyValidArgumentGivenException;

/**
 * AlPageBlocks is the object responsible to manage the blocks on a web page. A block on a web
 * page contains a single content.
 *
 *
 * Providing the page id and language id, it retrieves the contents and arrange them
 * into an array which keys are the name of slot where the contents live.
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlPageBlocks implements AlPageBlocksInterface
{
    protected $blocks = array();
    //protected $requiredParamsOptions = array('Content' => '');

    /**
     * {@inheritdoc}
     */
    public function add($slotName, $value, $position = null)
    {
        /*
        $value = array_intersect_key($values, $this->requiredParamsOptions);
        if (empty($value)) {
            throw new AnyValidArgumentGivenException(sprintf('Any valid option have been given. Add was expecting "%s" but receives "%s"', implode(',', array_keys($this->requiredParamsOptions)), implode(',', array_keys($values))));
        }*/

        if(null !== $position && array_key_exists($position, $this->blocks[$slotName]))
        {
            $this->blocks[$slotName][$position] = $value;
        }
        else
        {
            $this->blocks[$slotName][] = $value;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addRange(array $values, $override = false)
    {
        foreach($values as $slotName => $contents)
        {
            if (array_key_exists($slotName, $this->blocks) && $override) {
                $this->clearSlotBlocks($slotName);
            }

            if(null !== $contents)
            {
                foreach($contents as $content)
                {
                    $this->add($slotName, $content);
                }
            }
            else
            {
                $this->blocks[$slotName] = null;
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearSlotBlocks($slotName)
    {
        $this->checkSlotExists($slotName);

        $this->blocks[$slotName] = array();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearSlots()
    {
        foreach ($this->blocks as $slotName => $block) {
            $this->clearSlotBlocks($slotName);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeSlot($slotName)
    {
        $this->checkSlotExists($slotName);

        unset($this->blocks[$slotName]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeSlots()
    {
        $this->blocks = array();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlotBlocks($slotName)
    {
        return (array_key_exists($slotName, $this->blocks)) ? $this->blocks[$slotName] : array();
    }

    protected function checkSlotExists($slotName)
    {
        if (!array_key_exists($slotName, $this->blocks)) {
            throw new InvalidArgumentException(sprintf('The slot "%s" does not exist. Nothing to clear', $slotName));
        }
    }
}