<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

class AttributeHelperTest extends TestCase
{
    /**
     * Test cases for parseRange method.
     */
    public function testParseRangeWithSingleNumber(): void
    {
        $this->assertEquals([5, 5], AttributeHelper::parseRange('5'));
    }

    public function testParseRangeWithValidRange(): void
    {
        $this->assertEquals([5, 10], AttributeHelper::parseRange('5,10'));
    }

    public function testParseRangeWithInvalidRange(): void
    {
        $this->assertEquals([10, 10], AttributeHelper::parseRange('10,5'));
    }

    public function testParseRangeWithEmptyString(): void
    {
        $this->assertNull(AttributeHelper::parseRange(''));
    }

    public function testParseRangeWithEmptyStringAndDefault(): void
    {
        $this->assertEquals([1, 1], AttributeHelper::parseRange('', [1, 1]));
    }

    public function testParseRangeWithNonNumericParts(): void
    {
        $this->assertNull(AttributeHelper::parseRange('a,b'));
    }

    public function testParseRangeWithOneNumericPart(): void
    {
        $this->assertNull(AttributeHelper::parseRange('5,b'));
    }

    /**
     * Test cases for parseTag method.
     */
    public function testParseTagWithOnlyTag(): void
    {
        $this->assertEquals(['p', '', 1], AttributeHelper::parseTag('p'));
    }

    public function testParseTagWithTagAndCount(): void
    {
        $this->assertEquals(['p', '', 5], AttributeHelper::parseTag('p,5'));
    }

    public function testParseTagWithTagAndClass(): void
    {
        $this->assertEquals(['div', 'my-class', 1], AttributeHelper::parseTag('div.my-class'));
    }

    public function testParseTagWithTagClassAndCount(): void
    {
        $this->assertEquals(['ul', 'todos', 3], AttributeHelper::parseTag('ul.todos,3'));
    }

    public function testParseTagWithInvalidCount(): void
    {
        $this->assertEquals(['p', '', 1], AttributeHelper::parseTag('p,0'));
        $this->assertEquals(['p', '', 1], AttributeHelper::parseTag('p,-5'));
    }

    public function testParseTagWithEmptyString(): void
    {
        $this->assertNull(AttributeHelper::parseTag(''));
    }

    public function testParseTagWithEmptyStringAndDefault(): void
    {
        $this->assertEquals(['span', 'default', 1], AttributeHelper::parseTag('', ['span', 'default', 1]));
    }

    public function testParseTagWithNoClassButComma(): void
    {
        $this->assertEquals(['p', '', 10], AttributeHelper::parseTag('p,10'));
    }

    public function testParseTagWithTrimmedValues(): void
    {
        $this->assertEquals(['ul', 'my-class', 5], AttributeHelper::parseTag(' ul . my-class , 5 '));
    }

    /**
     * Test cases for parseTime method.
     */
    public function testParseTimeWithSeconds(): void
    {
        $this->assertEquals(60, AttributeHelper::parseTime('60'));
    }

    public function testParseTimeWithMinutesAndSeconds(): void
    {
        $this->assertEquals(90, AttributeHelper::parseTime('1:30'));
    }

    public function testParseTimeWithHoursMinutesAndSeconds(): void
    {
        $this->assertEquals(3723, AttributeHelper::parseTime('1:02:03')); // 1 hour, 2 minutes, 3 seconds
    }

    public function testParseTimeWithInvalidFormatReturnsDefault(): void
    {
        $this->assertNull(AttributeHelper::parseTime('invalid-time'));
        $this->assertEquals(10, AttributeHelper::parseTime('invalid-time', 10));
    }

    public function testParseTimeWithEmptyStringReturnsDefault(): void
    {
        $this->assertNull(AttributeHelper::parseTime(''));
        $this->assertEquals(20, AttributeHelper::parseTime('', 20));
    }

    /**
     * Test cases for parseBoolean method.
     */
    public function testParseBooleanWithTrueValues(): void
    {
        $this->assertTrue(AttributeHelper::parseBoolean('true'));
        $this->assertTrue(AttributeHelper::parseBoolean('1'));
        $this->assertTrue(AttributeHelper::parseBoolean('yes'));
        $this->assertTrue(AttributeHelper::parseBoolean('true', false)); // With default
    }

    public function testParseBooleanWithFalseValues(): void
    {
        $this->assertFalse(AttributeHelper::parseBoolean('false'));
        $this->assertFalse(AttributeHelper::parseBoolean('0'));
        $this->assertFalse(AttributeHelper::parseBoolean('no'));
        $this->assertFalse(AttributeHelper::parseBoolean('false', true)); // With default
    }

    public function testParseBooleanWithEmptyStringReturnsDefault(): void
    {
        $this->assertNull(AttributeHelper::parseBoolean(''));
        $this->assertTrue(AttributeHelper::parseBoolean('', true));
        $this->assertFalse(AttributeHelper::parseBoolean('', false));
    }

    public function testParseBooleanWithUnrecognizedStringReturnsDefault(): void
    {
        $this->assertNull(AttributeHelper::parseBoolean('any_string'));
        $this->assertTrue(AttributeHelper::parseBoolean('any_string', true));
        $this->assertFalse(AttributeHelper::parseBoolean('any_string', false));
    }

    /**
     * Test cases for isEnabled method.
     */
    public function testIsEnabledWithTrueValue(): void
    {
        $attributes = ['autoplay' => 'true'];
        $this->assertTrue(AttributeHelper::isEnabled('autoplay', $attributes));
    }

    public function testIsEnabledWithFalseValue(): void
    {
        $attributes = ['autoplay' => 'false'];
        $this->assertFalse(AttributeHelper::isEnabled('autoplay', $attributes));
    }

    public function testIsEnabledWithValuelessAttribute(): void
    {
        $attributes = ['_' => ['autoplay']];
        $this->assertTrue(AttributeHelper::isEnabled('autoplay', $attributes));
    }

    public function testIsEnabledWhenAttributeMissing(): void
    {
        $attributes = [];
        $this->assertFalse(AttributeHelper::isEnabled('autoplay', $attributes));
    }

    public function testIsEnabledWhenAttributeMissingAnd_IsEmpty(): void
    {
        $attributes = ['_' => []];
        $this->assertFalse(AttributeHelper::isEnabled('autoplay', $attributes));
    }

    public function testIsEnabledWithOtherValuelessAttributes(): void
    {
        $attributes = ['_' => ['loop', 'autoplay']];
        $this->assertTrue(AttributeHelper::isEnabled('autoplay', $attributes));
        $this->assertTrue(AttributeHelper::isEnabled('loop', $attributes));
        $this->assertFalse(AttributeHelper::isEnabled('random', $attributes));
    }

    public function testIsEnabledWithMixedAttributes(): void
    {
        $attributes = ['autoplay' => 'false', '_' => ['autoplay']]; // Explicit value takes precedence
        $this->assertFalse(AttributeHelper::isEnabled('autoplay', $attributes));

        $attributes = ['autoplay' => 'true', '_' => ['autoplay']];
        $this->assertTrue(AttributeHelper::isEnabled('autoplay', $attributes));
    }
}
