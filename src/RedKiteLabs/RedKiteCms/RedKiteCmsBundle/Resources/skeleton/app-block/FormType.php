<?php
/**
 * A base form to edit App-Blocks attributes
 */

namespace {{ namespace }}\Core\Form;

use RedKiteLabs\RedKiteCmsBundle\Core\Form\JsonBlock\JsonBlockType;
use Symfony\Component\Form\FormBuilderInterface;

class Al{{ bundle_basename }}Type extends JsonBlockType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // Add here your fields
        $builder->add('block_text');
    }
}
