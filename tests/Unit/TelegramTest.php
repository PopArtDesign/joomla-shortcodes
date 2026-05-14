<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Telegram;
use PHPUnit\Framework\TestCase;

class TelegramTest extends TestCase
{
    private Telegram $handler;

    protected function setUp(): void
    {
        $this->handler = new Telegram();
    }

    public function testInvoke(): void
    {
        $url = 'https://t.me/durov/89';
        $attributes = ['url' => $url];
        $content = '';

        $expected = '<div class="embed-container embed-telegram"><script src="https://telegram.org/js/telegram-widget.js?23" async data-telegram-post="durov/89" data-width="100%"></script></div>';
        $this->assertEquals($expected, ($this->handler)($attributes, $content));
    }

    public function testInvokeWithContent(): void
    {
        $url = 'https://t.me/durov/89';
        $attributes = [];
        $content = $url;

        $expected = '<div class="embed-container embed-telegram"><script src="https://telegram.org/js/telegram-widget.js?23" async data-telegram-post="durov/89" data-width="100%"></script></div>';
        $this->assertEquals($expected, ($this->handler)($attributes, $content));
    }

    public function testInvokeWithInvalidUrl(): void
    {
        $attributes = ['url' => 'https://example.com'];
        $content = '';

        $expected = 'Telegram: The provided URL is not a valid Telegram post URL.';
        $this->assertStringContainsString($expected, ($this->handler)($attributes, $content));
    }

    public function testInvokeWithoutUrl(): void
    {
        $attributes = [];
        $content = '';

        $expected = 'Telegram: A valid URL was not found.';
        $this->assertStringContainsString($expected, ($this->handler)($attributes, $content));
    }
}
