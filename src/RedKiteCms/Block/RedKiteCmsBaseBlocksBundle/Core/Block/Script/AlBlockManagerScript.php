<?php
/**
 * This file is part of the RedKiteCmsBaseBlocksBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Script;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * AlBlockManagerScript handles a script block
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockManagerScript extends AlBlockManagerContainer
{
    /**
     * Constructor
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     */
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($container, $validator);
        
        $this->translator = $this->container->get('red_kite_cms.translator');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {
        return array('Content' => $this->translator->translate("script_block_default_content", array(), 'RedKiteCmsBaseBlocksBundle'));
    }
    
    /**
     * {@inheritdoc}
     */
    protected function renderHtml()
    {
        return array('RenderView' => array(
            'view' => 'RedKiteCmsBaseBlocksBundle:Content:Script/script.html.twig',
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function editorParameters()
    {
        $formClass = $this->container->get('script.form');
        $form = $this->container->get('form.factory')->create($formClass, $this->alBlock);
        
        return array(
            "template" => "RedKiteCmsBaseBlocksBundle:Editor:Script/editor.html.twig",
            "title" => $this->translator->translate('script_block_editor_title', array(), 'RedKiteCmsBaseBlocksBundle'),
            "blockManager" => $this,
            "form" => $form->createView(),
            "jsFiles" => explode(",", $this->alBlock->getExternalJavascript()),
            "cssFiles" => explode(",", $this->alBlock->getExternalStylesheet()),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getHideInEditMode()
    {
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function edit(array $values)
    {
        $unserializedData = array();
        $serializedData = $values['Content'];            
        parse_str($serializedData, $unserializedData); 
        
        $values["Content"] = $unserializedData['al_json_block']['content'];
        
        return parent::edit($values);
    }
}