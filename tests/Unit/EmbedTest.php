<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcoder\ShortcodeProcessor;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Youtube;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Gist;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Vimeo;
use JoomlaShortcoder\Plugin\Content\Shortcoder\Exception\ShortcodeProcessingException;
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

    public function testEmptyEmbed(): void
    {
        $this->expectException(ShortcodeProcessingException::class);
        $text = '{embed}{/embed}';
        $this->processShortcodes($text);
    }

    public function testEmbedWithUrlAttribute(): void
    {
        $text = '{embed url="https://www.youtube.com/watch?v=dQw4w9WgXcQ"}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('dQw4w9WgXcQ', $result);
    }

    public function testEmbedYoutubeWithNestedContent(): void
    {
        $text = '{embed}https://www.youtube.com/watch?v=dQw4w9WgXcQ{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('dQw4w9WgXcQ', $result);
    }

    public function testEmbedYoutubeWithYoutuBe(): void
    {
        $text = '{embed}https://youtu.be/dQw4w9WgXcQ{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('dQw4w9WgXcQ', $result);
    }

    public function testEmbedYoutubeWithEmbedUrl(): void
    {
        $text = '{embed}https://www.youtube.com/embed/dQw4w9WgXcQ{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('dQw4w9WgXcQ', $result);
    }

    public function testEmbedYoutubeWithoutScheme(): void
    {
        $this->expectException(ShortcodeProcessingException::class);
        $text = '{embed}www.youtube.com/watch?v=dQw4w9WgXcQ{/embed}';
        $this->processShortcodes($text);
    }

    public function testEmbedYoutubeWithCustomDimensions(): void
    {
        $text = '{embed url="https://www.youtube.com/watch?v=dQw4w9WgXcQ" width="800" height="600"}{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('width="800"', $result);
        $this->assertStringContainsString('height="600"', $result);
    }

    public function testEmbedGist(): void
    {
        $text = '{embed}https://gist.github.com/testuser/12345{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('gist.github.com/testuser/12345.js', $result);
    }

    public function testEmbedGistWithFile(): void
    {
        $text = '{embed url="https://gist.github.com/testuser/12345" file="test.php"}{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('gist.github.com/testuser/12345.js?file=test.php', $result);
    }

    public function testEmbedGenericUrl(): void
    {
        $text = '{embed}https://example.com/article{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('example.com/article', $result);
        $this->assertStringContainsString('iframe', $result);
    }

    public function testEmbedGenericUrlWithoutScheme(): void
    {
        $this->expectException(ShortcodeProcessingException::class);
        $text = '{embed}example.com/article{/embed}';
        $this->processShortcodes($text);
    }

    public function testEmbedWithBooleanAttributeAndUrlInContent(): void
    {
        $text = '{embed autoplay}https://www.youtube.com/watch?v=dQw4w9WgXcQ{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('dQw4w9WgXcQ', $result);
        $this->assertStringContainsString('autoplay', $result); // Also check if autoplay is passed correctly
    }

    public function testEmbedGenericUrlWithCustomClass(): void
    {
        $text = '{embed url="https://example.com" width="100%" height="400" class="my-embed"}{/embed}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('class="embed-container embed-iframe my-embed"', $result);
        $this->assertStringContainsString('width="100%"', $result);
        $this->assertStringContainsString('height="400"', $result);
    }
}
