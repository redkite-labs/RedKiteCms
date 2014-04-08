<?php
/**
 * A RedKiteCms Block
 */

namespace RedKiteCms\Block\MarkdownBlockBundle\Core\Block;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\InlineTextBlock\BlockManagerInlineTextBlock;

/**
 * Description of BlockManagerMarkdownBlock
 */
class BlockManagerMarkdownBlock extends BlockManagerInlineTextBlock
{
    public function getDefaultValue()
    {
        $message = "This is the default content for a new hypertext block";
        if (null !== $this->translator) {
            $message = $this->translator->translate($message);
        }

        return array(
            'Content' => $message,
        );
    }

    protected function renderHtml()
    {
        return array('RenderView' => array(
            'view' => 'MarkdownBlockBundle:Content:markdownblock.html.twig',
            'options' => array(
                'block_id' => $this->alBlock->getId(),
                'block_content' => $this->alBlock->getContent(),
            ),
        ));
    }

    /**
     * @inheritdoc
     */
    protected function editInline()
    {
        return false;
    }
}
