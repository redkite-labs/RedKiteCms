<?php

namespace RedKiteLabs\RedKiteCmsBundle\Core\Generator;

use Symfony\Component\DependencyInjection\Container;

/**
 * AlAppThemeGenerator
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlAppThemeGenerator extends AlBaseGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generateExt($namespace, $bundle, $dir, $format, $structure, array $options)
    {
        $format = 'annotation';
        
        // @codeCoverageIgnoreStart
        if (null === $this->bundleSkeletonDir) {
            $this->bundleSkeletonDir = __DIR__ . '/../../../../../../sensio/generator-bundle/Sensio/Bundle/GeneratorBundle/Resources/skeleton';            
        }
        // @codeCoverageIgnoreEnd
        $this->setSkeletonDirs($this->bundleSkeletonDir);
        $this->generate($namespace, $bundle, $dir, $format, $structure);

        $dir .= '/'.strtr($namespace, '\\', '/');
        $themeBasename = str_replace('Bundle', '', $bundle);
        $extensionAlias = Container::underscore($themeBasename);

        $themeSkeletonDir = __DIR__ . '/../../Resources/skeleton/app-theme';            
        $this->setSkeletonDirs($themeSkeletonDir);
        $parameters = array(
            'namespace_path' => str_replace('\\', '\\\\', $namespace),
            'target_dir' => str_replace('\\', '/', $namespace),
            'bundle'    => $bundle,
            'theme_basename' => $themeBasename,
            'extension_alias' => $extensionAlias,
        );

        $this->renderFile('theme.xml', $dir.'/Resources/config/'.$extensionAlias.'.xml', $parameters);
        $this->renderFile('info.yml', $dir.'/Resources/data/info.yml', $parameters);
        $this->renderFile('autoload.json', $dir.'/autoload.json', $parameters);
        $this->renderFile('composer.json', $dir.'/composer.json', $parameters);
        $this->filesystem->mkdir($dir.'/Resources/views/Theme');
    }
}
