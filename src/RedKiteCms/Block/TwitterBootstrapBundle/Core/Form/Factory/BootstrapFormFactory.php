<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Factory;

use RedKiteLabs\RedKiteCmsBundle\Core\ActiveTheme\AlActiveThemeInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\RuntimeException;

/**
 * Description of FormFactory
 *
 * @author alphalemon
 */
class BootstrapFormFactory
{
    private $activeTheme;
    private $formFactory;
    
    public function __construct(AlActiveThemeInterface $activeTheme, \Symfony\Component\Form\FormFactoryInterface $formFactory)
    {
        $this->activeTheme = $activeTheme;
        $this->formFactory = $formFactory;
    }
    
    public function createForm($type, $formName, $data = null)
    {
        $formClass = $this->getFormClass($type, $formName);
        $form = $this->formFactory->create(new $formClass(), $data);
        
        return $form;
    }
    
    protected function getFormClass($type, $formName)
    {
        $bootstrapToken = null;
        $bootstrapVersion = $this->activeTheme->getThemeBootstrapVersion();
        switch ($bootstrapVersion) {
            case "2.x":
                $bootstrapToken = "Two";
                break;
            case "3.x":
                $bootstrapToken = "Three";
                break;
        }
        
        if (null === $bootstrapToken) {
            throw new RuntimeException("Something went wrong: I cannot find any valid form for %s Twitter Bootstrap version", $bootstrapVersion);
        }
        
        $formClass = sprintf("RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\%s\%s\%s", $type, $bootstrapToken, $formName);
        
        return new $formClass();
    }
}