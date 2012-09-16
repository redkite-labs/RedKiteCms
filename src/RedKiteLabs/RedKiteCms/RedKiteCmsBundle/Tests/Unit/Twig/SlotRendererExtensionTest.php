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

    public function testSlotHasBeenRendereda()
    {
        $this->setUpContainer();
        $this->pageTree->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->throwException(new \RuntimeException('Impossibile to do something')));

        $expectedValue = '<div class="al_logo">Something was wrong rendering the logo slot. This is the returned error: Impossibile to do something</div>';
        $this->assertEquals($expectedValue, $this->slotRenderer->renderSlot('logo'));
    }

    public function testSlotHasBeenRendered()
    {
        $this->setUpContainer();
        $value = array(
            "Block" => array(
                "Id" => "10",
                "SlotName" => "logo",
                "ClassName" => "Text",
            ),
            "HtmlContent" => "my awesome content",
        );

        $blockManagers = array($this->setUpBlockManager($value));
        $this->pageTree->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->returnValue($blockManagers));

        $expectedValue = '<div class="al_logo">' . PHP_EOL;
        $expectedValue .= '<!-- BEGIN LOGO BLOCK -->' . PHP_EOL;
        $expectedValue .= '<div id="block_10" class=" al_editable {id: \'10\', slotName: \'logo\', type: \'text\'}"><div>my awesome content</div></div>' . PHP_EOL;
        $expectedValue .= '<!-- END LOGO BLOCK -->' . PHP_EOL;
        $expectedValue .= '</div>';
        $this->assertEquals($expectedValue, $this->slotRenderer->renderSlot('logo'));
    }

    public function testASlotHideInEditModeHasBeenRendered()
    {
        $this->setUpContainer();
        $value = array(
            "Block" => array(
                "Id" => "10",
                "SlotName" => "logo",
                "ClassName" => "Text",
            ),
            "HtmlContent" => "my awesome content",
            "HideInEditMode" => "true",
        );

        $blockManagers = array($this->setUpBlockManager($value));
        $this->pageTree->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->returnValue($blockManagers));

        $expectedValue = '<div class="al_logo">' . PHP_EOL;
        $expectedValue .= '<!-- BEGIN LOGO BLOCK -->' . PHP_EOL;
        $expectedValue .= '<div id="block_10" class="al_hide_edit_mode al_editable {id: \'10\', slotName: \'logo\', type: \'text\'}"><div>my awesome content</div></div>' . PHP_EOL;
        $expectedValue .= '<!-- END LOGO BLOCK -->' . PHP_EOL;
        $expectedValue .= '</div>';
        $this->assertEquals($expectedValue, $this->slotRenderer->renderSlot('logo'));
    }

    public function testSlotHasBeenRenderedFromATwigTemplate()
    {
        $value = array(
            "Block" => array(
                "Id" => "10",
                "SlotName" => "logo",
                "ClassName" => "Text",
            ),
            "HtmlContent" => "my awesome replaced content",
            "RenderView" => array(
                "view" => "AlphaLemonWebSite:Template:my_template.twig.html",
                "params" => array(),
            )
        );

        $blockManagers = array($this->setUpBlockManager($value));
        $this->pageTree->expects($this->once())
            ->method('getBlockManagers')
            ->will($this->returnValue($blockManagers));

        $templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $templating->expects($this->once())
                        ->method('render')
                        ->will($this->returnValue('<p>This content has been rendered from a twig template</p>'));

        $this->container->expects($this->exactly(2))
                        ->method('get')
                        ->will($this->onConsecutiveCalls($this->pageTree, $templating));

        $expectedValue = '<div class="al_logo">' . PHP_EOL;
        $expectedValue .= '<!-- BEGIN LOGO BLOCK -->' . PHP_EOL;
        $expectedValue .= '<div id="block_10" class=" al_editable {id: \'10\', slotName: \'logo\', type: \'text\'}"><div><p>This content has been rendered from a twig template</p></div></div>' . PHP_EOL;
        $expectedValue .= '<!-- END LOGO BLOCK -->' . PHP_EOL;
        $expectedValue .= '</div>';
        $this->assertEquals($expectedValue, $this->slotRenderer->renderSlot('logo'));
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
                "ClassName" => "Text",
            ),
            "HtmlContent" => "my awesome content",
        );

        $expectedValue = '<div id="block_10" class=" al_editable {id: \'10\', slotName: \'logo\', type: \'text\'}"><div>my awesome content</div></div>';
        $this->assertEquals($expectedValue, $this->slotRenderer->renderBlock($value, true));
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
