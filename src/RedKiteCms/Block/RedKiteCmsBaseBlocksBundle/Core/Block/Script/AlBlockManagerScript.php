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
        return array('Content' => $this->translator->translate("This is a default script content"));
    }
    
    protected function renderHtml()
    {
        return array('RenderView' => array(
            'view' => 'RedKiteCmsBaseBlocksBundle:Content:Script/script.html.twig',
        ));
    }
    
    public function editorParameters()
    {
        $formClass = $this->container->get('script.form');
        $form = $this->container->get('form.factory')->create($formClass, $this->alBlock);
        
        return array(
            "template" => "RedKiteCmsBaseBlocksBundle:Editor:Script/editor.html.twig",
            "title" => "Script editor",
            "blockManager" => $this,
            "form" => $form->createView(),
            "jsFiles" => explode(",", $this->alBlock->getExternalJavascript()),
            "cssFiles" => explode(",", $this->alBlock->getExternalStylesheet()),
            'configuration' => $this->container->get('red_kite_cms.configuration'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getHideInEditMode()
    {
        return true;
    }
}
