<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit\Helper;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\UrlHelper;
use PHPUnit\Framework\TestCase;

class UrlHelperValidateUrlTest extends TestCase
{
    /**
     * @dataProvider validateUrlProvider
     */
    public function testValidateUrl(string $url, $type, bool $expected): void
    {
        $this->assertSame($expected, UrlHelper::validateUrl($url, $type));
    }

    public static function validateUrlProvider(): array
    {
        return [
            // Absolute URLs
            'absolute url, type absolute' => ['http://example.com', 'absolute', true],
            'absolute url, type relative' => ['http://example.com', 'relative', false],
            'absolute url, type [absolute, relative]' => ['http://example.com', ['absolute', 'relative'], true],
            'absolute url, type any' => ['http://example.com', 'any', true],
            'absolute url, type null' => ['http://example.com', null, true],
            'absolute url, type empty array' => ['http://example.com', [], true],

            // Relative URLs
            'relative url, type absolute' => ['/path/to/file', 'absolute', false],
            'relative url, type relative' => ['/path/to/file', 'relative', true],
            'relative url, type [absolute, relative]' => ['/path/to/file', ['absolute', 'relative'], true],
            'relative url, type any' => ['/path/to/file', 'any', true],
            'relative url, type null' => ['/path/to/file', null, true],
            'relative url, type empty array' => ['/path/to/file', [], true],

            // Protocol-relative URLs
            'protocol-relative url, type absolute' => ['//example.com', 'absolute', false],
            'protocol-relative url, type relative' => ['//example.com', 'relative', false],
            'protocol-relative url, type protocol-relative' => ['//example.com', 'protocol-relative', true],
            'protocol-relative url, type [absolute, protocol-relative]' => ['//example.com', ['absolute', 'protocol-relative'], true],
            'protocol-relative url, type any' => ['//example.com', 'any', true],

            // Invalid URLs
            'empty url, type any' => ['', 'any', false],
            'url with forbidden chars, type any' => ['http://ex{ample.com', 'any', false],
            'url with invalid scheme, type any' => ['1http://example.com', 'any', false],

            // Edge cases
            'relative url with query, type relative' => ['path?foo=bar', 'relative', true],
            'relative url with fragment, type relative' => ['path#foo', 'relative', true],
        ];
    }

    public function testValidateUrlThrowsExceptionForInvalidType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        UrlHelper::validateUrl('http://example.com', 'invalid-type');
    }

    public function testValidateUrlThrowsExceptionForInvalidTypeInArray(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        UrlHelper::validateUrl('http://example.com', ['absolute', 'invalid-type']);
    }
}
