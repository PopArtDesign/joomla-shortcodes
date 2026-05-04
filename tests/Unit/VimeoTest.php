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
        $this->assertStringContainsString('width="800"', $result);
        $this->assertStringContainsString('height="600"', $result);
    }
}
