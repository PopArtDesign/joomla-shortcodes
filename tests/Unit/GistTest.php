<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use JoomlaShortcoder\Plugin\Content\Shortcoder\ShortcodeProcessor;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Gist;
use PHPUnit\Framework\TestCase;

class GistTest extends TestCase
{
    private ShortcodeProcessor $processor;

    protected function setUp(): void
    {
        $this->processor = new ShortcodeProcessor([
            'gist' => new Gist(),
        ]);
    }

    public function testNoUrl()
    {
        $content = $this->processor->processShortcodes('{gist}', new \stdClass());
        $this->assertEquals('', $content);
    }

    public function testBasicUsage()
    {
        $content = $this->processor->processShortcodes('{gist https://gist.github.com/testuser/12345}', new \stdClass());
        $expected = '<script src="https://gist.github.com/testuser/12345.js"></script>';
        $this->assertEquals($expected, trim($content));
    }

    public function testUsageWithFile()
    {
        $content = $this->processor->processShortcodes('{gist https://gist.github.com/testuser/12345 file="test.php"}', new \stdClass());
        $expected = '<script src="https://gist.github.com/testuser/12345.js?file=test.php"></script>';
        $this->assertEquals($expected, trim($content));
    }

    public function testInvalidUrl()
    {
        $content = $this->processor->processShortcodes('{gist https://example.com/testuser/12345}', new \stdClass());
        $this->assertEquals('', $content);
    }

    public function testShortSyntax()
    {
        $content = $this->processor->processShortcodes('{gist voronkovich/0ee5c78d7b1a61c7e8f3cd6eedd2e3dc}', new \stdClass());
        $expected = '<script src="https://gist.github.com/voronkovich/0ee5c78d7b1a61c7e8f3cd6eedd2e3dc.js"></script>';
        $this->assertEquals($expected, trim($content));
    }

    public function testShortSyntaxWithFile()
    {
        $content = $this->processor->processShortcodes('{gist voronkovich/0ee5c78d7b1a61c7e8f3cd6eedd2e3dc file="test.php"}', new \stdClass());
        $expected = '<script src="https://gist.github.com/voronkovich/0ee5c78d7b1a61c7e8f3cd6eedd2e3dc.js?file=test.php"></script>';
        $this->assertEquals($expected, trim($content));
    }
}
