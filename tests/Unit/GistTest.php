<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Gist;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Value\ParsedUrl;

\defined('_JEXEC') or die;

class GistTest extends TestCase
{
    public function testGistUrlInContentIsProcessed(): void
    {
        $shortcode = new Gist();
        $result = $shortcode([], 'https://gist.github.com/testuser/0123456789abcdef0123456789abcdef');
        $this->assertStringContainsString('gist.github.com/testuser/0123456789abcdef0123456789abcdef.js', $result);
    }

    public function testGistUrlAsAttributeIsProcessed(): void
    {
        $shortcode = new Gist();
        $result = $shortcode(['url' => 'https://gist.github.com/testuser/0123456789abcdef0123456789abcdef'], '');
        $this->assertStringContainsString('gist.github.com/testuser/0123456789abcdef0123456789abcdef.js', $result);
    }

    public function testNonGistUrlIsNotProcessed(): void
    {
        $shortcode = new Gist();
        $result = $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'], '');
        $this->assertStringContainsString('Gist: The provided URL is not a valid Gist URL.', $result);
    }

    public function testGistUrlWithFile(): void
    {
        $shortcode = new Gist();
        $result = $shortcode(['url' => 'https://gist.github.com/testuser/0123456789abcdef0123456789abcdef', 'file' => 'test.php'], '');
        $this->assertStringContainsString('gist.github.com/testuser/0123456789abcdef0123456789abcdef.js?file=test.php', $result);
    }

    /**
     * @dataProvider invalidGistUrlProvider
     */
    public function testGistUrlWithInvalidPathReturnsError(string $url): void
    {
        $shortcode = new Gist();
        $result = $shortcode(['url' => $url], '');
        $this->assertStringContainsString('Gist: The provided Gist URL path is invalid. Expected format: username/gistid.', $result);
    }

    public static function invalidGistUrlProvider(): array
    {
        return [
            'root path' => ['https://gist.github.com/'],
            'username only' => ['https://gist.github.com/testuser'],
            'extra path segments' => ['https://gist.github.com/testuser/0123456789abcdef0123456789abcdef/extra'],
            'non-hex gist id' => ['https://gist.github.com/testuser/12345z'],
        ];
    }
}
