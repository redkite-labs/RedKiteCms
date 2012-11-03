<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\BundleGenerator;
use Symfony\Component\DependencyInjection\Container;
use AlphaLemon\ThemeEngineBundle\Core\Generator\AlBaseGenerator;

/**
 * AlAppBlockGenerator generates an App-Block bundle
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlAppBlockGenerator extends AlBaseGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generateExt($namespace, $bundle, $dir, $format, $structure, array $options)
    {
        $this->generate($namespace, $bundle, $dir, $format, $structure);

        $dir .= '/'.strtr($namespace, '\\', '/');
        $bundleBasename = str_replace('Bundle', '', $bundle);

        $this->filesystem->mkdir($dir.'/Core/Block');

        $blockSkeletonDir = __DIR__ . '/../../Resources/skeleton/app-block';
        $extensionAlias = Container::underscore($bundleBasename);
        $typeLowercase = strtolower($bundleBasename);
        $parameters = array(
            'namespace' => $namespace,
            'namespace_path' => str_replace('\\', '\\\\', $namespace),
            'target_dir' => str_replace('\\', '/', $namespace),
            'bundle'    => $bundle,
            'format'    => $format,
            'bundle_basename' => $bundleBasename,
            'type_lowercase' => $typeLowercase,
            'extension_alias' => $extensionAlias,
            'description'    => $options["description"],
            'group'    => $options["group"],
        );

        $this->renderFile($blockSkeletonDir, 'Block.php', $dir.'/Core/Block/AlBlockManager'.$bundleBasename.'.php', $parameters);
        $this->renderFile($blockSkeletonDir, 'app-block.xml', $dir.'/Resources/config/app-block.xml', $parameters);
        $this->renderFile($blockSkeletonDir, 'config_alcms.yml', $dir.'/Resources/config/config_alcms.yml', $parameters);
        $this->renderFile($blockSkeletonDir, 'config_alcms_dev.yml', $dir.'/Resources/config/config_alcms_dev.yml', $parameters);
        $this->renderFile($blockSkeletonDir, 'config_alcms_test.yml', $dir.'/Resources/config/config_alcms_test.yml', $parameters);
        $this->renderFile($blockSkeletonDir, 'autoload.json', $dir.'/autoload.json', $parameters);
        if ($options["strict"]) $this->renderFile($blockSkeletonDir, 'composer.json', $dir.'/composer.json', $parameters);
        $this->filesystem->copy($blockSkeletonDir . '/editor.html.twig', $dir.'/Resources/views/Block/' . $typeLowercase . '_editor.html.twig');
    }
}