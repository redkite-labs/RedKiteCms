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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\BlocksAdderMenu;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\BlocksAdderMenu\BlocksAdderMenu;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\BlocksAdderMenu\BlocksAdderMenuItem;
use RedKiteLabs\ThemeEngineBundle\Tests\TestCase;

/**
 * ABlocksAdderMenuTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlocksAdderMenuTest extends TestCase
{
    /**
     * @dataProvider blocksProvider
     */
    public function testBuildMenu($blocks, $result)
    {
        $blocksAdderMenu = new BlocksAdderMenu();
        $this->assertEquals($result, $blocksAdderMenu
            ->itemsPerColumn(4)
            ->build($blocks)
        );
    }
    
    public function blocksProvider()
    {
        return array(
           array(
                array(
                    "Header-1" => array(
                        "Type-1" => array(
                            "description" => "Element 1",
                            "filter" => "none",
                        ),
                    )
                ),
                array(
                    array(
                        $this->createHeader("Header-1"),
                        $this->createElement("Type-1", array(
                                "description" => "Element 1",
                                "filter" => "none",
                            )
                        ),
                    ),
                ),
            ),
            array(
                array(
                    "Header-1" => array(
                        "Type-1" => array(
                            "description" => "Element 1",
                            "filter" => "none",
                        ),
                        "Type-2" => array(
                            "description" => "Element 2",
                            "filter" => "filter",
                        ),
                        "Type-3" => array(
                            "description" => "Element 3",
                            "filter" => "none",
                        ),
                        "Type-4" => array(
                            "description" => "Element 4",
                            "filter" => "none",
                        ),
                    )
                ),
                array(
                    array(
                        $this->createHeader("Header-1"),
                        $this->createElement("Type-1", array(
                                "description" => "Element 1",
                                "filter" => "none",
                            )
                        ),
                        $this->createElement("Type-2", array(
                                "description" => "Element 2",
                                "filter" => "filter",
                            )
                        ),
                    ),
                    array(
                        $this->createElement("Type-3", array(
                                "description" => "Element 3",
                                "filter" => "none",
                            )
                        ),
                        $this->createElement("Type-4", array(
                                "description" => "Element 4",
                                "filter" => "none",
                            )
                        ),
                    ),
                ),
            ),
            array(
                array(
                    "Header-1" => array(
                        "Type-1" => array(
                            "description" => "Element 1",
                            "filter" => "none",
                        ),
                        "Type-2" => array(
                            "description" => "Element 2",
                            "filter" => "filter",
                        ),
                    ),
                    "Header-2" => array(
                        "Type-3" => array(
                            "description" => "Element 3",
                            "filter" => "none",
                        ),
                        "Type-4" => array(
                            "description" => "Element 4",
                            "filter" => "none",
                        ),
                    )
                ),
                array(
                    array(
                        $this->createHeader("Header-1"),
                        $this->createElement("Type-1", array(
                                "description" => "Element 1",
                                "filter" => "none",
                            )
                        ),
                        $this->createElement("Type-2", array(
                                "description" => "Element 2",
                                "filter" => "filter",
                            )
                        ),
                    ),
                    array(
                        $this->createHeader("Header-2"),
                        $this->createElement("Type-3", array(
                                "description" => "Element 3",
                                "filter" => "none",
                            )
                        ),
                        $this->createElement("Type-4", array(
                                "description" => "Element 4",
                                "filter" => "none",
                            )
                        ),
                    ),
                ),
            ),
            array(
                array(
                    "Header-1" => array(
                        "Type-1" => array(
                            "description" => "Element 1",
                            "filter" => "none",
                        ),
                    ),
                    "Header-2" => array(
                        "Type-2" => array(
                            "description" => "Element 2",
                            "filter" => "filter",
                        ),
                        "Type-3" => array(
                            "description" => "Element 3",
                            "filter" => "none",
                        ),
                        "Type-4" => array(
                            "description" => "Element 4",
                            "filter" => "none",
                        ),
                    )
                ),
                array(
                    array(
                        $this->createHeader("Header-1"),
                        $this->createElement("Type-1", array(
                                "description" => "Element 1",
                                "filter" => "none",
                            )
                        ),
                    ),
                    array(
                        $this->createHeader("Header-2"),
                        $this->createElement("Type-2", array(
                                "description" => "Element 2",
                                "filter" => "filter",
                            )
                        ),
                        $this->createElement("Type-3", array(
                                "description" => "Element 3",
                                "filter" => "none",
                            )
                        ),
                    ),
                    array(
                        $this->createElement("Type-4", array(
                                "description" => "Element 4",
                                "filter" => "none",
                            )
                        ),
                    ),
                ),
            ),
        );
    }

    private function createHeader($label)
    {
        $menuItem = new BlocksAdderMenuItem();
        $menuItem->addHeader($label);

        return $menuItem;
    }

    private function createElement($type, $element)
    {
        $menuItem = new BlocksAdderMenuItem();
        $menuItem->addElement($type, $element);

        return $menuItem;
    }
}