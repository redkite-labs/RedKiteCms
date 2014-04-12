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

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Tests\Unit\Core\Form\Three\Thumbnail;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Form\Base\BaseType;
use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Form\Thumbnail\Three\ThumbnailType;

/**
 * ThumbnailTypeTest
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class ThumbnailTypeTest extends BaseType
{
    protected $translatorDomain = 'TwitterBootstrapBundle';
    
    protected function configureFields()
    {
        return array(
            array(
                'name' => 'width',
                'type' => 'choice',
                'options' => array(
                    'label' => 'thumbnail_width_attribute',
                    'choices' =>
                    array(
                        'none' => 'none',
                        'col-md-1' => 'col-md-1',
                        'col-md-2' => 'col-md-2',
                        'col-md-3' => 'col-md-3',
                        'col-md-4' => 'col-md-4',
                        'col-md-5' => 'col-md-5',
                        'col-md-6' => 'col-md-6',
                        'col-md-7' => 'col-md-7',
                        'col-md-8' => 'col-md-8',
                        'col-md-9' => 'col-md-9',
                        'col-md-10' => 'col-md-10',
                        'col-md-11' => 'col-md-11',
                        'col-md-12' => 'col-md-12',
                    ),
                ),
            ),

        );
    }
    
    protected function getForm()
    {
        return new ThumbnailType();
    }
    
    public function testDefaultOptions()
    {
        $this->setBaseResolver();

        $this->getForm()->setDefaultOptions($this->resolver);
    }
    
    public function testGetName()
    {
        $this->assertEquals('al_json_block', $this->getForm()->getName());
    }
}
