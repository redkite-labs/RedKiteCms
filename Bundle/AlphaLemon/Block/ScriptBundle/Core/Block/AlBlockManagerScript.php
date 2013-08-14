<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\Block\ScriptBundle\Core\Block;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManagerContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * ScriptExtension
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockManagerScript extends AlBlockManagerContainer
{
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($container, $validator);
        
        $this->translator = $this->container->get('alpha_lemon_cms.translator');
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
            'view' => 'ScriptBundle:Content:script.html.twig',
        ));
    }
    
    public function editorParameters()
    {
        return array(
            "template" => "ScriptBundle:Editor:_editor.html.twig",
            "title" => "Script editor",
            "blockManager" => $this,
            "jsFiles" => explode(",", $this->alBlock->getExternalJavascript()),
            "cssFiles" => explode(",", $this->alBlock->getExternalStylesheet()),
            'configuration' => $this->container->get('alpha_lemon_cms.configuration'),
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
