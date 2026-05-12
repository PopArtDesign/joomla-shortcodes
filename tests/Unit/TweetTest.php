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

    public function testInvokeWithInvalidUrlReturnsError(): void
    {
        $result = ($this->tweet)(['url' => 'https://example.com'], '');
        $this->assertStringContainsString('Tweet: The provided URL is not a valid Twitter/X post URL.', $result);
    }

    public function testInvokeWithEmptyUrlReturnsError(): void
    {
        $result = ($this->tweet)(['url' => ''], '');
        $this->assertStringContainsString('Tweet: A valid URL was not found.', $result);
    }

    public function testInvokeWithMissingUrlAttributeReturnsError(): void
    {
        $result = ($this->tweet)(['foo' => 'bar'], '');
        $this->assertStringContainsString('Tweet: A valid URL was not found.', $result);
    }
}
