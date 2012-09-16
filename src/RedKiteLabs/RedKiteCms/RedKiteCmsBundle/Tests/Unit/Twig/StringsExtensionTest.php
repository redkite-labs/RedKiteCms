<?php

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Twig;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\AlphaLemonCmsBundle\Twig\StringsExtension;

/**
 * StringExtensionTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class StringsExtensionTest extends TestCase
{
    protected function setUp()
    {
        $this->stringExtension = new StringsExtension();
    }

    public function testTwigFunctions()
    {
        $functions = array(
            "left",
            "right",
            "truncate",
        );
        $this->assertEquals($functions, array_keys($this->stringExtension->getFunctions()));
    }

    public function testNameExtension()
    {
        $this->assertEquals('strings', $this->stringExtension->getName());
    }

    public function testLeftReturnsTheGivenValueWhenRequiredLengthIsZeroOrNegative()
    {
        $value = 'alphalemoncms';
        $this->assertEquals($value, $this->stringExtension->left($value, -1));
        $this->assertEquals($value, $this->stringExtension->left($value, 0));
    }

    public function testLeftReturnsTheGivenValueWhenRequiredLengthIsHigherThanStringLength()
    {
        $value = 'alphalemoncms';
        $this->assertEquals($value, $this->stringExtension->left($value, strlen($value) + 1));
    }

    public function testLeftReturnsTheStringAtTheRequiredLength()
    {
        $value = 'alphalemoncms';
        $this->assertEquals('alphalemon', $this->stringExtension->left($value, 10));
    }

    public function testRightReturnsTheGivenValueWhenRequiredLengthIsZeroOrNegative()
    {
        $value = 'alphalemoncms';
        $this->assertEquals($value, $this->stringExtension->right($value, -1));
        $this->assertEquals($value, $this->stringExtension->right($value, 0));
    }

    public function testRightReturnsTheGivenValueWhenRequiredLengthIsHigherThanStringLength()
    {
        $value = 'alphalemoncms';
        $this->assertEquals($value, $this->stringExtension->right($value, strlen($value) + 1));
    }

    public function testRightReturnsTheStringAtTheRequiredLength()
    {
        $value = 'alphalemoncms';
        $this->assertEquals('cms', $this->stringExtension->right($value, 3));
    }

    public function testTruncateReturnsTheGivenValueWhenRequiredLengthIsZeroOrNegative()
    {
        $value = 'alphalemoncms';
        $this->assertEquals($value, $this->stringExtension->truncate($value, -1));
        $this->assertEquals($value, $this->stringExtension->truncate($value, 0));
    }

    public function testTruncateReturnsTheGivenValueWhenRequiredLengthIsHigherThanStringLength()
    {
        $value = 'alphalemoncms';
        $this->assertEquals($value, $this->stringExtension->truncate($value, strlen($value) + 1));
    }

    public function testTruncateReturnsTheStringAtTheRequiredLength()
    {
        $value = 'alphalemoncms';
        $this->assertEquals('alpha...', $this->stringExtension->truncate($value, 5));
    }
}
