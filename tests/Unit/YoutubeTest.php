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
        $result = $this->youtube->process('kBddBRQ-xic', []);
        $expected = '
<div class="youtube-container">
    <iframe
        src="https://www.youtube.com/embed/kBddBRQ-xic?start=0"
        width="560"
        height="315"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        title="YouTube video player"
        referrerpolicy="strict-origin-when-cross-origin"
        frameborder="0"
        allowfullscreen>
    </iframe>
</div>';
        $this->assertEquals(trim($expected), trim($result));
    }

    public function testCustomDimensions()
    {
        $result = $this->youtube->process('kBddBRQ-xic', ['width' => '800', 'height' => '600']);
        $expected = '
<div class="youtube-container">
    <iframe
        src="https://www.youtube.com/embed/kBddBRQ-xic?start=0"
        width="800"
        height="600"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        title="YouTube video player"
        referrerpolicy="strict-origin-when-cross-origin"
        frameborder="0"
        allowfullscreen>
    </iframe>
</div>';
        $this->assertEquals(trim($expected), trim($result));
    }

    public function testStartTimeInSeconds()
    {
        $result = $this->youtube->process('kBddBRQ-xic', ['start' => '90']);
        $expected = '
<div class="youtube-container">
    <iframe
        src="https://www.youtube.com/embed/kBddBRQ-xic?start=90"
        width="560"
        height="315"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        title="YouTube video player"
        referrerpolicy="strict-origin-when-cross-origin"
        frameborder="0"
        allowfullscreen>
    </iframe>
</div>';
        $this->assertEquals(trim($expected), trim($result));
    }

    public function testStartTimeInMmSs()
    {
        $result = $this->youtube->process('kBddBRQ-xic', ['start' => '1:30']);
        $expected = '
<div class="youtube-container">
    <iframe
        src="https://www.youtube.com/embed/kBddBRQ-xic?start=90"
        width="560"
        height="315"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        title="YouTube video player"
        referrerpolicy="strict-origin-when-cross-origin"
        frameborder="0"
        allowfullscreen>
    </iframe>
</div>';
        $this->assertEquals(trim($expected), trim($result));
    }

    public function testAllAttributes()
    {
        $result = $this->youtube->process('kBddBRQ-xic', [
            'width' => '1024',
            'height' => '768',
            'start' => '0:42',
            'class' => 'my-class',
            'title' => 'My Video',
            'allow' => 'autoplay'
        ]);
        $expected = '
<div class="my-class">
    <iframe
        src="https://www.youtube.com/embed/kBddBRQ-xic?start=42"
        width="1024"
        height="768"
        allow="autoplay"
        title="My Video"
        referrerpolicy="strict-origin-when-cross-origin"
        frameborder="0"
        allowfullscreen>
    </iframe>
</div>';
        $this->assertEquals(trim($expected), trim($result));
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
