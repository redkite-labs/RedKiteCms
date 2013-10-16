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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Thumbnail;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * Defines the Block Manager to handle the Bootstrap Thumbnail
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapSimpleThumbnailBlock extends AlBlockManagerJsonBlockContainer
{
    protected $blockTemplate;// = 'TwitterBootstrapBundle:Content:Thumbnail/3.x/simple_thumbnail.html.twig';
    protected $editorTemplate = 'TwitterBootstrapBundle:Editor:Thumbnail/editor.html.twig';
    
    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface                             $container
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($container, $validator);
        
        $bootstrapVersion = $this->container->get('red_kite_cms.active_theme')->getThemeBootstrapVersion();   
        
        $this->blockTemplate = sprintf('TwitterBootstrapBundle:Content:Thumbnail/%s/simple_thumbnail.html.twig', $bootstrapVersion);        
    }
    
    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {//span
        $value = '
            {
                "0" : {
                    "width": "col-md-3"
                }
            }';
        
        return array('Content' => $value);
    }

    /**
     * {@inheritdoc}
     */
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];
        
        return array('RenderView' => array(
            'view' => $this->blockTemplate,
            'options' => array(
                'thumbnail' => $item,
            ),
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function editorParameters()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];
        
        $bootstrapFormFactory = $this->container->get('twitter_bootstrap.bootstrap_form_factory');
        $form = $bootstrapFormFactory->createForm('Thumbnail', 'AlThumbnailType', $item);
        
        return array(
            "template" => $this->editorTemplate,
            "title" => "Thumbnail editor",
            "form" => $form->createView(),
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getIsInternalBlock()
    {
        return true;
    }
}
