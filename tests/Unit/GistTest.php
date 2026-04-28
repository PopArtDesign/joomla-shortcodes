<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Gist;

class GistTest extends TestCase
{
    private Gist $gist;

    protected function setUp(): void
    {
        $this->gist = new Gist();
    }

    public function testNoUrl()
    {
        $result = $this->gist->process('', []);
        $this->assertEquals('', $result);
    }

    public function testBasicUsage()
    {
        $result = $this->gist->process('https://gist.github.com/testuser/12345', []);
        $expected = '<script src="https://gist.github.com/testuser/12345.js"></script>';
        $this->assertEquals($expected, trim($result));
    }

    public function testUsageWithFile()
    {
        $result = $this->gist->process(
            'https://gist.github.com/testuser/12345',
            ['file' => 'test.php']
        );
        $expected = '<script src="https://gist.github.com/testuser/12345.js?file=test.php"></script>';
        $this->assertEquals($expected, trim($result));
    }

    public function testInvalidUrl()
    {
        $result = $this->gist->process('https://example.com/testuser/12345', []);
        $this->assertEquals('', $result);
    }

    public function testShortSyntax()
    {
        // Short syntax (user/hash) is no longer supported - requires full URL
        $result = $this->gist->process('voronkovich/0ee5c78d7b1a61c7e8f3cd6eedd2e3dc', []);
        $this->assertEquals('', $result);
    }

    public function testShortSyntaxWithFile()
    {
        // Short syntax (user/hash) with file is no longer supported
        $result = $this->gist->process(
            'voronkovich/0ee5c78d7b1a61c7e8f3cd6eedd2e3dc',
            ['file' => 'test.php']
        );
        $this->assertEquals('', $result);
    }
}
