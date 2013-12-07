<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Generator;

use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Generator\Base\AlAppGeneratorBase;
use RedKiteLabs\RedKiteCmsBundle\Core\Generator\AlAppBlockGenerator;
use org\bovigo\vfs\vfsStream;

/**
 * AlAppBlockGeneratorTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlAppBlockGeneratorTest extends AlAppGeneratorBase
{
    private $blockGenerator;

    protected function setUp()
    {
        parent::setUp();

        $this->blockGenerator = new AlAppBlockGenerator($this->fileSystem, vfsStream::url('root'), vfsStream::url('root'));
    }

    public function testBlockBundleIsGenerated()
    {
        $options = array(
            'description' => 'Fake block',
            'group' => 'fake-group',
        );
        $this->blockGenerator->generateExt('RedKiteCms\\Block\\FakeBlockBundle', 'FakeBlockBundle', vfsStream::url('root/src'), 'xml', '', $options);

        $expected = '<?php' . PHP_EOL;
        $expected .= '/**' . PHP_EOL;
        $expected .= ' * A RedKiteCms Block' . PHP_EOL;
        $expected .= ' */' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= 'namespace RedKiteCms\Block\FakeBlockBundle\Core\Block;' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= 'use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '/**' . PHP_EOL;
        $expected .= ' * Description of AlBlockManagerFakeBlock' . PHP_EOL;
        $expected .= ' */' . PHP_EOL;
        $expected .= 'class AlBlockManagerFakeBlock extends AlBlockManagerJsonBlockContainer' . PHP_EOL;
        $expected .= '{' . PHP_EOL;
        $expected .= '    public function getDefaultValue()' . PHP_EOL;
        $expected .= '    {' . PHP_EOL;
        $expected .= '        $value =' . PHP_EOL;
        $expected .= '            \'' . PHP_EOL;
        $expected .= '                {' . PHP_EOL;
        $expected .= '                    "0" : {' . PHP_EOL;
        $expected .= '                        "block_text": "Default value"' . PHP_EOL;
        $expected .= '                    }' . PHP_EOL;
        $expected .= '                }' . PHP_EOL;
        $expected .= '            \';' . PHP_EOL;
        $expected .= '' . PHP_EOL;        
        $expected .= '        return array(\'Content\' => $value);' . PHP_EOL;
        $expected .= '    }' . PHP_EOL;        
        $expected .= PHP_EOL;
        $expected .= '    protected function renderHtml()' . PHP_EOL;
        $expected .= '    {' . PHP_EOL;
        $expected .= '        $items = $this->decodeJsonContent($this->alBlock->getContent());' . PHP_EOL;
        $expected .= PHP_EOL;        
        $expected .= '        return array(\'RenderView\' => array(' . PHP_EOL;
        $expected .= '            \'view\' => \'FakeBlockBundle:Content:fakeblock.html.twig\',' . PHP_EOL;
        $expected .= '            \'options\' => array(\'item\' => $items[0]),' . PHP_EOL;
        $expected .= '        ));' . PHP_EOL;
        $expected .= '    }' . PHP_EOL;
        $expected .= PHP_EOL;    
        $expected .= '    public function editorParameters()' . PHP_EOL;
        $expected .= '    {' . PHP_EOL;        
        $expected .= '        $items = $this->decodeJsonContent($this->alBlock->getContent());' . PHP_EOL;
        $expected .= '        $item = $items[0];' . PHP_EOL;
        $expected .= '' . PHP_EOL;        
        $expected .= '        $formClass = $this->container->get(\'fakeblock.form\');' . PHP_EOL;
        $expected .= '        $form = $this->container->get(\'form.factory\')->create($formClass, $item);' . PHP_EOL;
        $expected .= PHP_EOL;        
        $expected .= '        return array(' . PHP_EOL;
        $expected .= '            "template" => \'FakeBlockBundle:Editor:fakeblock.html.twig\',' . PHP_EOL;
        $expected .= '            "title" => "My awesome App-Block",' . PHP_EOL;
        $expected .= '            "form" => $form->createView(),' . PHP_EOL;
        $expected .= '        );' . PHP_EOL;
        $expected .= '    }' . PHP_EOL;
        $expected .= '}' . PHP_EOL;

        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Core/Block/AlBlockManagerFakeBlock.php');
        $this->assertFileExists($file);
        $this->assertEquals($expected, file_get_contents($file));
        
        $expected = '<?php' . PHP_EOL;
        $expected .= '/**' . PHP_EOL;
        $expected .= ' * A base form to edit App-Blocks attributes' . PHP_EOL;
        $expected .= ' */' . PHP_EOL;
        $expected .= PHP_EOL;    
        $expected .= 'namespace RedKiteCms\Block\FakeBlockBundle\Core\Form;' . PHP_EOL;
        $expected .= PHP_EOL;    
        $expected .= 'use RedKiteLabs\RedKiteCmsBundle\Core\Form\JsonBlock\JsonBlockType;' . PHP_EOL;
        $expected .= 'use Symfony\Component\Form\FormBuilderInterface;' . PHP_EOL;
        $expected .= PHP_EOL;    
        $expected .= 'class AlFakeBlockType extends JsonBlockType' . PHP_EOL;
        $expected .= '{' . PHP_EOL;
        $expected .= '    public function buildForm(FormBuilderInterface $builder, array $options)' . PHP_EOL;
        $expected .= '    {' . PHP_EOL;
        $expected .= '        parent::buildForm($builder, $options);' . PHP_EOL;
        $expected .= PHP_EOL;    
        $expected .= '        // Add here your fields' . PHP_EOL;
        $expected .= '        $builder->add(\'block_text\');' . PHP_EOL;
        $expected .= '    }' . PHP_EOL;
        $expected .= '}' . PHP_EOL;
        
        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Core/Form/AlFakeBlockType.php');
        $this->assertFileExists($file);
        $this->assertEquals($expected, file_get_contents($file));

        $expected = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $expected .= '<container xmlns="http://symfony.com/schema/dic/services"' . PHP_EOL;
        $expected .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $expected .= '        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">' . PHP_EOL;
        $expected .= PHP_EOL;
        $expected .= '    <parameters>' . PHP_EOL;
        $expected .= '        <parameter key="fake_block.block.class">RedKiteCms\Block\FakeBlockBundle\Core\Block\AlBlockManagerFakeBlock</parameter>' . PHP_EOL;
        $expected .= '        <parameter key="fake_block.form.class">RedKiteCms\Block\FakeBlockBundle\Core\Form\AlFakeBlockType</parameter>' . PHP_EOL;
        $expected .= '    </parameters>' . PHP_EOL;
        $expected .= PHP_EOL;        
        $expected .= '    <services>' . PHP_EOL;
        $expected .= '        <service id="fake_block.block" class="%fake_block.block.class%">' . PHP_EOL;
        $expected .= '            <tag name="red_kite_cms.blocks_factory.block" description="Fake block" type="FakeBlock" group="fake-group" />' . PHP_EOL;
        $expected .= '            <argument type="service" id="service_container" />' . PHP_EOL;
        $expected .= '        </service>' . PHP_EOL;
        $expected .= PHP_EOL;        
        $expected .= '        <service id="fake_block.form" class="%fake_block.form.class%">' . PHP_EOL;            
        $expected .= '        </service>' . PHP_EOL;
        $expected .= '    </services>' . PHP_EOL;
        $expected .= '</container>' . PHP_EOL;

        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Resources/config/app_block.xml');
        $this->assertFileExists($file);
        $this->assertEquals($expected, file_get_contents($file));

        $expected = 'imports:' . PHP_EOL;
        $expected .= '- { resource: "@FakeBlockBundle/Resources/config/app_block.xml" }';

        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Resources/config/config_rkcms.yml');
        $this->assertFileExists($file);
        $this->assertEquals($expected, file_get_contents($file));

        $expected = 'imports:' . PHP_EOL;
        $expected .= '- { resource: "@FakeBlockBundle/Resources/config/config_rkcms.yml" }';
        
        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Resources/config/config_rkcms_dev.yml');
        $this->assertFileExists($file);
        $this->assertEquals($expected, file_get_contents($file));

        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Resources/config/config_rkcms_test.yml');
        $this->assertFileExists($file);
        $this->assertEquals($expected, file_get_contents($file));

        $expected = '{' . PHP_EOL;
        $expected .= '    "bundles" : {' . PHP_EOL;
        $expected .= '        "RedKiteCms\\\\Block\\\\FakeBlockBundle\\\\FakeBlockBundle" : {' . PHP_EOL;
        $expected .= '            "environments" : ["all"]' . PHP_EOL;
        $expected .= '        }' . PHP_EOL;
        $expected .= '    }' . PHP_EOL;
        $expected .= '}';

        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/autoload.json');
        $this->assertFileExists($file);
        $this->assertEquals($expected, file_get_contents($file));

        $expected = '{' . PHP_EOL;
        $expected .= '    "autoload": {' . PHP_EOL;
        $expected .= '        "psr-0": { "RedKiteCms\\\\Block\\\\FakeBlockBundle": ""' . PHP_EOL;
        $expected .= '        }' . PHP_EOL;
        $expected .= '    },' . PHP_EOL;
        $expected .= '    "target-dir" : "RedKiteCms/Block/FakeBlockBundle",' . PHP_EOL;
        $expected .= '    "minimum-stability": "dev"' . PHP_EOL;
        $expected .= '}';
        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/composer.json');
        $this->assertFileExists($file);
        $this->assertEquals($expected, file_get_contents($file));
        
        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Resources/views/Content/fakeblock.html.twig');
        $this->assertFileExists($file);
        
        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Resources/views/Editor/fakeblock.html.twig');
        $this->assertFileExists($file);
        $this->assertEquals('{% include "RedKiteCmsBundle:Block:Editor/_editor_form.html.twig" %}', file_get_contents($file));
        
        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/DependencyInjection/FakeBlockExtension.php');
        $this->assertFileExists($file);
    }

    public function testBlockBundleIsGeneratedSkippingStrictMode()
    {
        $options = array(
            'description' => 'Fake block',
            'group' => 'fake-group',
            'no-strict' => true
        );
        $this->blockGenerator->generateExt('RedKiteCms\\Block\\FakeBlockBundle', 'FakeBlockBundle', vfsStream::url('root/src'), 'xml', '', $options);

        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Core/Block/AlBlockManagerFakeBlock.php');
        $this->assertFileExists($file);

        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Resources/config/app_block.xml');
        $this->assertFileExists($file);

        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Resources/config/config_rkcms.yml');
        $this->assertFileExists($file);

        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Resources/config/config_rkcms_dev.yml');
        $this->assertFileExists($file);

        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Resources/config/config_rkcms_test.yml');
        $this->assertFileExists($file);

        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/autoload.json');
        $this->assertFileExists($file);

        $this->assertFileNotExists(vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/composer.json'));

        $file = vfsStream::url('root/src/RedKiteCms/Block/FakeBlockBundle/Resources/views/Block/fake_block_editor.html.twig');
    }
}