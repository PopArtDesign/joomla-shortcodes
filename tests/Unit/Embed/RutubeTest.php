<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit\Embed;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Rutube;

class RutubeTest extends TestCase
{
    private Rutube $rutube;

    protected function setUp(): void
    {
        $this->rutube = new Rutube();
    }

    public function testNoUrl()
    {
        $result = $this->rutube->process('', []);
        $this->assertEquals('', $result);
    }

    public function testBasicUsage()
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', []);
        $expected = '<div class="rutube-container"><iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c" width="720" height="405" frameborder="0" allow="clipboard-write; autoplay" allowfullscreen></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testPlaylistUrl()
    {
        $result = $this->rutube->process('https://rutube.ru/pl/THEBEST/a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6/', []);
        $expected = '<div class="rutube-container"><iframe src="https://rutube.ru/play/embed/a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6" width="720" height="405" frameborder="0" allow="clipboard-write; autoplay" allowfullscreen></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testCustomDimensions()
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', ['width' => '800', 'height' => '600']);
        $expected = '<div class="rutube-container"><iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c" width="800" height="600" frameborder="0" allow="clipboard-write; autoplay" allowfullscreen></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testAutoplay()
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', ['autoplay' => 'true']);
        $expected = '<div class="rutube-container"><iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c?autoplay=1" width="720" height="405" frameborder="0" allow="clipboard-write; autoplay" allowfullscreen></iframe></div>';
        $this->assertEquals($expected, $result);
    }


    public function testAllAttributes()
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', [
            'width' => '1024',
            'height' => '768',
            'class' => 'my-class',
            'allow' => 'autoplay',
        ]);
        $expected = '<div class="my-class"><iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c" width="1024" height="768" frameborder="0" allow="autoplay" allowfullscreen></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testRutubeUrlVideo()
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', []);
        $this->assertStringContainsString('0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c', $result);
        $this->assertStringContainsString('rutube.ru/play/embed', $result);
        $this->assertStringContainsString('<div class="rutube-container">', $result);
        $this->assertEquals(1, substr_count($result, 'class="rutube-container"'));
    }

    public function testRutubeUrlWithoutScheme()
    {
        $result = $this->rutube->process('rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', []);
        $this->assertStringContainsString('rutube.ru/play/embed', $result);
        $this->assertStringContainsString('0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c', $result);
        $this->assertStringContainsString('<div class="rutube-container">', $result);
        $this->assertEquals(1, substr_count($result, 'class="rutube-container"'));
    }
}
