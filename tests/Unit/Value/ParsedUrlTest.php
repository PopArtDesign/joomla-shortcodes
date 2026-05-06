<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit\Value;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Value\ParsedUrl;
use PHPUnit\Framework\TestCase;

class ParsedUrlTest extends TestCase
{
    public function testHasDomain(): void
    {
        $parsedUrl = new ParsedUrl(['host' => 'example.com', 'original' => 'http://example.com', 'type' => ParsedUrl::ABSOLUTE]);
        $this->assertTrue($parsedUrl->hasDomain('example.com'));
        $this->assertTrue($parsedUrl->hasDomain(['example.com', 'test.com']));
        $this->assertFalse($parsedUrl->hasDomain('anotherexample.com'));
        $this->assertFalse($parsedUrl->hasDomain([]));

        $parsedUrlWithWww = new ParsedUrl(['host' => 'www.example.com', 'original' => 'http://www.example.com', 'type' => ParsedUrl::ABSOLUTE]);
        $this->assertFalse($parsedUrlWithWww->hasDomain('example.com'));
        $this->assertTrue($parsedUrlWithWww->hasDomain('www.example.com'));

        $parsedUrlNullHost = new ParsedUrl(['path' => '/test', 'original' => '/test', 'type' => ParsedUrl::RELATIVE]);
        $this->assertFalse($parsedUrlNullHost->hasDomain('example.com'));
    }

    public function testHasExtension(): void
    {
        $parsedUrl = new ParsedUrl(['extension' => 'pdf', 'original' => 'file.pdf', 'type' => ParsedUrl::RELATIVE]);
        $this->assertTrue($parsedUrl->hasExtension('pdf'));
        $this->assertTrue($parsedUrl->hasExtension(['pdf', 'jpg']));
        $this->assertFalse($parsedUrl->hasExtension('png'));

        $parsedUrlCaps = new ParsedUrl(['extension' => 'PDF', 'original' => 'file.PDF', 'type' => ParsedUrl::RELATIVE]);
        $this->assertTrue($parsedUrlCaps->hasExtension('pdf'));

        $parsedUrlNullExt = new ParsedUrl(['path' => '/file', 'original' => '/file', 'type' => ParsedUrl::RELATIVE]);
        $this->assertFalse($parsedUrlNullExt->hasExtension('pdf'));

        $parsedUrlWithExt = new ParsedUrl(['extension' => 'pdf', 'original' => 'file.pdf', 'type' => ParsedUrl::RELATIVE]);
        $this->assertFalse($parsedUrlWithExt->hasExtension(''));
        $this->assertFalse($parsedUrlWithExt->hasExtension([]));
    }

    public function testToString(): void
    {
        $url = 'http://example.com/file.pdf';
        $parsedUrl = new ParsedUrl(['original' => $url, 'type' => ParsedUrl::ABSOLUTE]);
        $this->assertSame($url, (string) $parsedUrl);
    }

    public function testHasType(): void
    {
        $parsedUrl = new ParsedUrl(['type' => ParsedUrl::ABSOLUTE, 'original' => 'http://example.com']);
        $this->assertTrue($parsedUrl->hasType(ParsedUrl::ABSOLUTE));
        $this->assertTrue($parsedUrl->hasType([ParsedUrl::ABSOLUTE, ParsedUrl::RELATIVE]));
        $this->assertFalse($parsedUrl->hasType(ParsedUrl::RELATIVE));
        $this->assertTrue($parsedUrl->hasType([]));
        $this->assertTrue($parsedUrl->hasType(null));
    }
}
