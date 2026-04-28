<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit\Embed;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Youtube;

class YoutubeTest extends TestCase
{
    private Youtube $youtube;

    protected function setUp(): void
    {
        $this->youtube = new Youtube();
    }

    public function testNoUrl()
    {
        $result = $this->youtube->process('', ['_' => []]);
        $this->assertEquals('', $result);
    }

    public function testBasicUsage()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['_' => []]);
        $expected = '<div class="youtube-container"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=0" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: 16 / 9;"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testCustomDimensions()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['width' => '800', 'height' => '600', '_' => []]);
        $expected = '<div class="youtube-container"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=0" width="800" height="600" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testStartTimeInSeconds()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['start' => '90', '_' => []]);
        $expected = '<div class="youtube-container"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=90" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: 16 / 9;"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testStartTimeInMmSs()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['start' => '1:30', '_' => []]);
        $expected = '<div class="youtube-container"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=90" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: 16 / 9;"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testAutoplay()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['autoplay' => 'true', '_' => []]);
        $expected = '<div class="youtube-container"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=0&autoplay=1&mute=1" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: 16 / 9;"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testAllAttributes()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', [
            'width' => '1024',
            'height' => '768',
            'start' => '0:42',
            'class' => 'my-class',
            'title' => 'My Video',
            'allow' => 'autoplay',
            'autoplay' => 'true',
            '_' => [],
        ]);
        $expected = '<div class="my-class"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=42&autoplay=1&mute=1" width="1024" height="768" title="My Video" frameborder="0" allowfullscreen allow="autoplay" referrerpolicy="strict-origin-when-cross-origin"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testYoutubeUrlWatchV()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['_' => []]);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('<div class="youtube-container">', $result);
        $this->assertEquals(1, substr_count($result, 'class="youtube-container"'));
    }

    public function testYoutubeUrlYoutuBe()
    {
        $result = $this->youtube->process('https://youtu.be/kBddBRQ-xic', ['_' => []]);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('<div class="youtube-container">', $result);
        $this->assertEquals(1, substr_count($result, 'class="youtube-container"'));
    }

    public function testYoutubeUrlEmbed()
    {
        $result = $this->youtube->process('https://www.youtube.com/embed/kBddBRQ-xic', ['_' => []]);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('<div class="youtube-container">', $result);
        $this->assertEquals(1, substr_count($result, 'class="youtube-container"'));
    }

    public function testYoutubeUrlWithOtherParams()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic&t=10s', ['_' => []]);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
        $this->assertStringContainsString('<div class="youtube-container">', $result);
        $this->assertEquals(1, substr_count($result, 'class="youtube-container"'));
    }

    public function testYoutubeUrlWithoutScheme()
    {
        $result = $this->youtube->process('www.youtube.com/watch?v=kBddBRQ-xic', ['_' => []]);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
        $this->assertStringContainsString('<div class="youtube-container">', $result);
        $this->assertEquals(1, substr_count($result, 'class="youtube-container"'));
    }
}
