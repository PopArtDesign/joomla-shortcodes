<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Iframe;

\defined('_JEXEC') or die;

class IframeTest extends TestCase
{
    public function testIframeUrlInContentIsProcessed(): void
    {
        $shortcode = new Iframe();
        $result = $shortcode([], 'https://example.com/somepage');
        $this->assertStringContainsString('example.com/somepage', $result);
        $this->assertStringContainsString('iframe', $result);
    }

    public function testIframeUrlAsAttributeIsProcessed(): void
    {
        $shortcode = new Iframe();
        $result = $shortcode(['url' => 'https://example.com/somepage'], '');
        $this->assertStringContainsString('example.com/somepage', $result);
        $this->assertStringContainsString('iframe', $result);
    }

    public function testUrlAttributeTakesPrecedenceOverContent(): void
    {
        $shortcode = new Iframe();
        $result = $shortcode(['url' => 'https://example.com/attribute'], 'https://example.com/content');
        $this->assertStringContainsString('example.com/attribute', $result);
        $this->assertStringNotContainsString('example.com/content', $result);
    }

    public function testIframeElementHasFullWidthAndHeight(): void
    {
        $shortcode = new Iframe();
        $result = $shortcode(['url' => 'https://example.com/somepage', 'width' => '100', 'height' => '200'], '');
        $this->assertStringContainsString('width="100%"', $result);
        $this->assertStringContainsString('height="100%"', $result);
        $this->assertStringNotContainsString('width="100"', $result);
        $this->assertStringNotContainsString('height="200"', $result);
    }

    public function testIframeUrlWithCustomClass(): void
    {
        $shortcode = new Iframe();
        $result = $shortcode(['url' => 'https://example.com', 'class' => 'my-custom-class'], '');
        $this->assertStringContainsString('class="embed-container embed-iframe my-custom-class"', $result);
    }

    public function testNoUrlThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $shortcode = new Iframe();
        $shortcode([], '');
    }

    public function testInvalidUrlThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $shortcode = new Iframe();
        $shortcode(['url' => 'javascript:alert("xss")'], '');
    }

    public function testInvalidUrlInContentThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $shortcode = new Iframe();
        $shortcode([], 'javascript:alert("xss")');
    }

    public function testIframeHasDefaultAttributes(): void
    {
        $shortcode = new Iframe();
        $result = $shortcode(['url' => 'https://example.com/somepage'], '');
        $this->assertStringContainsString('frameborder="0"', $result);
        $this->assertStringContainsString('allowfullscreen', $result);
        $this->assertStringContainsString('referrerpolicy="strict-origin-when-cross-origin"', $result);
        $this->assertStringContainsString('loading="lazy"', $result);
    }
}
