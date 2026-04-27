<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcoder\ShortcodeProcessor;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Lorem;

class LoremTest extends TestCase
{
    private static ShortcodeProcessor $processor;

    public static function setUpBeforeClass(): void
    {
        self::$processor = new ShortcodeProcessor([
            'lorem' => new Lorem(),
        ]);
    }

    private function processShortcodes(string $text): string
    {
        return self::$processor->processShortcodes($text, new \stdClass());
    }

    public function testDefaultLoremIpsumShortcode(): void
    {
        $text = '{lorem}';
        $result = $this->processShortcodes($text);

        $this->assertEquals(84, str_word_count($result));
    }

    public function testLoremIpsumWithMoreWordsThanInSource(): void
    {
        $text = '{lorem words="150"}';
        $result = $this->processShortcodes($text);

        $this->assertEquals(150, str_word_count($result));
    }

    public function testLoremIpsumWithWordsAttribute(): void
    {
        $text = '{lorem words="5"}';
        $result = $this->processShortcodes($text);

        $this->assertEquals(5, str_word_count($result));
    }

    public function testLoremIpsumWithWordsRangeAttribute(): void
    {
        $text = '{lorem words="5,10"}';
        $result = $this->processShortcodes($text);

        $wordCount = str_word_count($result);
        $this->assertGreaterThanOrEqual(5, $wordCount);
        $this->assertLessThanOrEqual(10, $wordCount);
    }

    public function testLoremIpsumWithInvalidWordsRange(): void
    {
        $text = '{lorem words="10,5"}';
        $result = $this->processShortcodes($text);

        $wordCount = str_word_count($result);
        $this->assertEquals(10, $wordCount);
    }
}
