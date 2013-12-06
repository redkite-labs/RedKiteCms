<?php

namespace RedKiteLabs\RedKiteCmsBundle\Core\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\DependencyInjection\Container;

/**
 * AlTemplateGenerator
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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
     * @param  string $dir          The directory where the generated file must be saved
     * @param  string $themeName
     * @param  string $templateName
     * @param  array  $assets
     * @return string A message formatted to be displayed on the console
     */
    public function generateTemplate($dir, $themeName, $templateName)
    {
        $themeBasename = str_replace('Bundle', '', $themeName);
        $extensionAlias = Container::underscore($themeBasename);

        $parameters = array(
            'theme_name' => $themeName,
            'template_name' => $templateName,
            'extension_alias' => $extensionAlias,
        );

        $templateFile = $templateName.'.xml';
        $this->setSkeletonDirs($this->themeSkeletonDir);
        $this->renderFile('template.xml', $dir . '/' . $templateFile, $parameters);

        return sprintf('The template <info>%s</info> has been generated into <info>%s</info>', $templateFile, $dir);
    }
}
