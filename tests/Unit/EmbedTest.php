<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcoder\ShortcodeProcessor;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Youtube;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Gist;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Vimeo;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Iframe;

class EmbedTest extends TestCase
{
    private static ShortcodeProcessor $processor;

    public static function setUpBeforeClass(): void
    {
        self::$processor = new ShortcodeProcessor([
            'embed' => new Embed([
                new Youtube(),
                new Gist(),
                new Vimeo(),
                new Iframe()
            ]),
        ]);
    }

    private function processShortcodes(string $text): string
    {
        return self::$processor->processShortcodes($text, new \stdClass());
    }

    public function testEmptyEmbed()
    {
        $text = '{embed}{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertEquals('', $result);
    }

    public function testEmbedWithUrlAttribute()
    {
        $text = '{embed url="https://www.youtube.com/watch?v=dQw4w9WgXcQ"}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('dQw4w9WgXcQ', $result);
    }

    public function testEmbedYoutubeWithNestedContent()
    {
        $text = '{embed}https://www.youtube.com/watch?v=dQw4w9WgXcQ{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('dQw4w9WgXcQ', $result);
    }

    public function testEmbedYoutubeWithYoutuBe()
    {
        $text = '{embed}https://youtu.be/dQw4w9WgXcQ{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('dQw4w9WgXcQ', $result);
    }

    public function testEmbedYoutubeWithEmbedUrl()
    {
        $text = '{embed}https://www.youtube.com/embed/dQw4w9WgXcQ{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('dQw4w9WgXcQ', $result);
    }

    public function testEmbedYoutubeWithoutScheme()
    {
        $text = '{embed}www.youtube.com/watch?v=dQw4w9WgXcQ{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('dQw4w9WgXcQ', $result);
    }

    public function testEmbedYoutubeWithCustomDimensions()
    {
        $text = '{embed url="https://www.youtube.com/watch?v=dQw4w9WgXcQ" width="800" height="600"}{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('width="800"', $result);
        $this->assertStringContainsString('height="600"', $result);
    }

    public function testEmbedGist()
    {
        $text = '{embed}https://gist.github.com/testuser/12345{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('gist.github.com/testuser/12345.js', $result);
    }

    public function testEmbedGistWithFile()
    {
        $text = '{embed url="https://gist.github.com/testuser/12345" file="test.php"}{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('gist.github.com/testuser/12345.js?file=test.php', $result);
    }

    public function testEmbedGistWithShortSyntax()
    {
        $text = '{embed}testuser/12345{/embed}';
        $result = $this->processShortcodes($text);
        // Short syntax no longer supported - falls back to iframe
        $this->assertStringContainsString('embed-container', $result);
        $this->assertStringContainsString('testuser/12345', $result);
    }

    public function testEmbedGenericUrl()
    {
        $text = '{embed}https://example.com/article{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('example.com/article', $result);
        $this->assertStringContainsString('iframe', $result);
    }

    public function testEmbedGenericUrlWithoutScheme()
    {
        $text = '{embed}example.com/article{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('https://example.com/article', $result);
        $this->assertStringContainsString('iframe', $result);
    }

    public function testEmbedGenericUrlWithCustomClass()
    {
        $text = '{embed url="https://example.com" width="100%" height="400" class="my-embed"}{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('class="my-embed"', $result);
        $this->assertStringContainsString('width="100%"', $result);
        $this->assertStringContainsString('height="400"', $result);
    }
}
