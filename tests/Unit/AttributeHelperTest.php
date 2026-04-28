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

    public function testParseTimeWithInvalidFormatReturnsZero(): void
    {
        $this->assertEquals(0, AttributeHelper::parseTime('invalid-time'));
    }
}
