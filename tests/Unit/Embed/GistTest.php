<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit\Embed;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Gist;

class GistTest extends TestCase
{
    private Gist $gist;

    protected function setUp(): void
    {
        $this->gist = new Gist();
    }

    public function testInvalidUrl(): void
    {
        $result = $this->gist->supports('https://example.com/testuser/12345');
        $this->assertFalse($result);
    }

    public function testBasicUsage(): void
    {
        $result = $this->gist->process('https://gist.github.com/testuser/12345', []);
        $expected = '<script src="https://gist.github.com/testuser/12345.js"></script>';
        $this->assertEquals($expected, trim($result));
    }

    public function testUsageWithFile(): void
    {
        $result = $this->gist->process(
            'https://gist.github.com/testuser/12345',
            ['file' => 'test.php']
        );
        $expected = '<script src="https://gist.github.com/testuser/12345.js?file=test.php"></script>';
        $this->assertEquals($expected, trim($result));
    }
}
