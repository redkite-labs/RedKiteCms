<?php
/*
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright 
 * notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 * 
 * @license    MIT LICENSE
 * 
 */

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Block\Badge;

use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Block\Label\BlockManagerBootstrapLabelBlockTest;
use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\Badge\BlockManagerBootstrapBadgeBlock;

/**
 * BlockManagerBootstrapBadgeBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapBadgeBlockTest extends BlockManagerBootstrapLabelBlockTest
{    
    protected function getBlockManager()
    {
        return new BlockManagerBootstrapBadgeBlock($this->container, $this->validator);
    }
        
    public function testDefaultValue()
    {
        $expectedValue = array(
            'Content' => '
                {
                    "0" : {
                        "badge_text": "Badge 1",
                        "badge_type": ""
                    }
                }
            '
        );
        
        $this->defaultValue($expectedValue);
    }
    
    public function testEditorParameters()
    {
        $value =
        '{
            "0" : {
                "badge_text": "My custom badge",
                "badge_type": ""
            }
        }';
        
        $this->editorParameters($value, 'bootstrapbadgeblock.form', 'Badge', 'BadgeType');
    }
    
    public function testGetHtml()
    {
        $value =
        '{
            "0" : {
                "badge_text": "My custom badge",
                "badge_type": "danger"
            }
        }';
        
        $expectedData = array(
            'badge_text' => 'My custom badge',
            'badge_type' => 'danger',
        );
        
        $this->getHtml($value, 'TwitterBootstrapBundle:Content:Badge/badge.html.twig', $expectedData );        
    }
}
