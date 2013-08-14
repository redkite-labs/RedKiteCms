<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
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

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Core\UrlManager;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Core\ViewRenderer\AlViewRenderer;

/**
 * AlViewRendererTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlViewRendererTest extends TestCase
{
    private $templating;
            
    protected function setUp()
    {
        parent::setUp();

        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->viewRenderer = new AlViewRenderer($this->templating);        
    }

    /**
     * @dataProvider invalidOptionsProvider
     */
    public function testAnEmptyContentIsReturnedWhenAnyOptionIsRecognized($views)
    {
        $this->templating
             ->expects($this->never())
             ->method('render')
        ;
        
        $content = $this->viewRenderer->render($views);
        $this->assertEmpty($content);
    }
    
    public function invalidOptionsProvider($views)
    {
        $views = array(
            array(
                array(
                    'wiev' => 'MyBundle:Default:index.html.twig',
                ),
            ),
            array(
                'wievs' => array(
                    'wiev' => 'MyBundle:Default:index.html.twig',
                ),
            ),
        );
        
        return $views;
    }
    
    /**
     * @dataProvider validOptionsProvider
     */
    public function testAnEmptyContentIsReturnedWhenAnyOptionIsRecognized1($views, $template, $options)
    {
        $this->templating
             ->expects($this->once())
             ->method('render')
             ->with($template, $options)
        ;
        
        $content = $this->viewRenderer->render($views);
        $this->assertEmpty($content);
    }
    
    public function validOptionsProvider($views)
    {
        $views = array(
            array(
                array(
                    'view' => 'MyBundle:Default:index.html.twig',
                ),
                'MyBundle:Default:index.html.twig',
                array(),
            ),
            array(
                'views' => array(
                    'view' => 'MyBundle:Default:index.html.twig',
                ),
                'MyBundle:Default:index.html.twig',
                array(),
            ),
            array(
                array(
                    'view' => 'MyBundle:Default:index.html.twig',
                    'option' => array(),
                ),
                'MyBundle:Default:index.html.twig',
                array(),
            ),
            array(
                'views' => array(
                    'view' => 'MyBundle:Default:index.html.twig',
                    'option' => array(),
                ),
                'MyBundle:Default:index.html.twig',
                array(),
            ),
            array(
                array(
                    'view' => 'MyBundle:Default:index.html.twig',
                    'options' => array('foo' => 'bar'),
                ),
                'MyBundle:Default:index.html.twig',
                array('foo' => 'bar'),
            ),
            array(
                'views' => array(
                    'view' => 'MyBundle:Default:index.html.twig',
                    'options' => array('foo' => 'bar'),
                ),
                'MyBundle:Default:index.html.twig',
                array('foo' => 'bar'),
            ),
        );
        
        return $views;
    }
}