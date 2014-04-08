<?php
/**
 * A base form to edit App-Blocks attributes
 */

namespace RedKiteCms\Block\MarkdownBlockBundle\Core\Form;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\JsonBlock\JsonBlockType;
use Symfony\Component\Form\FormBuilderInterface;

class MarkdownBlockType extends JsonBlockType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // Add here your fields
        $builder->add('block_text');
    }
}
