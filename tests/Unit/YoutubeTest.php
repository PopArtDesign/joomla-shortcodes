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

    public function testNonYoutubeUrlIsNotProcessed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not extract YouTube video ID from URL: https://vimeo.com/12345');
        $shortcode = new Youtube();
        $shortcode(['url' => 'https://vimeo.com/12345'], '');
    }

    public function testYoutubeUrlWithCustomDimensions(): void
    {
        $shortcode = new Youtube();
        $result = $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'width' => '800', 'height' => '600'], '');
                $this->assertStringContainsString('style="aspect-ratio: var(--embed-video-aspect-ratio, 16 / 9);width: 800;height: 600"', $result);
                $this->assertStringContainsString('width="100%"', $result);
                $this->assertStringContainsString('height="100%"', $result);    }

    public function testYoutubeUrlWithAutoplay(): void
    {
        $shortcode = new Youtube();
        $result = $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'autoplay' => true], '');
        $this->assertStringContainsString('autoplay=1&amp;mute=1', $result);
    }
}
