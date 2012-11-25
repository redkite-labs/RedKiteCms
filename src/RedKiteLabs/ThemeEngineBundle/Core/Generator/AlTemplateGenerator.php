<?php

namespace AlphaLemon\ThemeEngineBundle\Core\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\DependencyInjection\Container;

/**
 * AlTemplateGenerator
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlTemplateGenerator extends Generator
{
    protected $themeSkeletonDir;

    /**
     * Constructor
     *
     * @param string $themeSkeletonDir
     */
    public function __construct($themeSkeletonDir = null)
    {
        $this->themeSkeletonDir = (null === $themeSkeletonDir) ? __DIR__ . '/../../Resources/skeleton/app-theme' : $themeSkeletonDir;
    }

    /**
     * Generates the template file
     * @param string $dir The directory where the generated file must be saved
     * @param string $themeName
     * @param string $templateName
     * @param array $assets
     * @return string A message formatted to be displayed on the console
     */
    public function generateTemplate($dir, $themeName, $templateName, $assets)
    {
        $themeBasename = str_replace('Bundle', '', $themeName);
        $extensionAlias = Container::underscore($themeBasename);

        $parameters = array(
            'theme_name' => $themeName,
            'template_name' => $templateName,
            'extension_alias' => $extensionAlias,
            'assets' => $assets,
        );

        $templateFile = $templateName.'.xml';
        $this->renderFile($this->themeSkeletonDir, 'template.xml', $dir . '/' . $templateFile, $parameters);

        return sprintf('The template <info>%s</info> has been generated into <info>%s</info>', $templateFile, $dir);
    }
}