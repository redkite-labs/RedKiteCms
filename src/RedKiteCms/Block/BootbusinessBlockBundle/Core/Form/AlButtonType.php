<?php
/*
 * This file is part of the BusinessDropCapBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright 
 * notice.
 *
 * Copyright (c) RedKiteCms <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    MIT LICENSE
 * 
 */

namespace RedKiteCms\Block\BootbusinessBlockBundle\Core\Form;

use RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Button\AlButtonType as BaseButtonType;
use Symfony\Component\Form\FormBuilderInterface;

class AlButtonType extends BaseButtonType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder->add('button_href');
    }
}
