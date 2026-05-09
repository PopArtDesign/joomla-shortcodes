<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Rutube;

\defined('_JEXEC') or die;

class RutubeTest extends TestCase
{
    public function testRutubeUrlInContentIsProcessed(): void
    {
        $shortcode = new Rutube();
        $result = $shortcode([], 'https://rutube.ru/video/12345/');
        $this->assertStringContainsString('rutube.ru/play/embed/12345', $result);
    }

    public function testRutubeUrlAsAttributeIsProcessed(): void
    {
        $shortcode = new Rutube();
        $result = $shortcode(['url' => 'https://rutube.ru/video/12345/'], '');
        $this->assertStringContainsString('rutube.ru/play/embed/12345', $result);
    }

    public function testNonRutubeUrlIsNotProcessed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not extract Rutube video ID from URL: https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        $shortcode = new Rutube();
        $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'], '');
    }

    public function testRutubeUrlWithAutoplay(): void
    {
        $shortcode = new Rutube();
        $result = $shortcode(['url' => 'https://rutube.ru/video/12345/', 'autoplay' => 'true'], '');
        $this->assertStringContainsString('autoplay=true', $result);
        $this->assertStringContainsString('mute=true', $result);
    }

    public function testRutubeUrlWithMute(): void
    {
        $shortcode = new Rutube();
        $result = $shortcode(['url' => 'https://rutube.ru/video/12345/', 'mute' => 'true'], '');
        $this->assertStringContainsString('mute=true', $result);
        $this->assertStringNotContainsString('autoplay=', $result);
    }

    public function testRutubeUrlWithAutoplayAndMute(): void
    {
        $shortcode = new Rutube();
        $result = $shortcode(['url' => 'https://rutube.ru/video/12345/', 'autoplay' => 'true', 'mute' => 'true'], '');
        $this->assertStringContainsString('autoplay=true', $result);
        $this->assertStringContainsString('mute=true', $result);
    }

    public function testRutubeUrlWithLoop(): void
    {
        $shortcode = new Rutube();
        $result = $shortcode(['url' => 'https://rutube.ru/video/12345/', 'loop' => 'true'], '');
        $this->assertStringContainsString('loop=true', $result);
    }

    public function testRutubeUrlWithControls(): void
    {
        $shortcode = new Rutube();
        // Test with controls explicitly set to false (should add controls=false)
        $result = $shortcode(['url' => 'https://rutube.ru/video/12345/', 'controls' => 'false'], '');
        $this->assertStringContainsString('controls=false', $result);

        // Test with controls explicitly set to true (should not add controls=false as it's default)
        $result = $shortcode(['url' => 'https://rutube.ru/video/12345/', 'controls' => 'true'], '');
        $this->assertStringNotContainsString('controls=false', $result);

        // Test without controls attribute (should not add controls=false as it's default)
        $result = $shortcode(['url' => 'https://rutube.ru/video/12345/'], '');
        $this->assertStringNotContainsString('controls=false', $result);
    }
}
