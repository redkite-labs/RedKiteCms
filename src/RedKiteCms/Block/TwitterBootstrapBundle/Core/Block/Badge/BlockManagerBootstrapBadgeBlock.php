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

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Label\BlockManagerBootstrapLabelBlock;

/**
 * Defines the Block Manager to handle the Bootstrap Badge
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapBadgeBlock extends BlockManagerBootstrapLabelBlock
{
    protected $formParam = 'bootstrapbadgeblock.form';
    protected $blockTemplate = 'TwitterBootstrapBundle:Content:Badge/badge.html.twig';
    protected $editorTemplate = 'TwitterBootstrapBundle:Editor:Badge/badge_editor.html.twig';

    /**
     * Defines the App-Block's default value
     *
     * @return array
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

    /**
     * Defines the parameters passed to the App-Block's editor
     *
     * @return array
     */
    public function editorParameters()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];

        $bootstrapFormFactory = $this->container->get('twitter_bootstrap.bootstrap_form_factory');
        $form = $bootstrapFormFactory->createForm('Badge', 'BadgeType', $item);

        return array(
            "template" => $this->editorTemplate,
            "title" => $this->translator->translate('badge_block_editor_title', array(), 'TwitterBootstrapBundle'),
            "form" => $form->createView(),
        );
    }
}
