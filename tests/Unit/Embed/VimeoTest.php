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

    public function testNoUrl()
    {
        $result = $this->vimeo->process('', ['_' => []]);
        $this->assertEquals('', $result);
    }

    public function testBasicUsage()
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', ['_' => []]);
        $expected = '<iframe src="https://player.vimeo.com/video/123456789?autoplay=0&loop=0" width="100%" height="auto" title="Vimeo video player" frameborder="0" allowfullscreen class="vimeo-container" allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: 16 / 9;"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testCustomDimensions()
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', ['width' => '800', 'height' => '600', '_' => []]);
        $expected = '<iframe src="https://player.vimeo.com/video/123456789?autoplay=0&loop=0" width="800" height="600" title="Vimeo video player" frameborder="0" allowfullscreen class="vimeo-container" allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testStartTime()
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', ['start' => '1m30s', '_' => []]);
        $expected = '<iframe src="https://player.vimeo.com/video/123456789?autoplay=0&loop=0" width="100%" height="auto" title="Vimeo video player" frameborder="0" allowfullscreen class="vimeo-container" allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: 16 / 9;"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testAutoplay()
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', ['autoplay' => 'true', '_' => []]);
        $expected = '<iframe src="https://player.vimeo.com/video/123456789?autoplay=1&loop=0" width="100%" height="auto" title="Vimeo video player" frameborder="0" allowfullscreen class="vimeo-container" allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: 16 / 9;"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testLoop()
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', ['loop' => 'true', '_' => []]);
        $expected = '<iframe src="https://player.vimeo.com/video/123456789?autoplay=0&loop=1" width="100%" height="auto" title="Vimeo video player" frameborder="0" allowfullscreen class="vimeo-container" allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: 16 / 9;"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testAllAttributes()
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', [
            'width' => '1024',
            'height' => '768',
            'start' => '42',
            'end' => '1m',
            'autoplay' => 'true',
            'loop' => 'true',
            'class' => 'my-.class',
            'title' => 'My Video',
            'allow' => 'autoplay',
            'referrerpolicy' => 'no-referrer',
            'aspect-ratio' => '4/3',
            '_' => [],
        ]);
        $expected = '<iframe src="https://player.vimeo.com/video/123456789?autoplay=1&loop=1#t=42s" width="1024" height="768" title="My Video" frameborder="0" allowfullscreen class="my-.class" allow="autoplay" referrerpolicy="no-referrer"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testVimeoUrl()
    {
        $result = $this->vimeo->process('https://vimeo.com/123456789', ['_' => []]);
        $this->assertStringContainsString('player.vimeo.com/video/123456789', $result);
    }

    public function testPlayerVimeoUrl()
    {
        $result = $this->vimeo->process('https://player.vimeo.com/video/123456789', ['_' => []]);
        $this->assertStringContainsString('player.vimeo.com/video/123456789', $result);
    }
}
