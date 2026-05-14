<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\UrlHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Value\ParsedUrl;

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

    public function testParseRangeWithNegativeNumbers(): void
    {
        $this->assertEquals([-5, -1], AttributeHelper::parseRange('-5,-1'));
    }

    public function testParseRangeWithZero(): void
    {
        $this->assertEquals([0, 0], AttributeHelper::parseRange('0,0'));
        $this->assertEquals([0, 5], AttributeHelper::parseRange('0,5'));
    }

    public function testParseRangeWithWhitespace(): void
    {
        $this->assertEquals([5, 10], AttributeHelper::parseRange('  5, 10  '));
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

    public function testParseTagWithNonNumericCount(): void
    {
        $this->assertEquals(['p', '', 1], AttributeHelper::parseTag('p,foo'));
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

    public function testParseTimeWithWhitespace(): void
    {
        $this->assertEquals(90, AttributeHelper::parseTime(' 1:30 '));
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

    public function testParseBooleanWithMixedCase(): void
    {
        $this->assertTrue(AttributeHelper::parseBoolean('True'));
        $this->assertTrue(AttributeHelper::parseBoolean('YES'));
        $this->assertFalse(AttributeHelper::parseBoolean('False'));
        $this->assertFalse(AttributeHelper::parseBoolean('No'));
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

    public function testIsEnabledWithNullOrEmptyValue(): void
    {
        $attributes = ['autoplay' => null];
        $this->assertFalse(AttributeHelper::isEnabled('autoplay', $attributes));

        $attributes = ['autoplay' => ''];
        $this->assertFalse(AttributeHelper::isEnabled('autoplay', $attributes));
    }

    /**
     * Test cases for getUrl method.
     */
    public function testGetUrlFromUrlAttribute(): void
    {
        $url = 'https://example.com/doc.pdf';
        $attributes = ['url' => $url];
        $expectedParsedUrl = UrlHelper::parseUrl($url);
        $this->assertEquals($expectedParsedUrl, AttributeHelper::getUrl($attributes, ''));
    }

    public function testGetUrlFromContent(): void
    {
        $url = 'https://example.com/doc.pdf';
        $expectedParsedUrl = UrlHelper::parseUrl($url);
        $this->assertEquals($expectedParsedUrl, AttributeHelper::getUrl([], $url));
    }

    public function testGetUrlFromPositionalAttribute(): void
    {
        $url = 'https://example.com/doc.pdf';
        $attributes = [$url];
        $expectedParsedUrl = UrlHelper::parseUrl($url);
        $this->assertEquals($expectedParsedUrl, AttributeHelper::getUrl($attributes, ''));
    }

    public function testGetUrlReturnsNullForInvalidUrlAttribute(): void
    {
        $this->assertNull(AttributeHelper::getUrl(['url' => 'invalid url <script>'], ''));
    }

    public function testGetUrlReturnsNullForInvalidUrlInContent(): void
    {
        $this->assertNull(AttributeHelper::getUrl([], 'invalid url <script>'));
    }

    public function testGetUrlReturnsNullForInvalidUrlAsPositionalAttribute(): void
    {
        $this->assertNull(AttributeHelper::getUrl(['invalid url <script>'], ''));
    }

    public function testGetUrlReturnsNullWhenNoUrlFound(): void
    {
        $this->assertNull(AttributeHelper::getUrl([], ''));
    }

    public function testGetUrlReturnsRelativeUrl(): void
    {
        $url1 = '/media/doc.pdf';
        $expectedParsedUrl1 = UrlHelper::parseUrl($url1);
        $this->assertEquals($expectedParsedUrl1, AttributeHelper::getUrl(['url' => $url1], ''));

        $url2 = 'doc.pdf';
        $expectedParsedUrl2 = UrlHelper::parseUrl($url2);
        $this->assertEquals($expectedParsedUrl2, AttributeHelper::getUrl([], $url2));

        $url3 = '../doc.pdf';
        $expectedParsedUrl3 = UrlHelper::parseUrl($url3);
        $this->assertEquals($expectedParsedUrl3, AttributeHelper::getUrl([$url3], ''));
    }

    /**
     * Test cases for getValue method.
     */
    public function testGetValueFromNamedAttribute(): void
    {
        $attributes = ['foo' => 'bar', 'url' => 'https://example.com'];
        $this->assertEquals('bar', AttributeHelper::getValue($attributes, '', 'foo'));
        $this->assertEquals('https://example.com', AttributeHelper::getValue($attributes, '', 'url'));
    }

    public function testGetValueFromContent(): void
    {
        $attributes = ['foo' => ''];
        $this->assertEquals('some content', AttributeHelper::getValue($attributes, 'some content', 'foo'));
        $this->assertEquals('another content', AttributeHelper::getValue([], 'another content'));
    }

    public function testGetValueFromPositionalAttribute(): void
    {
        $attributes = ['foo' => '', 'bar'];
        $this->assertEquals('bar', AttributeHelper::getValue($attributes, '', 'foo'));
        $this->assertEquals('baz', AttributeHelper::getValue(['baz'], ''));
    }

    public function testGetValuePrecedenceNamedAttributeTakesPrecedence(): void
    {
        // Named attribute 'foo' exists and is not empty, should return its value
        $attributes = ['foo' => 'named_value', '0' => 'positional_value'];
        $content = 'content_value';
        $this->assertEquals('named_value', AttributeHelper::getValue($attributes, $content, 'foo'));
    }

    public function testGetValuePrecedenceContentTakesPrecedenceOverPositionalWhenNamedIsEmpty(): void
    {
        // Named attribute 'foo' is empty, content is not empty, should return content
        $attributes = ['foo' => '', '0' => 'positional_value'];
        $content = 'content_value';
        $this->assertEquals('content_value', AttributeHelper::getValue($attributes, $content, 'foo'));
    }

    public function testGetValuePrecedencePositionalTakesPrecedenceWhenNamedAndContentAreEmpty(): void
    {
        // Named attribute 'foo' is empty, content is empty, positional is not empty, should return positional
        $attributes = ['foo' => '', '0' => 'positional_value'];
        $content = '';
        $this->assertEquals('positional_value', AttributeHelper::getValue($attributes, $content, 'foo'));
    }

    public function testGetValuePrecedenceContentTakesPrecedenceWhenNoKeyAndNotEmpty(): void
    {
        // No explicit key, content not empty, should return content
        $attributes = ['0' => 'positional_value'];
        $content = 'content_value';
        $this->assertEquals('content_value', AttributeHelper::getValue($attributes, $content, null));
    }

    public function testGetValuePrecedencePositionalTakesPrecedenceWhenNoKeyAndContentIsEmpty(): void
    {
        // No explicit key, content empty, positional not empty, should return positional
        $attributes = ['0' => 'positional_value'];
        $content = '';
        $this->assertEquals('positional_value', AttributeHelper::getValue($attributes, $content, null));
    }

    public function testGetValueReturnsNullWhenNothingFound(): void
    {
        $attributes = ['foo' => ''];
        $this->assertNull(AttributeHelper::getValue($attributes, '', 'foo')); // Empty named, empty content, no positional
        $this->assertNull(AttributeHelper::getValue([], '')); // Empty everywhere
    }

    public function testGetValueWithWhitespace(): void
    {
        $attributes = ['key' => '  value with spaces  '];
        $this->assertEquals('value with spaces', AttributeHelper::getValue($attributes, '', 'key'));

        $this->assertEquals('content with spaces', AttributeHelper::getValue([], '  content with spaces  '));

        $attributes = ['  positional with spaces  '];
        $this->assertEquals('positional with spaces', AttributeHelper::getValue($attributes, ''));
    }

    public function testGetValueWithNonStringAttributeValue(): void
    {
        $attributes = ['key' => 123];
        $this->assertEquals('123', AttributeHelper::getValue($attributes, '', 'key'));

        $attributes = ['key' => true];
        $this->assertEquals('1', AttributeHelper::getValue($attributes, '', 'key'));

        $attributes = ['key' => false];
        $this->assertEquals('', AttributeHelper::getValue($attributes, '', 'key'));
    }
}
