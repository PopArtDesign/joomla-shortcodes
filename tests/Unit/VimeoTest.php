<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Vimeo;

\defined('_JEXEC') or die;

class VimeoTest extends TestCase
{
    public function testVimeoUrlInContentIsProcessed(): void
    {
        $shortcode = new Vimeo();
        $result = $shortcode([], 'https://vimeo.com/12345');
        $this->assertStringContainsString('player.vimeo.com/video/12345', $result);
    }

    public function testVimeoUrlAsAttributeIsProcessed(): void
    {
        $shortcode = new Vimeo();
        $result = $shortcode(['url' => 'https://vimeo.com/12345'], '');
        $this->assertStringContainsString('player.vimeo.com/video/12345', $result);
    }

    public function testNonVimeoUrlIsNotProcessed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not extract Vimeo video ID from URL: https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        $shortcode = new Vimeo();
        $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'], '');
    }

    public function testVimeoUrlWithCustomDimensions(): void
    {
        $shortcode = new Vimeo();
        $result = $shortcode(['url' => 'https://vimeo.com/12345', 'width' => '800', 'height' => '600'], '');
        $this->assertStringContainsString('style="aspect-ratio: var(--embed-video-aspect-ratio, 16 / 9); width: 800px; height: 600px"', $result);
        $this->assertStringContainsString('width="100%"', $result);
        $this->assertStringContainsString('height="100%"', $result);
    }

    public function testVimeoUrlWithAutoplay(): void
    {
        $shortcode = new Vimeo();
        $result = $shortcode(['url' => 'https://vimeo.com/12345', 'autoplay' => 'true'], '');
        $this->assertStringContainsString('autoplay=1&amp;loop=0&amp;muted=1', $result);
    }

    public function testVimeoUrlWithMute(): void
    {
        $shortcode = new Vimeo();
        $result = $shortcode(['url' => 'https://vimeo.com/12345', 'mute' => 'true'], '');
        $this->assertStringContainsString('autoplay=0&amp;loop=0&amp;muted=1', $result);
    }

    public function testVimeoUrlWithAutoplayAndMute(): void
    {
        $shortcode = new Vimeo();
        $result = $shortcode(['url' => 'https://vimeo.com/12345', 'autoplay' => 'true', 'mute' => 'true'], '');
        $this->assertStringContainsString('autoplay=1&amp;loop=0&amp;muted=1', $result);
    }

    public function testVimeoUrlWithLoop(): void
    {
        $shortcode = new Vimeo();
        $result = $shortcode(['url' => 'https://vimeo.com/12345', 'loop' => 'true'], '');
        $this->assertStringContainsString('loop=1', $result);
    }

    public function testVimeoUrlWithControls(): void
    {
        $shortcode = new Vimeo();
        // Test with controls explicitly set to false (should add controls=0)
        $result = $shortcode(['url' => 'https://vimeo.com/12345', 'controls' => 'false'], '');
        $this->assertStringContainsString('controls=0', $result);

        // Test with controls explicitly set to true (should add controls=1)
        $result = $shortcode(['url' => 'https://vimeo.com/12345', 'controls' => 'true'], '');
        $this->assertStringContainsString('controls=1', $result);

        // Test without controls attribute (should add controls=1 as it's default)
        $result = $shortcode(['url' => 'https://vimeo.com/12345'], '');
        $this->assertStringContainsString('controls=1', $result);
    }
}
