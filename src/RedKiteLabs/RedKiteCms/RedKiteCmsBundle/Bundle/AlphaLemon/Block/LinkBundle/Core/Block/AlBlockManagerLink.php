<?php
/**
 * An AlphaLemonCms Block
 */

namespace AlphaLemon\Block\LinkBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * Description of AlBlockManagerLink
 */
class AlBlockManagerLink extends AlBlockManagerJsonBlockContainer
{
    protected $translator;
    protected $cmsLanguage;
    
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($container, $validator);
        
        $this->translator = $this->container->get('alpha_lemon_cms.translator');
    }
    
    public function getDefaultValue()
    {
        $value = 
            '
                {
                    "0" : {
                        "href": "#",
                        "value": "Link"
                    }
                }
            ';
        
        return array('Content' => $value);
    }
    
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $link = $items[0];
        
        return array('RenderView' => array(
            'view' => 'LinkBundle:Content:link.html.twig',
            'options' => array(
                'link' => $link, 
                'block_manager' => $this,
            ),
        ));
    }
    
    public function editorParameters()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];
        
        $formClass = $this->container->get('bootstrap_link.form');
        $form = $this->container->get('form.factory')->create($formClass, $item);
                
        $seoRepository = $this->factoryRepository->createRepository('Seo');        
        $request = $this->container->get('request');
        
        return array(
            "template" => "LinkBundle:Editor:_editor.html.twig",
            "title" => $this->translator->translate('Link editor'),
            "form" => $form->createView(),
            'pages' => ChoiceValues::getPermalinks($seoRepository, $request->get('_locale')),
            'configuration' => $this->container->get('alpha_lemon_cms.configuration'),
        );
    }
}
