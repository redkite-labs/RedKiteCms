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

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Block\Label;

use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\Label\BlockManagerBootstrapLabelBlock;

/**
 * BlockManagerBootstrapLabelBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapLabelBlockTest extends BlockManagerLabelTestBase
{  
 
    protected function getBlockManager()
    {
        return new BlockManagerBootstrapLabelBlock($this->container, $this->validator);
    }
     
    public function testDefaultValue()
    {
        $expectedValue = array(
            'Content' => '
                {
                    "0" : {
                        "label_text": "Label 1",
                        "label_type": ""
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
                "label_text": "My custom label",
                "label_type": ""
            }
        }';
        
        $this->editorParameters($value, 'bootstraplabelblock.form', 'Label', 'LabelType');
    }
    
    public function testGetHtml()
    {
        $value =
        '{
            "0" : {
                "label_text": "My custom label",
                "label_type": "primary"
            }
        }';
        
        $expectedData = array(
            'label_text' => 'My custom label',
            'label_type' => 'primary',
        );
        
        $this->getHtml($value, 'TwitterBootstrapBundle:Content:Label/label.html.twig', $expectedData );        
    }
}
