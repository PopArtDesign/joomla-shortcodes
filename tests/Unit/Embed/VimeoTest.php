<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit\Embed;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Vimeo;

class VimeoTest extends TestCase
{
    private Vimeo $vimeo;

    protected function setUp(): void
    {
        $this->vimeo = new Vimeo();
    }

    public function testNoUrl(): void
    {
        $result = $this->vimeo->process('', ['_' => []]);
        $this->assertEquals('', $result);
    }

    public function testBasicUsage(): void
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', ['_' => []]);
        $expected = '<iframe src="https://player.vimeo.com/video/123456789?autoplay=0&amp;loop=0" width="100%" height="auto" title="Vimeo video player" frameborder="0" allowfullscreen allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testCustomDimensions(): void
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', ['width' => '800', 'height' => '600', '_' => []]);
        $expected = '<iframe src="https://player.vimeo.com/video/123456789?autoplay=0&amp;loop=0" width="800" height="600" title="Vimeo video player" frameborder="0" allowfullscreen allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testStartTime(): void
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', ['start' => '1:30', '_' => []]);
        $expected = '<iframe src="https://player.vimeo.com/video/123456789?autoplay=0&amp;loop=0#t=90s" width="100%" height="auto" title="Vimeo video player" frameborder="0" allowfullscreen allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testAutoplay(): void
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', ['autoplay' => 'true', '_' => []]);
        $expected = '<iframe src="https://player.vimeo.com/video/123456789?autoplay=1&amp;loop=0" width="100%" height="auto" title="Vimeo video player" frameborder="0" allowfullscreen allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testLoop(): void
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', ['loop' => 'true', '_' => []]);
        $expected = '<iframe src="https://player.vimeo.com/video/123456789?autoplay=0&amp;loop=1" width="100%" height="auto" title="Vimeo video player" frameborder="0" allowfullscreen allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testAllAttributes(): void
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', [
            'width' => '1024',
            'height' => '768',
            'start' => '42',
            'end' => '60', // 1m
            'autoplay' => 'true',
            'loop' => 'true',
            'class' => 'my-class', // This class is for the wrapper now
            'title' => 'My Video',
            'allow' => 'autoplay',
            'referrerpolicy' => 'no-referrer',
            'aspect-ratio' => '4/3', // This applies to the wrapper now
            '_' => [],
        ]);
        $expected = '<iframe src="https://player.vimeo.com/video/123456789?autoplay=1&amp;loop=1#t=42s&amp;end=60" width="1024" height="768" title="My Video" frameborder="0" allowfullscreen allow="autoplay" referrerpolicy="no-referrer"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testVimeoUrl(): void
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', ['_' => []]);
        $this->assertStringContainsString('player.vimeo.com/video/123456789', $result);
    }

    public function testPlayerVimeoUrl(): void
    {
        $result = $this->vimeo->process('https://player.vimeo.com/video/123456789', ['_' => []]);
        $this->assertStringContainsString('player.vimeo.com/video/123456789', $result);
    }
}
