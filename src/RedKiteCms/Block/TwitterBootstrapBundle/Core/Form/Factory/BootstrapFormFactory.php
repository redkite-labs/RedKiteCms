<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\Factory;

use RedKiteLabs\RedKiteCmsBundle\Core\ActiveTheme\AlActiveThemeInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\RuntimeException;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Creates a form for a specific Twitter Bootstrap version
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BootstrapFormFactory
{
    private $activeTheme;
    private $formFactory;

    /**
     * Comnstructor
     *
     * @param AlActiveThemeInterface                       $activeTheme
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    public function __construct(AlActiveThemeInterface $activeTheme, FormFactoryInterface $formFactory)
    {
        $this->activeTheme = $activeTheme;
        $this->formFactory = $formFactory;
    }

    /**
     * Creates the form
     *
     * @param  string                                $type
     * @param  string                                $formName
     * @param  array                                 $data
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm($type, $formName, array $data = null)
    {
        $formClass = $this->getFormClass($type, $formName);
        $form = $this->formFactory->create(new $formClass(), $data);

        return $form;
    }

    /**
     * Returns the form class name
     *
     * @param  string $type
     * @param  string $formName
     * @return string
     */
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
            throw new RuntimeException(sprintf("Something went wrong: I cannot find any valid form for %s Twitter Bootstrap version", $bootstrapVersion));
        }

        $formClass = sprintf("RedKiteCms\Block\TwitterBootstrapBundle\Core\Form\%s\%s\%s", $type, $bootstrapToken, $formName);

        return new $formClass();
    }
}
