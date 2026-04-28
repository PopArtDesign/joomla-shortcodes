<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

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
        $result = $this->youtube->process('', []);
        $this->assertEquals('', $result);
    }

    public function testBasicUsage()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', []);
        $expected = '<iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=0" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen class="youtube-container" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: 16 / 9;"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testCustomDimensions()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['width' => '800', 'height' => '600']);
        $expected = '<iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=0" width="800" height="600" title="YouTube video player" frameborder="0" allowfullscreen class="youtube-container" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testStartTimeInSeconds()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['start' => '90']);
        $expected = '<iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=90" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen class="youtube-container" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: 16 / 9;"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testStartTimeInMmSs()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['start' => '1:30']);
        $expected = '<iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=90" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen class="youtube-container" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: 16 / 9;"></iframe>';
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
            'allow' => 'autoplay'
        ]);
        $expected = '<iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=42" width="1024" height="768" title="My Video" frameborder="0" allowfullscreen class="my-class" allow="autoplay" referrerpolicy="strict-origin-when-cross-origin"></iframe>';
        $this->assertEquals($expected, $result);
    }

    public function testYoutubeUrlWatchV()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', []);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
        $this->assertStringContainsString('youtube.com/embed', $result);
    }

    public function testYoutubeUrlYoutuBe()
    {
        $result = $this->youtube->process('https://youtu.be/kBddBRQ-xic', []);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
        $this->assertStringContainsString('youtube.com/embed', $result);
    }

    public function testYoutubeUrlEmbed()
    {
        $result = $this->youtube->process('https://www.youtube.com/embed/kBddBRQ-xic', []);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
        $this->assertStringContainsString('youtube.com/embed', $result);
    }

    public function testYoutubeUrlWithOtherParams()
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic&t=10s', []);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
    }

    public function testYoutubeUrlWithoutScheme()
    {
        $result = $this->youtube->process('www.youtube.com/watch?v=kBddBRQ-xic', []);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
    }
}
