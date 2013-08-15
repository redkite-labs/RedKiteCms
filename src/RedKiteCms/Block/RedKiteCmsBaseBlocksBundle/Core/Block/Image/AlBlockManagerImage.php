<?php
/**
 * An AlphaLemonCms Block 
 */

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\Image;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * Description of AlBlockManagerImage
 */
class AlBlockManagerImage extends AlBlockManagerJsonBlockContainer
{
    protected $translator;
    protected $configuration;
    protected $cmsLanguage;
    protected $blockTemplate = 'RedKiteCmsBaseBlocksBundle:Content:Image/image.html.twig';  
    protected $editorTemplate = 'RedKiteCmsBaseBlocksBundle:Editor:Image/editor.html.twig';
     
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($container, $validator);
        
        $this->translator = $this->container->get('red_kite_cms.translator');
        $this->configuration = $this->container->get('red_kite_cms.configuration');
    }
    
    public function getDefaultValue()
    {
        $value = sprintf(
            '
                {
                    "0" : {
                        "src": "",
                        "data_src": "holder.js/260x180",
                        "title" : "%s",
                        "alt" : "%s"
                    }
                }
            ',  $this->translator->translate("Sample title"), 
                $this->translator->translate("Sample alt"));
        
        return array('Content' => $value);
    }
    
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        
        return array('RenderView' => array(
            'view' => $this->blockTemplate,
            'options' => array(
                'image' => $items[0],
            ),
        ));
    }
    
    public function editorParameters()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];
        
        $formClass = $this->container->get('image.form');
        $form = $this->container->get('form.factory')->create($formClass, $item);
        
        return array(
            "template" => $this->editorTemplate,
            "title" => $this->translator->translate("Image editor"),
            "form" => $form->createView(),
            "configuration" => $this->configuration,
        );
    }
}
