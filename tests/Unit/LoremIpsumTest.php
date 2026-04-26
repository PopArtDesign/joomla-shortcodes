<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Test\Unit;

use PHPUnit\Framework\TestCase;
use JoomlaShortcoder\Plugin\Content\Shortcoder\ShortcodeProcessor;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Shortcode\LoremIpsum;

class LoremIpsumTest extends TestCase
{
    private static ShortcodeProcessor $processor;

    public static function setUpBeforeClass(): void
    {
        self::$processor = new ShortcodeProcessor([
            'loremipsum' => new LoremIpsum(),
        ]);
    }

    private function processShortcodes(string $text): string
    {
        return self::$processor->processShortcodes($text, new \stdClass());
    }

    public function testDefaultLoremIpsumShortcode(): void
    {
        $text = '{loremipsum}';
        $result = $this->processShortcodes($text);

        $this->assertStringNotContainsString('<p>', $result);
        $this->assertEquals(84, str_word_count($result));
    }

    public function testLoremIpsumWithWordsAttribute(): void
    {
        $text = '{loremipsum words="5"}';
        $result = $this->processShortcodes($text);

        $this->assertStringNotContainsString('<p>', $result);
        $this->assertEquals(5, str_word_count($result));
    }

    public function testLoremIpsumWithWordsRangeAttribute(): void
    {
        $text = '{loremipsum words="5,10"}';
        $result = $this->processShortcodes($text);

        $this->assertStringNotContainsString('<p>', $result);
        $wordCount = str_word_count($result);
        $this->assertGreaterThanOrEqual(5, $wordCount);
        $this->assertLessThanOrEqual(10, $wordCount);
    }

    public function testLoremIpsumWithInvalidWordsRange(): void
    {
        $text = '{loremipsum words="10,5"}';
        $result = $this->processShortcodes($text);

        $this->assertStringNotContainsString('<p>', $result);
        $wordCount = str_word_count($result);
        $this->assertEquals(10, $wordCount);
    }
}
