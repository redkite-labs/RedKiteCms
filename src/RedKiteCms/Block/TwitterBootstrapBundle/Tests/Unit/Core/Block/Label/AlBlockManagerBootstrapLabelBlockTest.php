<?php
/*
 * This file is part of the TwitterBootstrapBundle and it is distributed
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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Tests\Unit\Core\Block\Label;

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Label\AlBlockManagerBootstrapLabelBlock;

/**
 * AlBlockManagerBootstrapLabelBlockTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapLabelBlockTest extends AlBlockManagerLabelTestBase
{  
 
    protected function getBlockManager()
    {
        return new AlBlockManagerBootstrapLabelBlock($this->container, $this->validator);
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
        
        $this->editorParameters($value, 'bootstraplabelblock.form', 'Label', 'AlLabelType');
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
