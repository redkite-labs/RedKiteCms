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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\Deploy\TwigTemplateWriter;

use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\TwigTemplateWriter\AlTwigTemplateWriterBase;

/**
 * AlTwigTemplateWriterPagesTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlTwigTemplateWriterBaseTest extends BaseAlTwigTemplateWriter
{
    /**
     * @dataProvider creditsProvider
     */
    public function testGenerateFullTemplate($credits)
    {
        $blocks = array(
            "logo" =>
                array(
                    $this->setUpBlock('logo')
                ),
            "nav-menu" =>
                array(
                    $this->setUpBlock('nav-menu'),
                    $this->setUpBlock('nav-menu'),
                    $this->setUpBlock('nav-menu')
                )
        );
        
        $slots = array(
            'logo' => array('repeated' => 'site'),
            'nav-menu' => array('repeated' => 'language'),
        );

        $this->urlManager->expects($this->any())
            ->method('getProductionRoute')
            ->will($this->returnValue('_en_index'));

        $this->setUpTemplateSlots($slots);
        $this->setUpMetatagsAndAssets("A title", "A description", "some,keywords", array('style1.css', 'style2.css'), array('javascript1.js', 'javascript2.js'), 'some css code', 'some js code');
        $this->setUpPageBlocks($blocks);
        
        $blockManager1 = $this->setUpBlockManager(null);
        $blockManager2 = $this->setUpBlockManager(null);
        $blockManager3 = $this->setUpBlockManager(null);
        $blockManager3->expects($this->any())
            ->method('getMetaTags')
            ->will($this->returnValue(array('RenderView' => array('AcmeWebsiteBundle:Metatags:extra_metatags.html.twig'))))
        ;
        
        $blockManager4 = $this->setUpBlockManager(null);        
        $blockManager4->expects($this->any())
            ->method('getMetaTags')
            ->will($this->returnValue('Metatags rendered froma block'))
        ;
        
        $this->template->expects($this->once())
            ->method('getThemeName')
            ->will($this->returnValue('AcmeWebsiteBundle'));
        
        $this->blockManagerFactory->expects($this->any())
            ->method('createBlockManager')
            ->will($this->onConsecutiveCalls($blockManager1, $blockManager2, $blockManager3, $blockManager4));
        
        $this->blockManagerFactory->expects($this->at(0))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager1));
        $this->blockManagerFactory->expects($this->at(1))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager2));
        $this->blockManagerFactory->expects($this->at(2))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager3));
        $this->blockManagerFactory->expects($this->at(3))
            ->method('createBlockManager')
            ->will($this->returnValue($blockManager4));
        
        $this->viewRenderer
                ->expects($this->at(0))
                ->method('render')
                ->will($this->returnValue('<img width="381" height="87" title="Download" alt="download.png" src="/bundles/redkitecms/uploads/assets/media/download.png">'))
            ;
        $this->viewRenderer
                ->expects($this->at(1))
                ->method('render')
                ->will($this->returnValue('<div>A new content</div>'))
            ;
        $this->viewRenderer
                ->expects($this->at(2))
                ->method('render')
                ->will($this->returnValue('<div>Some other text <img width="381" height="87" title="Download" alt="download.png" src="/bundles/redkitecms/uploads/assets/media/image.png"></div>'))
            ;
        
        $this->viewRenderer
                ->expects($this->at(3))
                ->method('render')
                ->will($this->returnValue('Some block\'s metatags rendered from a view'))
            ;
        
        $this->viewRenderer
                ->expects($this->at(4))
                ->method('render')
                ->will($this->returnValue('<div>Lorem ipsum <ul><li><a href="my-awesome-page">Fancy page</a></li></ul></div>'))
            ;

        $section = "{% extends 'AcmeWebsiteBundle:Theme:home.html.twig' %}" . PHP_EOL;
        $section .= "\n{#--------------  METATAGS EXTRA SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block metatags %}" . PHP_EOL;
        $section .= "{{ parent() }}" . PHP_EOL;
        $section .= "Some block's metatags rendered from a view" . PHP_EOL;
        $section .= "Metatags rendered froma block" . PHP_EOL;
        $section .= "{% endblock %}" . PHP_EOL;
        $section .= "\n\n{#--------------  CONTENTS SECTION  --------------#}" . PHP_EOL;
        $section .= "{% block logo %}" . PHP_EOL;
        $section .= "    <!-- BEGIN LOGO BLOCK -->" . PHP_EOL;
        $section .= "    <img width=\"381\" height=\"87\" title=\"Download\" alt=\"download.png\" src=\"/bundles/acmewebsite/media/download.png\">" . PHP_EOL;
        $section .= "    <!-- END LOGO BLOCK -->" . PHP_EOL;
        $section .= "{% endblock %}" . PHP_EOL;
        $section .= "" . PHP_EOL;
        $section .= "{% block nav-menu %}" . PHP_EOL;
        $section .= "    <!-- BEGIN NAV-MENU BLOCK -->" . PHP_EOL;
        $section .= "    <div>A new content</div>" . PHP_EOL;
        $section .= "    <div>Some other text <img width=\"381\" height=\"87\" title=\"Download\" alt=\"download.png\" src=\"/bundles/acmewebsite/media/image.png\"></div>" . PHP_EOL;
        $section .= "    <div>Lorem ipsum <ul><li><a href=\"{{ path('_en_index') }}\">Fancy page</a></li></ul></div>" . PHP_EOL;
        $section .= "    <!-- END NAV-MENU BLOCK -->" . PHP_EOL;
        $section .= "{% endblock %}\n" . PHP_EOL;
        
        if ($credits) {
            $section .= $this->addSomeLove();
        }

        $imagesPath = array('backendPath' => "/bundles/redkitecms/uploads/assets",
            'prodPath' => "/bundles/acmewebsite");
        $twigTemplateWriter = new AlTwigTemplateWriterBase($this->pageTree, $this->blockManagerFactory, $this->urlManager, $this->viewRenderer, $imagesPath);
        $generatedTemplate = $twigTemplateWriter
            ->setCredits($credits)
            ->generateTemplate()
            ->getTwigTemplate()
        ;
        
        $this->assertEquals($section, $generatedTemplate);
    }
    
    public function creditsProvider()
    {
        return array(
            array(
                true,
            ),
            array(
                false,
            ),
        );
    }
}
