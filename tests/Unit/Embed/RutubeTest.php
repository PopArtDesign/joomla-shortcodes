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

    public function testNoUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not extract Rutube video ID from URL: ');
        $this->rutube->process('', []);
    }

    public function testBasicUsage(): void
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', []);
        $expected = '<iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c" width="100%" height="auto" title="Rutube video player" frameborder="0" allowfullscreen allow="clipboard-write; autoplay" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testPlaylistUrl(): void
    {
        $result = $this->rutube->process('https://rutube.ru/pl/THEBEST/a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6/', []);
        $expected = '<iframe src="https://rutube.ru/play/embed/a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6" width="100%" height="auto" title="Rutube video player" frameborder="0" allowfullscreen allow="clipboard-write; autoplay" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testCustomDimensions(): void
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', ['width' => '800', 'height' => '600']);
        $expected = '<iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c" width="800" height="600" title="Rutube video player" frameborder="0" allowfullscreen allow="clipboard-write; autoplay" referrerpolicy="strict-origin-when-cross-origin"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testAutoplay(): void
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', ['autoplay' => 'true']);
        $expected = '<iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c?autoplay=true&amp;autostartmute=true" width="100%" height="auto" title="Rutube video player" frameborder="0" allowfullscreen allow="clipboard-write; autoplay" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testAspectRatioDefault(): void
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', ['height' => 'auto']);
        $expected = '<iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c" width="100%" height="auto" title="Rutube video player" frameborder="0" allowfullscreen allow="clipboard-write; autoplay" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testAspectRatioCustom(): void
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', ['height' => 'auto', 'aspect-ratio' => '4 / 3']);
        $expected = '<iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c" width="100%" height="auto" title="Rutube video player" frameborder="0" allowfullscreen allow="clipboard-write; autoplay" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testStartTimeInSeconds(): void
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', ['start' => '300']);
        $expected = '<iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c?t=300" width="100%" height="auto" title="Rutube video player" frameborder="0" allowfullscreen allow="clipboard-write; autoplay" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testStartTimeInMmSs(): void
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', ['start' => '5:00']);
        $expected = '<iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c?t=300" width="100%" height="auto" title="Rutube video player" frameborder="0" allowfullscreen allow="clipboard-write; autoplay" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testEndTime(): void
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', ['end' => '480']);
        $expected = '<iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c?stopTime=480" width="100%" height="auto" title="Rutube video player" frameborder="0" allowfullscreen allow="clipboard-write; autoplay" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testStartAndEndTime(): void
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', ['start' => '300', 'end' => '480']);
        $expected = '<iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c?t=300&amp;stopTime=480" width="100%" height="auto" title="Rutube video player" frameborder="0" allowfullscreen allow="clipboard-write; autoplay" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testAllAttributes(): void
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', [
            'width' => '1024',
            'height' => '768',
            'class' => 'my-class', // This class is for the wrapper now
            'allow' => 'autoplay',
        ]);
        $expected = '<iframe src="https://rutube.ru/play/embed/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c" width="1024" height="768" title="Rutube video player" frameborder="0" allowfullscreen allow="autoplay" referrerpolicy="strict-origin-when-cross-origin"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testRutubeUrlVideo(): void
    {
        $result = $this->rutube->process('https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', []);
        $this->assertStringContainsString('0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c', $result);
        $this->assertStringContainsString('rutube.ru/play/embed', $result);
    }

    public function testRutubeUrlWithoutScheme(): void
    {
        $result = $this->rutube->process('rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/', []);
        $this->assertStringContainsString('rutube.ru/play/embed', $result);
        $this->assertStringContainsString('0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c', $result);
    }
}
