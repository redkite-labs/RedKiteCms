<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 *
 * @license    MIT License
 */

namespace AlphaLemon\ThemeEngineBundle\Core\TemplateSlots;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * AlTemplateSlotsFactory
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlTemplateSlotsFactory implements AlTemplateSlotsFactoryInterface
{
    private $kernel;
    private $translator;
    private $fixturesFolder;

    public function __construct(TranslatorInterface $translator, KernelInterface $kernel = null, $fixturesFolder = null)
    {
        $this->translator = $translator;
        $this->kernel = $kernel;
        $this->fixturesFolder = $fixturesFolder;
    }

    public function create($themeName, $templateName)
    {
        $templateName = str_replace('Slots', '', $templateName);
        preg_match("/^(" . $themeName . ")?([\w]+)$/", $templateName, $matches);

        $template = $themeName;
        $template .= (empty($matches[2])) ? \ucfirst($templateName) : \ucfirst($matches[2]);
        $template .= 'Slots';

        $className = \sprintf('AlphaLemon\Theme\%s\Core\Slots\%s', $themeName, $template);
        if(!\class_exists($className)) {
            throw new \RuntimeException($this->translator->trans('The class %className% does not exist. You must create a [ThemeName][TemplateName]Slots class for each template of your theme.', array('%className%' => $className)));
        }

        return new $className($this->kernel, $this->fixturesFolder);
    }

}