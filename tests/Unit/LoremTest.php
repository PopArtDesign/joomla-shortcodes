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

    public function testWrapAttributeWithSingleP(): void
    {
        $text = '{lorem wrap="p"}';
        $result = $this->processShortcodes($text);

        $this->assertStringStartsWith('<p>', $result);
        $this->assertStringEndsWith('</p>', $result);
        $this->assertEquals(1, substr_count($result, '<p>'));
        $this->assertGreaterThan(0, str_word_count(strip_tags($result)));
    }

    public function testWrapAttributeWithMultipleP(): void
    {
        $text = '{lorem wrap="p,3"}';
        $result = $this->processShortcodes($text);

        $this->assertEquals(3, substr_count($result, '<p>'));
        $this->assertGreaterThan(0, str_word_count(strip_tags($result)));
    }

    public function testWrapAttributeWithUnorderedList(): void
    {
        $text = '{lorem wrap="ul,5"}';
        $result = $this->processShortcodes($text);

        $this->assertStringStartsWith('<ul>', $result);
        $this->assertStringEndsWith('</ul>', $result);
        $this->assertEquals(1, substr_count($result, '<ul>'));
        $this->assertEquals(5, substr_count($result, '<li>'));
        $this->assertGreaterThan(0, str_word_count(strip_tags($result)));
    }

    public function testWrapAttributeWithOrderedList(): void
    {
        $text = '{lorem wrap="ol,3"}';
        $result = $this->processShortcodes($text);

        $this->assertStringStartsWith('<ol>', $result);
        $this->assertStringEndsWith('</ol>', $result);
        $this->assertEquals(1, substr_count($result, '<ol>'));
        $this->assertEquals(3, substr_count($result, '<li>'));
        $this->assertGreaterThan(0, str_word_count(strip_tags($result)));
    }

    public function testWrapAttributeWithWords(): void
    {
        $text = '{lorem wrap="div,2" words="10"}';
        $result = $this->processShortcodes($text);

        $this->assertEquals(2, substr_count($result, '<div>'));
        // Extract content between <div> tags and check word count for each
        \preg_match_all('/<div>(.*?)<\/div>/s', $result, $matches);
        $this->assertCount(2, $matches[1]);
        foreach ($matches[1] as $content) {
            $this->assertEquals(10, str_word_count(strip_tags($content)));
        }
    }

    public function testWrapAttributeWithWordsRange(): void
    {
        $text = '{lorem wrap="ul,4" words="5,10"}';
        $result = $this->processShortcodes($text);

        $this->assertStringStartsWith('<ul>', $result);
        $this->assertStringEndsWith('</ul>', $result);
        $this->assertEquals(1, substr_count($result, '<ul>'));
        $this->assertEquals(4, substr_count($result, '<li>'));

        \preg_match_all('/<li>(.*?)<\/li>/s', $result, $matches);
        $this->assertCount(4, $matches[1]);
        foreach ($matches[1] as $content) {
            $wordCount = str_word_count(strip_tags($content));
            $this->assertGreaterThanOrEqual(5, $wordCount);
            $this->assertLessThanOrEqual(10, $wordCount);
        }
    }

    public function testWrapAttributeWithClass(): void
    {
        $text = '{lorem wrap="ul.todos,3"}';
        $result = $this->processShortcodes($text);

        $this->assertStringStartsWith('<ul class="todos">', $result);
        $this->assertStringEndsWith('</ul>', $result);
        $this->assertEquals(1, substr_count($result, '<ul class="todos">'));
        $this->assertEquals(3, substr_count($result, '<li>'));
        $this->assertGreaterThan(0, str_word_count(strip_tags($result)));
    }
}
