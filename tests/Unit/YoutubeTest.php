<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Youtube;

\defined('_JEXEC') or die;

class YoutubeTest extends TestCase
{
    public function testYoutubeUrlInContentIsProcessed(): void
    {
        $shortcode = new Youtube();
        $result = $shortcode([], 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        $this->assertStringContainsString('youtube.com/embed/dQw4w9WgXcQ', $result);
    }

    public function testYoutubeUrlAsAttributeIsProcessed(): void
    {
        $shortcode = new Youtube();
        $result = $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'], '');
        $this->assertStringContainsString('youtube.com/embed/dQw4w9WgXcQ', $result);
    }

    public function testNonYoutubeUrlReturnsError(): void
    {
        $shortcode = new Youtube();
        $result = $shortcode(['url' => 'https://vimeo.com/12345'], '');
        $this->assertStringContainsString('<div class="shortcode-error"', $result);
        $this->assertStringContainsString('<b>Youtube</b>: Could not extract video ID from URL.', $result);
    }

    public function testYoutubeUrlWithCustomDimensions(): void
    {
        $shortcode = new Youtube();
        $result = $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'width' => '800', 'height' => '600'], '');
        $this->assertStringContainsString('style="aspect-ratio: var(--embed-video-aspect-ratio, 16 / 9); width: 800px; height: 600px"', $result);
        $this->assertStringContainsString('width="100%"', $result);
        $this->assertStringContainsString('height="100%"', $result);
    }

    public function testYoutubeUrlWithAutoplay(): void
    {
        $shortcode = new Youtube();
        $result = $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'autoplay' => true], '');
        $this->assertStringContainsString('autoplay=1&amp;mute=1', $result);
    }

    public function testYoutubeUrlWithMute(): void
    {
        $shortcode = new Youtube();
        $result = $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'mute' => 'true'], '');
        $this->assertStringContainsString('mute=1', $result);
        $this->assertStringNotContainsString('autoplay=', $result);
    }

    public function testYoutubeUrlWithLoop(): void
    {
        $shortcode = new Youtube();
        $result = $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'loop' => 'true'], '');
        $this->assertStringContainsString('loop=1', $result);
        $this->assertStringContainsString('playlist=dQw4w9WgXcQ', $result);
    }

    public function testYoutubeUrlWithControls(): void
    {
        $shortcode = new Youtube();
        // Test with controls explicitly set to false (should add controls=0)
        $result = $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'controls' => 'false'], '');
        $this->assertStringContainsString('controls=0', $result);

        // Test with controls explicitly set to true (should not add controls=0 as it's default)
        $result = $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'controls' => 'true'], '');
        $this->assertStringNotContainsString('controls=0', $result);

        // Test without controls attribute (should not add controls=0 as it's default)
        $result = $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'], '');
        $this->assertStringNotContainsString('controls=0', $result);
    }
}
