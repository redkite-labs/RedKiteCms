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

    public function testRenderTheEmptySlot()
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
        
        $templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
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
    public function testSlotHasBeenRendered($value, $expected)
    {
        $blockManagers = array($this->setUpBlockManager($value));
        $this->pageTree->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->returnValue($blockManagers));
        
        $templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $templating->expects($this->once())
                        ->method('render')
                        ->with('AlphaLemonCmsBundle:Slot:editable_block.html.twig', $expected);

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
                    "Content" => "my awesome content",
                    "InternalJavascript" => "",
                    "EditorWidth" => 800,
                ),
                array(
                    "block_id" => 10,
                    "hide_in_edit_mode" => "",
                    "slot_name" => "logo",
                    "type" => "Text",
                    "editor_width" => 800,
                    "content" => "my awesome content",
                    "contents_hidden_script" => "",
                    "internal_javascript" => "",
                ),
            ),
            array(
                array(
                    "Block" => array(
                        "Id" => "10",
                        "SlotName" => "logo",
                        "Type" => "Text",
                    ),
                    "Content" => "my awesome content",
                    "InternalJavascript" => "my awesome script",
                    "EditorWidth" => 800,
                    "HideInEditMode" => "true",
                    "ExecuteInternalJavascript" => false,
                ),
                array(
                    "block_id" => 10,
                    "hide_in_edit_mode" => "al_hide_edit_mode",
                    "slot_name" => "logo",
                    "type" => "Text",
                    "editor_width" => 800,
                    "content" => "my awesome content",
                    "contents_hidden_script" => "$('#block_10').data('block', 'my%20awesome%20content');",
                    "internal_javascript" => "",
                ),
            ),
            array(
                array(
                    "Block" => array(
                        "Id" => "10",
                        "SlotName" => "logo",
                        "Type" => "Text",
                    ),
                    "Content" => "my awesome content",
                    "InternalJavascript" => "my awesome script",
                    "EditorWidth" => 800,
                    "HideInEditMode" => "true",
                    "ExecuteInternalJavascript" => true,
                ),
                array(
                    "block_id" => 10,
                    "hide_in_edit_mode" => "al_hide_edit_mode",
                    "slot_name" => "logo",
                    "type" => "Text",
                    "editor_width" => 800,
                    "content" => "my awesome content",
                    "contents_hidden_script" => "$('#block_10').data('block', 'my%20awesome%20content');",
                    "internal_javascript" => "my awesome script",
                ),
            ),
            array(
                array(
                    "Block" => array(
                        "Id" => "10",
                        "SlotName" => "logo",
                        "Type" => "Text",
                    ),
                    "Content" => "<script>my awesome script</script>",
                    "InternalJavascript" => "",
                    "EditorWidth" => 800,
                ),
                array(
                    "block_id" => 10,
                    "hide_in_edit_mode" => "",
                    "slot_name" => "logo",
                    "type" => "Text",
                    "editor_width" => 800,
                    "content" => "A script content is not rendered in editor mode",
                    "contents_hidden_script" => "",
                    "internal_javascript" => "",
                ),
            ),
        );
    }
    
    public function testRenderView()
    {
        $value = array(
            "Block" => array(
                "Id" => "10",
                "SlotName" => "logo",
                "Type" => "Text",
            ),
            "Content" => array("RenderView" => array(
                "view" => "AlphaLemonWebSite:Template:my_template.twig.html",
                "options" => array("foo" => "bar"),
            )),
            "InternalJavascript" => "",
            "EditorWidth" => 800,
        );
        $blockManagers = array($this->setUpBlockManager($value));
        $this->pageTree->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->returnValue($blockManagers));
        
        $expected = array(
            "block_id" => 10,
            "hide_in_edit_mode" => "",
            "slot_name" => "logo",
            "type" => "Text",
            "editor_width" => 800,
            "content" => "",
            "contents_hidden_script" => "",
            "internal_javascript" => "",
        );
        $templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $templating->expects($this->at(0))
                        ->method('render')
                        ->with("AlphaLemonWebSite:Template:my_template.twig.html", array("foo" => "bar"));
        
        $templating->expects($this->at(1))
                        ->method('render')
                        ->with('AlphaLemonCmsBundle:Slot:editable_block.html.twig', $expected);

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
    
    public function testSlotMap()
    {
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
        $templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
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
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage renderBlock function requires an array to render its contents. A null block argument has given
     */
    public function testAnExceptionIsThrownWhenBlockArgumentIsNull()
    {
        $this->slotRenderer->renderBlock();
    }

    public function testBlockIsRenderedAsNewBlock()
    {
        $value = array(
            "Block" => array(
                "Id" => "10",
                "SlotName" => "logo",
                "Type" => "Text",
            ),
            "Content" => "my awesome content",
            "InternalJavascript" => "",
            "EditorWidth" => 800,
        );
        
        $expected = array(
            "block_id" => 10,
            "hide_in_edit_mode" => "",
            "slot_name" => "logo",
            "type" => "Text",
            "editor_width" => 800,
            "content" => "my awesome content",
            "contents_hidden_script" => "",
            "internal_javascript" => "",
        );
        $templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $templating->expects($this->once())
                        ->method('render')
                        ->with('AlphaLemonCmsBundle:Slot:editable_block.html.twig', $expected);

        $this->container->expects($this->once())
                        ->method('get')
                        ->with('templating')
                        ->will($this->returnValue($templating));
        
        $this->slotRenderer->renderBlock($value, true);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAnExceptionHasThrownBackWhenSomethingThrowsAnException()
    {
        $value = array(
            "Block" => array(
                "Id" => "10",
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

        $templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $templating->expects($this->once())
                        ->method('render')
                        ->will($this->throwException(new \RuntimeException()));

        $this->container->expects($this->once())
                        ->method('get')
                        ->with('templating')
                        ->will($this->returnValue($templating));

        $this->slotRenderer->renderBlock($value);
    }

    private function setUpBlockManager(array $value)
    {
        $blockManager = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $blockManager->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue($value));

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
        $expectedValue .= '<div class="al_editable {id: \'0\', slotName: \'logo\'}">This slot has any content inside. Use the contextual menu to add a new one</div>' . PHP_EOL;
        $expectedValue .= '<!-- END LOGO BLOCK -->' . PHP_EOL;
        $expectedValue .= '</div>';

        return $expectedValue;
    }
}
