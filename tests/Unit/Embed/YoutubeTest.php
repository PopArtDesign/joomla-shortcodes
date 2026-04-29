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

    public function testNoUrl(): void
    {
        $result = $this->youtube->process('', ['_' => []]);
        $this->assertEquals('', $result);
    }

    public function testBasicUsage(): void
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['_' => []]);
        $expected = '<div class="embed-container embed-video embed-youtube" style="--embed-aspect-ratio: 16 / 9"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testCustomDimensions(): void
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['width' => '800', 'height' => '600', '_' => []]);
        $expected = '<div class="embed-container embed-video embed-youtube"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic" width="800" height="600" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testStartTimeInSeconds(): void
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['start' => '90', '_' => []]);
        $expected = '<div class="embed-container embed-video embed-youtube" style="--embed-aspect-ratio: 16 / 9"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=90" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testStartTimeInMmSs(): void
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['start' => '1:30', '_' => []]);
        $expected = '<div class="embed-container embed-video embed-youtube" style="--embed-aspect-ratio: 16 / 9"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=90" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testAutoplay(): void
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['autoplay' => 'true', '_' => []]);
        $expected = '<div class="embed-container embed-video embed-youtube" style="--embed-aspect-ratio: 16 / 9"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?autoplay=1&mute=1" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testAllAttributes(): void
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
        $expected = '<div class="embed-container embed-video embed-youtube my-class"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=42&autoplay=1&mute=1" width="1024" height="768" title="My Video" frameborder="0" allowfullscreen allow="autoplay" referrerpolicy="strict-origin-when-cross-origin"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testYoutubeUrlWatchV(): void
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['_' => []]);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('<div class="embed-container embed-video embed-youtube"', $result);
        $this->assertEquals(1, substr_count($result, 'class="embed-container embed-video embed-youtube"'));
    }

    public function testYoutubeUrlYoutuBe(): void
    {
        $result = $this->youtube->process('https://youtu.be/kBddBRQ-xic', ['_' => []]);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('<div class="embed-container embed-video embed-youtube"', $result);
        $this->assertEquals(1, substr_count($result, 'class="embed-container embed-video embed-youtube"'));
    }

    public function testYoutubeUrlEmbed(): void
    {
        $result = $this->youtube->process('https://www.youtube.com/embed/kBddBRQ-xic', ['_' => []]);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('<div class="embed-container embed-video embed-youtube"', $result);
        $this->assertEquals(1, substr_count($result, 'class="embed-container embed-video embed-youtube"'));
    }

    public function testYoutubeUrlWithOtherParams(): void
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic&t=10s', ['_' => []]);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
        $this->assertStringContainsString('<div class="embed-container embed-video embed-youtube"', $result);
        $this->assertEquals(1, substr_count($result, 'class="embed-container embed-video embed-youtube"'));
    }

    public function testYoutubeUrlWithoutScheme(): void
    {
        $result = $this->youtube->process('www.youtube.com/watch?v=kBddBRQ-xic', ['_' => []]);
        $this->assertStringContainsString('youtube.com/embed', $result);
        $this->assertStringContainsString('kBddBRQ-xic', $result);
        $this->assertStringContainsString('<div class="embed-container embed-video embed-youtube"', $result);
        $this->assertEquals(1, substr_count($result, 'class="embed-container embed-video embed-youtube"'));
    }

    public function testEndTimeInSeconds(): void
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['end' => '120', '_' => []]);
        $expected = '<div class="embed-container embed-video embed-youtube" style="--embed-aspect-ratio: 16 / 9"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?end=120" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testEndTimeInMmSs(): void
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['end' => '2:30', '_' => []]);
        $expected = '<div class="embed-container embed-video embed-youtube" style="--embed-aspect-ratio: 16 / 9"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?end=150" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testStartAndEndTimeCombined(): void
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['start' => '30', 'end' => '90', '_' => []]);
        $expected = '<div class="embed-container embed-video embed-youtube" style="--embed-aspect-ratio: 16 / 9"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=30&end=90" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testStartTimeMmSsAndEndTimeSeconds(): void
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['start' => '1:15', 'end' => '120', '_' => []]);
        $expected = '<div class="embed-container embed-video embed-youtube" style="--embed-aspect-ratio: 16 / 9"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?start=75&end=120" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe></div>';
        $this->assertEquals($expected, $result);
    }

    public function testEndTimeWithAutoplay(): void
    {
        $result = $this->youtube->process('https://www.youtube.com/watch?v=kBddBRQ-xic', ['end' => '60', 'autoplay' => 'true', '_' => []]);
        $expected = '<div class="embed-container embed-video embed-youtube" style="--embed-aspect-ratio: 16 / 9"><iframe src="https://www.youtube.com/embed/kBddBRQ-xic?end=60&autoplay=1&mute=1" width="100%" height="auto" title="YouTube video player" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="aspect-ratio: var(--embed-aspect-ratio);"></iframe></div>';
        $this->assertEquals($expected, $result);
    }
}
