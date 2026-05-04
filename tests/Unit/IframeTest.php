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

    public function testIframeUrlWithCustomDimensions(): void
    {
        $shortcode = new Iframe();
        $result = $shortcode(['url' => 'https://example.com/somepage', 'width' => '100%', 'height' => '400'], '');
        $this->assertStringContainsString('width="100%"', $result);
        $this->assertStringContainsString('height="400"', $result);
    }

    public function testIframeUrlWithCustomClass(): void
    {
        $shortcode = new Iframe();
        $result = $shortcode(['url' => 'https://example.com', 'class' => 'my-custom-class'], '');
        $this->assertStringContainsString('class="embed-container embed-iframe my-custom-class"', $result);
    }
}
