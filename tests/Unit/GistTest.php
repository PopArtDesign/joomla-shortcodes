<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Gist;

\defined('_JEXEC') or die;

class GistTest extends TestCase
{
    public function testGistUrlInContentIsProcessed(): void
    {
        $shortcode = new Gist();
        $result = $shortcode([], 'https://gist.github.com/testuser/12345');
        $this->assertStringContainsString('gist.github.com/testuser/12345.js', $result);
    }

    public function testGistUrlAsAttributeIsProcessed(): void
    {
        $shortcode = new Gist();
        $result = $shortcode(['url' => 'https://gist.github.com/testuser/12345'], '');
        $this->assertStringContainsString('gist.github.com/testuser/12345.js', $result);
    }

    public function testNonGistUrlIsNotProcessed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The provided URL is not a valid Gist URL.');
        $shortcode = new Gist();
        $shortcode(['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'], '');
    }

    public function testGistUrlWithFile(): void
    {
        $shortcode = new Gist();
        $result = $shortcode(['url' => 'https://gist.github.com/testuser/12345', 'file' => 'test.php'], '');
        $this->assertStringContainsString('gist.github.com/testuser/12345.js?file=test.php', $result);
    }
}
