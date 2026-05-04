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
}
