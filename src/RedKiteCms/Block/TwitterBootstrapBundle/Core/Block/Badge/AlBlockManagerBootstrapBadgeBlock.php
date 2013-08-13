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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Badge;

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Label\AlBlockManagerBootstrapLabelBlock;

/**
 * Defines the Block Manager to handle the Bootstrap Badge
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapBadgeBlock extends AlBlockManagerBootstrapLabelBlock
{
    protected $formParam = 'bootstrapbadgeblock.form';    
    protected $blockTemplate = 'TwitterBootstrapBundle:Content:Badge/badge.html.twig';    
    protected $editorTemplate = 'TwitterBootstrapBundle:Editor:Badge/badge_editor.html.twig';
    
    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {
        $value = 
            '
                {
                    "0" : {
                        "badge_text": "Badge 1",
                        "badge_type": ""
                    }
                }
            ';
        
        return array('Content' => $value);
    }
}
