<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcoder\ShortcodeProcessor;
use JoomlaShortcoder\Plugin\Content\Shortcoder\Exception\ShortcodeProcessingException;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Repeat;

class RepeatTest extends TestCase
{
    private static ShortcodeProcessor $processor;

    public static function setUpBeforeClass(): void
    {
        self::$processor = new ShortcodeProcessor([
            'repeat'     => new Repeat(),
        ]);
    }

    private function processShortcodes(string $text): string
    {
        return self::$processor->processShortcodes($text, new \stdClass());
    }

    public function testDefaultRepeatShortcode(): void
    {
        $text = '{repeat}test{/repeat}';
        $result = $this->processShortcodes($text);
        $this->assertEquals('test', $result);
    }

    public function testRepeatShortcodeWithNumber(): void
    {
        $text = '{repeat 3}test{/repeat}';
        $result = $this->processShortcodes($text);
        $this->assertEquals('testtesttest', $result);
    }

    public function testRepeatShortcodeWithRange(): void
    {
        $text = '{repeat 2,4}test{/repeat}';
        $result = $this->processShortcodes($text);
        $count = substr_count($result, 'test');
        $this->assertGreaterThanOrEqual(2, $count);
        $this->assertLessThanOrEqual(4, $count);
    }

    public function testRepeatShortcodeWithInvalidRange(): void
    {
        $text = '{repeat 4,2}test{/repeat}';
        $result = $this->processShortcodes($text);
        $this->assertEquals('testtesttesttest', $result);
    }

    public function testRepeatShortcodeWithZero(): void
    {
        $result = $this->processShortcodes('{repeat 0}test{/repeat}');
        $this->assertStringContainsString('<div class="shortcode-error"', $result);
        $this->assertStringContainsString('<b>Repeat</b>: Number of repeats must be a positive integer.', $result);
    }

    public function testRepeatWithNestedContent(): void
    {
        $text = '{repeat 3}<p>test content</p>{/repeat}';
        $result = $this->processShortcodes($text);

        $this->assertEquals(3, substr_count($result, '<p>'));
        $this->assertEquals(3, substr_count($result, '</p>'));
        $this->assertEquals(3, substr_count($result, 'test content'));
    }

    public function testRepeatWithRangeAndNestedContent(): void
    {
        $text = '{repeat 2,5}<p>range content</p>{/repeat}';
        $result = $this->processShortcodes($text);

        $count = substr_count($result, '<p>');
        $this->assertGreaterThanOrEqual(2, $count);
        $this->assertLessThanOrEqual(5, $count);
        $this->assertEquals($count, substr_count($result, '</p>'));
        $this->assertEquals($count, substr_count($result, 'range content'));
    }
}
