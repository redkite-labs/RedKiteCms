<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageContentsContainer;

/**
 *
 * @author test
 */
interface AlPageContentsContainerInterface
{    
    public function getBlocks();
    public function getSlotBlocks($slotName);
}