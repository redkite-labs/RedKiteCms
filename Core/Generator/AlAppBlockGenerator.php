<?php

namespace RedKiteLabs\RedKiteCmsBundle\Core\Generator;

use Symfony\Component\DependencyInjection\Container;
use RedKiteLabs\RedKiteCmsBundle\Core\Generator\AlBaseGenerator;

/**
 * AlAppBlockGenerator generates an App-Block bundle
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlAppBlockGenerator extends AlBaseGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generateExt($namespace, $bundle, $dir, $format, $structure, array $options)
    {
        $format = 'annotation';
        if (null === $this->bundleSkeletonDir) {
            $this->bundleSkeletonDir = __DIR__ . '/../../../../../../sensio/generator-bundle/Sensio/Bundle/GeneratorBundle/Resources/skeleton';
        }
        $this->setSkeletonDirs($this->bundleSkeletonDir);
        $this->generate($namespace, $bundle, $dir, $format, $structure);

        $dir .= '/'.strtr($namespace, '\\', '/');
        $bundleBasename = str_replace('Bundle', '', $bundle);

        $this->filesystem->mkdir($dir.'/Core/Block');

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
    
        $blockSkeletonDir = __DIR__ . '/../../Resources/skeleton/app-block';            
        $this->setSkeletonDirs($blockSkeletonDir);
        $this->renderFile('Block.php', $dir.'/Core/Block/AlBlockManager'.$bundleBasename.'.php', $parameters);
        $this->renderFile('FormType.php', $dir.'/Core/Form/Al'.$bundleBasename.'Type.php', $parameters);
        $this->renderFile('app_block.xml', $dir.'/Resources/config/app_block.xml', $parameters);
        $this->renderFile('config_alcms.yml', $dir.'/Resources/config/config_alcms.yml', $parameters);
        $this->renderFile('config_alcms_dev.yml', $dir.'/Resources/config/config_alcms_dev.yml', $parameters);
        $this->renderFile('config_alcms_test.yml', $dir.'/Resources/config/config_alcms_test.yml', $parameters);
        $this->renderFile('autoload.json', $dir.'/autoload.json', $parameters);
        if (!array_key_exists("no-strict", $options) || $options["no-strict"] == false) {
            $this->renderFile('composer.json', $dir.'/composer.json', $parameters);
        }
        $this->filesystem->copy($blockSkeletonDir . '/block.html.twig', $dir.'/Resources/views/Content/' . $typeLowercase . '.html.twig');
        $this->filesystem->copy($blockSkeletonDir . '/form_editor.html.twig', $dir.'/Resources/views/Editor/' . $typeLowercase . '.html.twig');
    }
}