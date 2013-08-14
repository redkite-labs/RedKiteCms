<?php

namespace RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Twig;

use RedKiteLabs\RedKiteCmsBundle\Tests\TestCase;
use RedKiteLabs\RedKiteCmsBundle\Twig\StringsExtension;

/**
 * StringExtensionTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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
        $value = 'redkitecms';
        $this->assertEquals($value, $this->stringExtension->left($value, -1));
        $this->assertEquals($value, $this->stringExtension->left($value, 0));
    }

    public function testLeftReturnsTheGivenValueWhenRequiredLengthIsHigherThanStringLength()
    {
        $value = 'redkitecms';
        $this->assertEquals($value, $this->stringExtension->left($value, strlen($value) + 1));
    }

    public function testLeftReturnsTheStringAtTheRequiredLength()
    {
        $value = 'redkitecms';
        $this->assertEquals('alphalemon', $this->stringExtension->left($value, 10));
    }

    public function testRightReturnsTheGivenValueWhenRequiredLengthIsZeroOrNegative()
    {
        $value = 'redkitecms';
        $this->assertEquals($value, $this->stringExtension->right($value, -1));
        $this->assertEquals($value, $this->stringExtension->right($value, 0));
    }

    public function testRightReturnsTheGivenValueWhenRequiredLengthIsHigherThanStringLength()
    {
        $value = 'redkitecms';
        $this->assertEquals($value, $this->stringExtension->right($value, strlen($value) + 1));
    }

    public function testRightReturnsTheStringAtTheRequiredLength()
    {
        $value = 'redkitecms';
        $this->assertEquals('cms', $this->stringExtension->right($value, 3));
    }

    public function testTruncateReturnsTheGivenValueWhenRequiredLengthIsZeroOrNegative()
    {
        $value = 'redkitecms';
        $this->assertEquals($value, $this->stringExtension->truncate($value, -1));
        $this->assertEquals($value, $this->stringExtension->truncate($value, 0));
    }

    public function testTruncateReturnsTheGivenValueWhenRequiredLengthIsHigherThanStringLength()
    {
        $value = 'redkitecms';
        $this->assertEquals($value, $this->stringExtension->truncate($value, strlen($value) + 1));
    }

    public function testTruncateReturnsTheStringAtTheRequiredLength()
    {
        $value = 'redkitecms';
        $this->assertEquals('alpha...', $this->stringExtension->truncate($value, 5));
    }
}
