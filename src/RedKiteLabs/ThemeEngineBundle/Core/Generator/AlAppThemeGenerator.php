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
        $this->generate($namespace, $bundle, $dir, $format, $structure);

        $dir .= '/'.strtr($namespace, '\\', '/');
        $suffix = $options["strict"] ? 'ThemeBundle' : 'Bundle';
        $themeBasename = str_replace($suffix, '', $bundle);

        $themeSkeletonDir = __DIR__ . '/../../Resources/skeleton/app-theme';
        $extensionAlias = Container::underscore($themeBasename);
        $parameters = array(
            'theme_basename' => $themeBasename,
            'extension_alias' => $extensionAlias,
        );

        $this->renderFile($themeSkeletonDir, 'theme.xml', $dir.'/Resources/config/'.$extensionAlias.'.xml', $parameters);
        $this->filesystem->mkdir($dir.'/Resources/views/Theme');
    }
}