<?php

namespace AlphaLemon\ThemeEngineBundle\Core\Generator;

use Symfony\Component\DependencyInjection\Container;

/**
 * AlAppThemeGenerator
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlAppThemeGenerator extends AlBaseGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generateExt($namespace, $bundle, $dir, $format, $structure, array $options)
    {
        $format = 'annotation';
        $this->generate($namespace, $bundle, $dir, $format, $structure);

        $dir .= '/'.strtr($namespace, '\\', '/');
        $themeBasename = str_replace('Bundle', '', $bundle);
        $extensionAlias = Container::underscore($themeBasename);

        $themeSkeletonDir = __DIR__ . '/../../Resources/skeleton/app-theme';
        $parameters = array(
            'namespace_path' => str_replace('\\', '\\\\', $namespace),
            'target_dir' => str_replace('\\', '/', $namespace),
            'bundle'    => $bundle,
            'theme_basename' => $themeBasename,
            'extension_alias' => $extensionAlias,
        );

        $this->renderFile($themeSkeletonDir, 'theme.xml', $dir.'/Resources/config/'.$extensionAlias.'.xml', $parameters);
        $this->renderFile($themeSkeletonDir, 'info.yml', $dir.'/Resources/data/info.yml', $parameters);
        $this->renderFile($themeSkeletonDir, 'autoload.json', $dir.'/autoload.json', $parameters);
        $this->renderFile($themeSkeletonDir, 'composer.json', $dir.'/composer.json', $parameters);
        $this->filesystem->mkdir($dir.'/Resources/views/Theme');
    }
}