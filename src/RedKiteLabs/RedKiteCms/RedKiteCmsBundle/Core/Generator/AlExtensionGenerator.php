<?php

namespace RedKiteLabs\RedKiteCmsBundle\Core\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\DependencyInjection\Container;

/**
 * AlExtensionGenerator generates the DI Extension file, overriding the one generated
 * by the bundle generator
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlExtensionGenerator extends Generator
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
     * Generates the extension file
     * 
     * @param string $namespace
     * @param string $dir
     * @param string $themeName
     * @param array $templates 
     */
    public function generateExtension($namespace, $dir, $themeName, array $templates)
    {
        $themeBasename = str_replace('Bundle', '', $themeName);
        $extensionAlias = Container::underscore($themeBasename);

        $templateFiles = array();
        $slotFiles = array();
        foreach (array_keys($templates) as $template)
        {
            $fileName = basename($template, '.html.twig');
            if ($fileName != 'base') {
                $templateFiles[] = $fileName;
            }
            $slotFiles[] = $fileName;
        }
        
        $parameters = array(
            'namespace' => $namespace,
            'bundle_basename' => $themeBasename,
            'theme_files' => array($extensionAlias),
            'template_files' => $templateFiles,
            "slot_files" => $slotFiles,
            "extension_alias" => $extensionAlias,
        );

        $this->setSkeletonDirs($this->themeSkeletonDir);
        $extensionFile = str_replace('Bundle', '', $themeBasename) . 'Extension.php';
        $this->renderFile('Extension.php', $dir . '/' . $extensionFile, $parameters);        
        $message = sprintf('The extension file <info>%s</info> has been generated into <info>%s</info>', $extensionFile, $dir);

        return $message;
    }
}
