<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Twig;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Twig\SlotRendererExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * AlphaLemonCmsExtensionTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class SlotRendererExtensionTest extends TestCase
{
    private $container;

    protected function setUp()
    {
        $this->pageTree = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree')
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $this->slotRenderer = new SlotRendererExtension($this->container);
    }

    public function testTwigFunctions()
    {
        $functions = array(
            "renderSlot",
            "renderBlock",
            "blockContentToHtml",
            "renderIncludedBlock",
        );
        $this->assertEquals($functions, array_keys($this->slotRenderer->getFunctions()));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage renderSlot function requires a valid slot name to render the contents
     */
    public function testAnExceptionIsThrownWhenSlotNameIsNull()
    {
        $this->slotRenderer->renderSlot();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage renderSlot function requires a string as argument to identify the slot name
     */
    public function testAnExceptionIsThrownWhenSlotNameIsNotAString()
    {
        $this->slotRenderer->renderSlot(array());
    }

    public function testRenderAnEmptySlot()
    {
        $this->setUpContainer();
        $this->pageTree->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->returnValue(array()));

        $this->assertEquals($this->renderEmptySlot(), $this->slotRenderer->renderSlot('logo'));
    }

    public function testAnEmptySlotIsRenderedWhenAllBlockManagersAreNull()
    {
        $this->setUpContainer();
        $this->pageTree->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->returnValue(array(null)));

        $this->assertEquals($this->renderEmptySlot(), $this->slotRenderer->renderSlot('logo'));
    }

    public function testSlotHasNotBeenRenderedDueToAnUnexpectedException()
    {
        $this->setUpContainer();
        $this->pageTree->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->throwException(new \RuntimeException('Impossible to do something')));

        $expectedValue = '<div class="al_logo">Something was wrong rendering the logo slot. This is the returned error: Impossible to do something</div>';
        $this->assertEquals($expectedValue, $this->slotRenderer->renderSlot('logo'));
    }

    public function testEmptySlot()
    {
        $blockManagers = array($this->setUpBlockManager(array()));
        $this->pageTree->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->returnValue($blockManagers));
        
        $templating = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $templating->expects($this->never())
                        ->method('render');

        $this->container->expects($this->once())
                        ->method('get')
                        ->with('alpha_lemon_cms.page_tree')
                        ->will($this->returnValue($this->pageTree));
        
        $this->slotRenderer->renderSlot('logo');
    }
    
    /**
     * @dataProvider renderSlotProvider
     */
    public function testSlotHasBeenRendered($value, $editorParameters, $contentParameters)
    {
        $blockManager = $this->setUpBlockManager($value);
        $blockManager->expects($this->once())
            ->method('editorParameters')
            ->will($this->returnValue(array('foo' => 'bar')));
        
        $blockManagers = array($blockManager);
        $this->pageTree->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->returnValue($blockManagers));
        
        $engine = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $engine->expects($this->at(0))
                        ->method('render')
                        ->with('AlphaLemonCmsBundle:Slot:editable_block_attributes.html.twig', $editorParameters)
                        ->will($this->returnValue('data-foo="bar"'))
        ;
        
        $engine->expects($this->at(1))
                        ->method('render')
                        ->with('AlphaLemonCmsBundle:Slot:_block.html.twig', $contentParameters)
        ;

        $this->container->expects($this->at(0))
                        ->method('get')
                        ->with('alpha_lemon_cms.page_tree')
                        ->will($this->returnValue($this->pageTree));

        $this->container->expects($this->at(1))
                        ->method('get')
                        ->with('templating')
                        ->will($this->returnValue($engine));
        
        $this->slotRenderer->renderSlot('logo');
    }
    
    /**
     * @dataProvider renderBlockProvider
     */
    public function testRenderBlock($value, $editorParameters, $contentParameters, $included, $extraAttributes, $template = null)
    {
        $blockManager = $this->setUpBlockManager($value);
        $blockManager->expects($this->once())
            ->method('editorParameters')
            ->will($this->returnValue(array('foo' => 'bar')));
              
        $engine = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $engine->expects($this->at(0))
                        ->method('render')
                        ->with('AlphaLemonCmsBundle:Slot:editable_block_attributes.html.twig', $editorParameters)
                        ->will($this->returnValue($extraAttributes))
        ;
        
        $templateView = (null === $template) ? 'AlphaLemonCmsBundle:Slot:_block.html.twig' : 'AlphaLemonCmsBundle:Slot:' . $template;
        
        $engine->expects($this->at(1))
                        ->method('render')
                        ->with($templateView, $contentParameters)
        ;

        $this->container->expects($this->at(0))
                        ->method('get')
                        ->with('templating')
                        ->will($this->returnValue($engine));
        
        $this->slotRenderer->renderBlock($blockManager, $template, $included, $extraAttributes);
    }
    
    public function testRenderView()
    {
        $value = array(
            "Block" => array(
                "Id" => 10,
                "SlotName" => "logo",
                "Type" => "Text",
            ),
            "Content" => array("RenderView" => array(
                "view" => "AlphaLemonWebSite:Template:my_template.twig.html",
                "options" => array("foo" => "bar"),
            )),
            "InternalJavascript" => "",
            "EditInline" => false,
        );
        $blockManager = $this->setUpBlockManager($value);
        
        $blockManagers = array($blockManager);
        $this->pageTree->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->returnValue($blockManagers));
        
        $expected = array(
            "block_id" => 10,
            "slot_name" => "logo",
            "type" => "Text",
            "content" => "",
            "internal_javascript" => "",
            "edit_inline" => false,
        );
        $templating = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $templating->expects($this->once())
                   ->method('render')
                   ->with("AlphaLemonCmsBundle:Slot:_block.html.twig", $expected)
        ;
        
        
        $viewRenderer = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\ViewRenderer\AlViewRenderer')
                             ->disableOriginalConstructor()
                             ->getMock();
        
        $viewRendererExpected = array(
            "view" => "AlphaLemonWebSite:Template:my_template.twig.html",
            "options" => array(
                "foo" => "bar"
            ),
        );
        
        $viewRenderer->expects($this->once())
                        ->method('render')
                        ->with($viewRendererExpected);

        $this->container->expects($this->at(0))
                        ->method('get')
                        ->with('alpha_lemon_cms.page_tree')
                        ->will($this->returnValue($this->pageTree));

        $this->container->expects($this->at(1))
                        ->method('get')
                        ->with('templating')
                        ->will($this->returnValue($templating));
        
        $this->container->expects($this->at(2))
                        ->method('get')
                        ->with('alpha_lemon_cms.view_renderer')
                        ->will($this->returnValue($viewRenderer));
        
        $this->slotRenderer->renderSlot('logo');
    }
    
    public function testSlotMap()
    {
        $this->markTestSkipped(
            'Does not work correctly the very first time is runned by the full test suite.'
        );
        
        $value = array(
            "Block" => array(
                "Id" => null,
                "SlotName" => "logo",
            ),
            "Content" => "my awesome content",
        );
        $blockManagers = array($this->setUpBlockManager($value));
        $this->pageTree->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->returnValue($blockManagers));
        
        $expected = array(
            "slot_name" => "logo",
            "content" => "my awesome content",
        );
        $templating = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $templating->expects($this->once())
                        ->method('render')
                        ->with('AlphaLemonCmsBundle:Slot:map_slot.html.twig', $expected);

        $this->container->expects($this->at(0))
                        ->method('get')
                        ->with('alpha_lemon_cms.page_tree')
                        ->will($this->returnValue($this->pageTree));

        $this->container->expects($this->at(1))
                        ->method('get')
                        ->with('templating')
                        ->will($this->returnValue($templating));
        
        $this->slotRenderer->renderSlot('logo');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAnExceptionHasThrownBackWhenSomethingThrowsAnException()
    {
        $value = array(
            "Block" => array(
                "Id" => 10,
                "SlotName" => "logo",
                "Type" => "Text",
            ),
            "Content" => array(
                "RenderView" => array(
                    "view" => "AlphaLemonWebSite:Template:my_template.twig.html",
                    "options" => array(),
                )
            ),
        );
        $blockManager = $this->setUpBlockManager($value);

        $templating = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $templating->expects($this->never())
                        ->method('render');
        
        $viewRenderer = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\ViewRenderer\AlViewRenderer')
                             ->disableOriginalConstructor()
                             ->getMock();
        $viewRenderer->expects($this->once())
                        ->method('render')
                        ->will($this->throwException(new \RuntimeException()));

        $this->container->expects($this->at(0))
                        ->method('get')
                        ->with('templating')
                        ->will($this->returnValue($templating));
        
        $this->container->expects($this->at(1))
                        ->method('get')
                        ->with('alpha_lemon_cms.view_renderer')
                        ->will($this->returnValue($viewRenderer));

        $this->slotRenderer->renderBlock($blockManager);
    }
    
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage You must provide a valid AlBlockManager instance to automatically add a new Block
     */
    public function testRenderIncludedBlockThrowsAnExceptionWhenANewBlockMustBeAddedAndParentIsNull()
    {
        $key = '200-1';
        
        $factoryRepository = 
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepository')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $blocksRepository =
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $blocksRepository->expects($this->once())
            ->method('retrieveContentsBySlotName')
            ->with($key)
            ->will($this->returnValue(array()))
        ;
        
        $factoryRepository->expects($this->once())
            ->method('createRepository')
            ->with('Block')
            ->will($this->returnValue($blocksRepository))
        ;
        
        $factoryRepository->expects($this->once())
            ->method('createRepository')
            ->with('Block')
            ->will($this->returnValue($blocksRepository))
        ;
        
        $this->container->expects($this->at(0))
            ->method('get')
            ->with('alpha_lemon_cms.factory_repository')
            ->will($this->returnValue($factoryRepository))
        ;
        
        $this->slotRenderer->renderIncludedBlock($key, null, 'Text', true);
    }
    
    /**
     * @dataProvider renderIncludedBlockProvider
     */
    public function testRenderIncludedBlock($key, $expectedResult, $blocks = array(), $arguments = null)
    {
        $factoryRepository = 
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepository')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $blocksRepository =
            $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlBlockRepositoryPropel')
                 ->disableOriginalConstructor()
                 ->getMock()
        ;
        
        $blocksRepository->expects($this->once())
            ->method('retrieveContentsBySlotName')
            ->with($key)
            ->will($this->returnValue($blocks))
        ;
        
        $factoryRepository->expects($this->once())
            ->method('createRepository')
            ->with('Block')
            ->will($this->returnValue($blocksRepository))
        ;
        
        $factoryRepository->expects($this->once())
            ->method('createRepository')
            ->with('Block')
            ->will($this->returnValue($blocksRepository))
        ;
        
        $this->container->expects($this->at(0))
            ->method('get')
            ->with('alpha_lemon_cms.factory_repository')
            ->will($this->returnValue($factoryRepository))
        ;
        
        if (null === $arguments) {
            $arguments = array(
                'parent' => null,
                'type' => 'Text', 
                'addWhenEmpty' => false, 
                'defaultContent' => '', 
                'extraAttributes' => '', 
            );
        }
        
        if ($arguments["addWhenEmpty"]) {            
            $values = $expectedResult;  
            
            $blockManager = $this->setUpBlockManager();
            $blockManager->expects($this->once())
                ->method('save')
                ->with($values)
            ;
            
            $blockManager->expects($this->once())
                ->method('setEditorDisabled')
            ;
            
            $blockManagerFactory = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory')
                 ->disableOriginalConstructor()
                 ->getMock()
            ;
            
            $blockManagerFactory->expects($this->once())
                ->method('createBlockManager')
                ->with($arguments["type"])
                ->will($this->returnValue($blockManager))
            ;

            $this->container->expects($this->at(1))
                ->method('get')
                ->with('alpha_lemon_cms.block_manager_factory')
                ->will($this->returnValue($blockManagerFactory))
            ;
        }
        
        if (count($blocks) > 0) {    
            $blockManager = $this->setUpBlockManager();
            $blockManager->expects($this->once())
                ->method('set')
                ->with($blocks[0])
            ;
            
            $expectation = (null !== $arguments["parent"]) ? 1 : 0;
            $blockManager->expects($this->exactly($expectation))
                ->method('setEditorDisabled')
            ;
            
            $blockManagerFactory = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactory')
                 ->disableOriginalConstructor()
                 ->getMock()
            ;
            
            $blockManagerFactory->expects($this->once())
                ->method('createBlockManager')
                ->will($this->returnValue($blockManager))
            ;

            $this->container->expects($this->at(1))
                ->method('get')
                ->with('alpha_lemon_cms.block_manager_factory')
                ->will($this->returnValue($blockManagerFactory))
            ;
        }
        
        $result = $this->slotRenderer->renderIncludedBlock($key, $arguments["parent"], $arguments["type"], $arguments["addWhenEmpty"], $arguments["defaultContent"], $arguments["extraAttributes"]);
        
        if (is_string($expectedResult)) {
            $this->assertEquals($expectedResult, $result);
        }
    }
    
    public function renderIncludedBlockProvider()
    {
        return array(                
            array(
                '200-1',
                '<div data-editor="enabled" data-block-id="0" data-slot-name="200-1" data-included="1">This slot has any content inside. Use the contextual menu to add a new one</div>',
                array(),
            ),
            array(
                '200-1',
                array(
                    "PageId"          => 2,
                    "LanguageId"      => 2,
                    "SlotName"        => '200-1',
                    "Type"            => 'Text',
                    "ContentPosition" => 1,
                ),
                array(),
                array(
                    'parent' => $this->createParentBlockManager(),
                    'type' => 'Text', 
                    'addWhenEmpty' => true, 
                    'defaultContent' => '', 
                    'extraAttributes' => '', 
                ),
            ),
            array(
                '200-1',
                array(
                    "PageId"          => 2,
                    "LanguageId"      => 2,
                    "SlotName"        => '200-1',
                    "Type"            => 'Script',
                    "ContentPosition" => 1,
                ),
                array(),
                array(
                    'parent' => $this->createParentBlockManager(),
                    'type' => 'Script', 
                    'addWhenEmpty' => true, 
                    'defaultContent' => '', 
                    'extraAttributes' => '', 
                ),
            ),
            array(
                '200-1',
                array(
                    "PageId"          => 2,
                    "LanguageId"      => 2,
                    "SlotName"        => '200-1',
                    "Type"            => 'Text',
                    "ContentPosition" => 1,
                    'Content' => 'My awesome content',
                ),
                array(),
                array(
                    'parent' => $this->createParentBlockManager(),
                    'type' => 'Text', 
                    'addWhenEmpty' => true, 
                    'defaultContent' => 'My awesome content', 
                    'extraAttributes' => '', 
                ),
            ),
            array(
                '200-1',
                null,
                array(
                    $this->createBlock(),
                ),
            ),
            array(
                '200-1',
                null,
                array(
                    $this->createBlock(),
                ),
                array(
                    'parent' => $this->createParentBlockManager(),
                    'type' => 'Text', 
                    'addWhenEmpty' => false, 
                    'defaultContent' => '', 
                    'extraAttributes' => '', 
                ),
            ),
            array(
                '200-1',
                null,
                array(
                    $this->createBlock('Menu'),
                    $this->createBlock(),
                ),
            ),
        );
    }
    
    private function createBlock($type = 'Text')
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getType')
            ->will($this->returnValue($type))
        ; 
        
        return $block;
    }
    
    private function createParentBlockManager()
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->at(0))
            ->method('getPageId')
            ->will($this->returnValue(2))
        ;
        
        $block->expects($this->at(1))
            ->method('getLanguageId')
            ->will($this->returnValue(2))
        ;
        
        $blockManager = $this->setUpBlockManager();
        $blockManager->expects($this->any())
            ->method('get')
            ->will($this->returnValue($block))
        ;
        
        return $blockManager;
    }
        
    public function renderBlockProvider()
    {
        return array(
            array(
                array(
                    "Block" => array(
                        "Id" => 10,
                        "SlotName" => "logo",
                        "Type" => "Text",
                    ),
                    "Content" => '<p data-editor="true">my awesome content</p>',
                    "InternalJavascript" => "",
                    "EditInline" => false,
                ),
                array(
                    "block_id" => 10,
                    'hide_in_edit_mode' => 'false',
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-editor="true">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => false,
                    'editor' => array('foo' => 'bar'),
                    'extra_attributes' => '',
                    'included' => false,
                ),             
                array(
                    "block_id" => 10,
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p  data-editor="enabled">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => false,
                ),
                false,
                '',
                'custom.html.twig',
            ),
            array(
                array(
                    "Block" => array(
                        "Id" => 10,
                        "SlotName" => "logo",
                        "Type" => "Text",
                    ),
                    "Content" => '<p data-editor="true">my awesome content</p>',
                    "InternalJavascript" => "",
                    "EditInline" => false,
                ),
                array(
                    "block_id" => 10,
                    'hide_in_edit_mode' => 'false',
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-editor="true">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => false,
                    'editor' => array('foo' => 'bar'),
                    'extra_attributes' => '',
                    'included' => true,
                ),             
                array(
                    "block_id" => 10,
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p  data-editor="enabled">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => false,
                ),
                true,
                '',
            ),
            array(
                array(
                    "Block" => array(
                        "Id" => 10,
                        "SlotName" => "logo",
                        "Type" => "Text",
                    ),
                    "Content" => '<p data-editor="true">my awesome content</p>',
                    "InternalJavascript" => "",
                    "EditInline" => false,
                ),
                array(
                    "block_id" => 10,
                    'hide_in_edit_mode' => 'false',
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-editor="true">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => false,
                    'editor' => array('foo' => 'bar'),
                    'extra_attributes' => 'data-foo="bar"',
                    'included' => false,
                ),             
                array(
                    "block_id" => 10,
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-foo="bar" data-editor="enabled">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => false,
                ),
                false,
                'data-foo="bar"',
            ),
            array(
                array(
                    "Block" => array(
                        "Id" => 10,
                        "SlotName" => "logo",
                        "Type" => "Text",
                    ),
                    "Content" => '<p data-editor="true">my awesome content</p>',
                    "InternalJavascript" => "",
                    "EditInline" => false,
                ),
                array(
                    "block_id" => 10,
                    'hide_in_edit_mode' => 'false',
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-editor="true">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => false,
                    'editor' => array('foo' => 'bar'),
                    'extra_attributes' => "data-encoded-content='my awesome text'",
                    'included' => false,
                ),             
                array(
                    "block_id" => 10,
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-encoded-content=\'my%20awesome%20text\' data-editor="enabled">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => false,
                ),
                false,
                "data-encoded-content='my awesome text'",
                null,
            ),
        );
    }
    
    public function renderSlotProvider()
    {
        return array(
            array(
                array(
                    "Block" => array(
                        "Id" => "10",
                        "SlotName" => "logo",
                        "Type" => "Text",
                    ),
                    "Content" => '<p data-editor="true">my awesome content</p>',
                    "InternalJavascript" => "",
                    "EditInline" => false,
                ),
                array(
                    "block_id" => 10,
                    'hide_in_edit_mode' => 'false',
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-editor="true">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => false,
                    'editor' => array('foo' => 'bar'),
                    'extra_attributes' => '',
                    'included' => false,
                ),                
                array(
                    "block_id" => 10,
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-foo="bar" data-editor="enabled">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => false,
                ),
            ),
            array(
                array(
                    "Block" => array(
                        "Id" => "10",
                        "SlotName" => "logo",
                        "Type" => "Text",
                    ),
                    "Content" => '<p data-editor="true">my awesome content</p>',
                    "InternalJavascript" => "my awesome script",
                    "HideInEditMode" => false,
                    "ExecuteInternalJavascript" => false, // This blocks the internal javascript execution
                    "EditInline" => false,
                ),
                array(
                    "block_id" => 10,
                    'hide_in_edit_mode' => 'false',
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-editor="true">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => false,
                    'editor' => array('foo' => 'bar'),
                    'extra_attributes' => '',
                    'included' => false,
                ),      
                array(
                    "block_id" => 10,
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-foo="bar" data-editor="enabled">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => false,
                ),
            ),
            array(
                array(
                    "Block" => array(
                        "Id" => "10",
                        "SlotName" => "logo",
                        "Type" => "Text",
                    ),
                    "Content" => '<p data-editor="true">my awesome content</p>',
                    "InternalJavascript" => "my awesome script",
                    "HideInEditMode" => true,
                    "ExecuteInternalJavascript" => true,
                    "EditInline" => false,
                ),
                array(
                    "block_id" => 10,
                    "hide_in_edit_mode" => "true",
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-editor="true">my awesome content</p>',
                    "internal_javascript" => "my awesome script",
                    "edit_inline" => false,
                    'editor' => array('foo' => 'bar'),
                    'extra_attributes' => '',
                    'included' => false,
                ),    
                array(
                    "block_id" => 10,
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-foo="bar" data-editor="enabled">my awesome content</p>',
                    "internal_javascript" => "my awesome script",
                    "edit_inline" => false,
                ),
            ),
            array(
                array(
                    "Block" => array(
                        "Id" => "10",
                        "SlotName" => "logo",
                        "Type" => "Text",
                    ),
                    "Content" => '<p data-editor="true">my awesome content</p>',
                    "InternalJavascript" => "",
                    "HideInEditMode" => false,
                    "ExecuteInternalJavascript" => false,
                    "EditInline" => true,
                ),
                array(
                    "block_id" => 10,
                    "hide_in_edit_mode" => "false",
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-editor="true">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => true,
                    'editor' => array('foo' => 'bar'),
                    'extra_attributes' => '',
                    'included' => false,
                ),    
                array(
                    "block_id" => 10,
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => '<p data-foo="bar" data-editor="enabled">my awesome content</p>',
                    "internal_javascript" => "",
                    "edit_inline" => true,
                ),
            ),
            /* TODO
            array(
                array(
                    "Block" => array(
                        "Id" => "10",
                        "SlotName" => "logo",
                        "Type" => "Text",
                    ),
                    "Content" => "<script>my awesome script</script>",
                    "InternalJavascript" => "",
                    "EditInline" => false,
                ),
                array(
                    "block_id" => 10,
                    "hide_in_edit_mode" => "",
                    "slot_name" => "logo",
                    "type" => "Text",
                    "content" => "A script content is not rendered in editor mode",
                    "contents_hidden_script" => "",
                    "internal_javascript" => "",
                    "edit_inline" => false,
                ),
            ),*/
        );
    }
    

    private function setUpBlockManager(array $value = array())
    {
        $blockManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        if ( ! empty($value)) {
            $blockManager->expects($this->once())
                ->method('toArray')
                ->will($this->returnValue($value));
        }

        return $blockManager;
    }

    private function setUpContainer()
    {
        $this->pageTree->expects($this->any())
                       ->method('isCmsMode')
                       ->will($this->returnValue(true));

        $this->container->expects($this->once())
                        ->method('get')
                        ->with('alpha_lemon_cms.page_tree')
                        ->will($this->returnValue($this->pageTree));
    }

    private function renderEmptySlot()
    {
        $expectedValue = '<div class="al_logo">' . PHP_EOL;
        $expectedValue .= '<!-- BEGIN LOGO BLOCK -->' . PHP_EOL;
        $expectedValue .= '<div data-editor="enabled" data-block-id="0" data-slot-name="logo">This slot has any content inside. Use the contextual menu to add a new one</div>' . PHP_EOL;
        $expectedValue .= '<!-- END LOGO BLOCK -->' . PHP_EOL;
        $expectedValue .= '</div>';

        return $expectedValue;
    }
}
