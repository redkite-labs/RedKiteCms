<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\UrlManager;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Core\ViewRenderer\AlViewRenderer;

/**
 * AlViewRendererTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
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