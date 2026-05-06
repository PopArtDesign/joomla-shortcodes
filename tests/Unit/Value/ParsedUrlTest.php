<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit\Value;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\UrlHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Value\ParsedUrl;
use PHPUnit\Framework\TestCase;

class ParsedUrlTest extends TestCase
{
    public function testHasDomain(): void
    {
        $parsedUrl = new ParsedUrl(['host' => 'example.com', 'original' => 'http://example.com', 'type' => 'absolute']);
        $this->assertTrue($parsedUrl->hasDomain('example.com'));
        $this->assertTrue($parsedUrl->hasDomain(['example.com', 'test.com']));
        $this->assertFalse($parsedUrl->hasDomain('anotherexample.com'));
        $this->assertFalse($parsedUrl->hasDomain([]));

        $parsedUrlWithWww = new ParsedUrl(['host' => 'www.example.com', 'original' => 'http://www.example.com', 'type' => 'absolute']);
        $this->assertFalse($parsedUrlWithWww->hasDomain('example.com'));
        $this->assertTrue($parsedUrlWithWww->hasDomain('www.example.com'));

        $parsedUrlNullHost = new ParsedUrl(['path' => '/test', 'original' => '/test', 'type' => 'relative']);
        $this->assertFalse($parsedUrlNullHost->hasDomain('example.com'));
    }

    public function testHasExtension(): void
    {
        $parsedUrl = new ParsedUrl(['extension' => 'pdf', 'original' => 'file.pdf', 'type' => 'relative']);
        $this->assertTrue($parsedUrl->hasExtension('pdf'));
        $this->assertTrue($parsedUrl->hasExtension(['pdf', 'jpg']));
        $this->assertFalse($parsedUrl->hasExtension('png'));

        $parsedUrlCaps = new ParsedUrl(['extension' => 'PDF', 'original' => 'file.PDF', 'type' => 'relative']);
        $this->assertTrue($parsedUrlCaps->hasExtension('pdf'));

        $parsedUrlNullExt = new ParsedUrl(['path' => '/file', 'original' => '/file', 'type' => 'relative']);
        $this->assertFalse($parsedUrlNullExt->hasExtension('pdf'));

        $parsedUrlWithExt = new ParsedUrl(['extension' => 'pdf', 'original' => 'file.pdf', 'type' => 'relative']);
        $this->assertFalse($parsedUrlWithExt->hasExtension(''));
        $this->assertFalse($parsedUrlWithExt->hasExtension([]));
    }

    public function testToString(): void
    {
        $url = 'http://example.com/file.pdf';
        $parsedUrl = new ParsedUrl(['original' => $url, 'type' => 'absolute']);
        $this->assertSame($url, (string) $parsedUrl);
    }

    public function testHasType(): void
    {
        $parsedUrl = new ParsedUrl(['type' => UrlHelper::ABSOLUTE, 'original' => 'http://example.com']);
        $this->assertTrue($parsedUrl->hasType(UrlHelper::ABSOLUTE));
        $this->assertTrue($parsedUrl->hasType([UrlHelper::ABSOLUTE, UrlHelper::RELATIVE]));
        $this->assertTrue($parsedUrl->hasType(UrlHelper::ANY));
        $this->assertTrue($parsedUrl->hasType([UrlHelper::ANY, UrlHelper::RELATIVE]));
        $this->assertFalse($parsedUrl->hasType(UrlHelper::RELATIVE));
        $this->assertTrue($parsedUrl->hasType([])); // Empty array should be treated as ANY
    }
}
