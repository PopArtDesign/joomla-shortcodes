<?php

declare(strict_types=1);

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Tweet;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Tweet::class)]
final class TweetTest extends TestCase
{
    private Tweet $tweet;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tweet = new Tweet();
    }

    public function testInvokeWithValidTwitterUrl(): void
    {
        $url = 'https://twitter.com/user/status/12345';
        $result = ($this->tweet)(['url' => $url], '');

        $this->assertStringContainsString('<div class="embed-container embed-tweet">', $result);
        $this->assertStringContainsString('<blockquote class="twitter-tweet">', $result);
        $this->assertStringContainsString('href="https://twitter.com/user/status/12345"', $result);
        $this->assertStringContainsString('<script', $result);
        $this->assertStringContainsString('src="https://platform.twitter.com/widgets.js"', $result);
        $this->assertStringContainsString('async', $result);
    }

    public function testInvokeWithValidXUrl(): void
    {
        $url = 'https://x.com/user/status/67890';
        $result = ($this->tweet)(['url' => $url], '');

        $this->assertStringContainsString('<div class="embed-container embed-tweet">', $result);
        $this->assertStringContainsString('<blockquote class="twitter-tweet">', $result);
        $this->assertStringContainsString('href="https://x.com/user/status/67890"', $result);
    }

    public function testInvokeWithUrlInContent(): void
    {
        $url = 'https://twitter.com/user/status/12345';
        $result = ($this->tweet)([], $url);

        $this->assertStringContainsString('<div class="embed-container embed-tweet">', $result);
        $this->assertStringContainsString('<blockquote class="twitter-tweet">', $result);
        $this->assertStringContainsString('href="https://twitter.com/user/status/12345"', $result);
    }

    public function testInvokeWithInvalidUrlThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The provided URL is not a valid Twitter/X post URL.');
        ($this->tweet)(['url' => 'https://example.com'], '');
    }

    public function testInvokeWithEmptyUrlThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid URL provided in "url" attribute: ');
        ($this->tweet)(['url' => ''], '');
    }

    public function testInvokeWithMissingUrlAttributeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('A valid embed URL was not found.');
        ($this->tweet)(['foo' => 'bar'], '');
    }
}
