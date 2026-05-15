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
        $text = '{lorem}'; // Should produce full lorem without tags
        $result = $this->processShortcodes($text);

        $this->assertStringNotContainsString('<p>', $result);
        $this->assertStringNotContainsString('</p>', $result);
        $this->assertEquals(84, str_word_count($result));
        $this->assertEquals(Lorem::LOREMIPSUM, $result); // Should be the exact content
    }

    public function testLoremIpsumWithMoreWordsThanInSource(): void
    {
        $text = '{lorem p words="150"}';
        $result = $this->processShortcodes($text);

        $this->assertEquals(150, str_word_count(strip_tags($result)));
    }

    public function testLoremIpsumWithWordsAttribute(): void
    {
        $text = '{lorem p words="5"}';
        $result = $this->processShortcodes($text);

        $this->assertEquals(5, str_word_count(strip_tags($result)));
    }

    public function testLoremIpsumWithWordsRangeAttribute(): void
    {
        $text = '{lorem p words="5,10"}';
        $result = $this->processShortcodes($text);

        $wordCount = str_word_count(strip_tags($result));
        $this->assertGreaterThanOrEqual(5, $wordCount);
        $this->assertLessThanOrEqual(10, $wordCount);
    }

    public function testLoremIpsumWithInvalidWordsRange(): void
    {
        $text = '{lorem p words="10,5"}';
        $result = $this->processShortcodes($text);

        $wordCount = str_word_count(strip_tags($result));
        $this->assertEquals(10, $wordCount);
    }

    public function testParagraphWithDefaultCount(): void
    {
        $text = '{lorem p}';
        $result = $this->processShortcodes($text);

        $this->assertStringStartsWith('<p>', $result);
        $this->assertStringEndsWith('</p>', $result);
        $this->assertEquals(1, substr_count($result, '<p>'));
        $this->assertGreaterThan(0, str_word_count(strip_tags($result)));
    }

    public function testParagraphWithMultipleCount(): void
    {
        $text = '{lorem p 3}';
        $result = $this->processShortcodes($text);

        $this->assertEquals(3, substr_count($result, '<p>'));
        $this->assertGreaterThan(0, str_word_count(strip_tags($result)));
    }

    public function testParagraphWithCountRange(): void
    {
        $text = '{lorem p 3,8}';
        $result = $this->processShortcodes($text);

        $pCount = substr_count($result, '<p>');
        $this->assertGreaterThanOrEqual(3, $pCount);
        $this->assertLessThanOrEqual(8, $pCount);
        $this->assertGreaterThan(0, str_word_count(strip_tags($result)));
    }

    public function testUnorderedList(): void
    {
        $text = '{lorem ul 5}';
        $result = $this->processShortcodes($text);

        $this->assertStringStartsWith('<ul>', $result);
        $this->assertStringEndsWith('</ul>', $result);
        $this->assertEquals(1, substr_count($result, '<ul>'));
        $this->assertEquals(5, substr_count($result, '<li>'));
        $this->assertGreaterThan(0, str_word_count(strip_tags($result)));
    }

    public function testOrderedList(): void
    {
        $text = '{lorem ol 3}';
        $result = $this->processShortcodes($text);

        $this->assertStringStartsWith('<ol>', $result);
        $this->assertStringEndsWith('</ol>', $result);
        $this->assertEquals(1, substr_count($result, '<ol>'));
        $this->assertEquals(3, substr_count($result, '<li>'));
        $this->assertGreaterThan(0, str_word_count(strip_tags($result)));
    }

    public function testDivWithWords(): void
    {
        $text = '{lorem div 2 words="10"}';
        $result = $this->processShortcodes($text);

        $this->assertEquals(2, substr_count($result, '<div>'));
        // Extract content between <div> tags and check word count for each
        \preg_match_all('/<div>(.*?)<\/div>/s', $result, $matches);
        $this->assertCount(2, $matches[1]);
        foreach ($matches[1] as $content) {
            $this->assertEquals(10, str_word_count(strip_tags($content)));
        }
    }

    public function testListWithWordsRange(): void
    {
        $text = '{lorem ul 4 words="5,10"}';
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

    public function testListWithClassAttribute(): void
    {
        $text = '{lorem ul 3 class="todos"}';
        $result = $this->processShortcodes($text);

        $this->assertStringStartsWith('<ul class="todos">', $result);
        $this->assertStringEndsWith('</ul>', $result);
        $this->assertEquals(1, substr_count($result, '<ul class="todos">'));
        $this->assertEquals(3, substr_count($result, '<li>'));
        $this->assertGreaterThan(0, str_word_count(strip_tags($result)));
    }

    public function testImageGeneration(): void
    {
        $text = '{lorem img width=100 height=50}';

        if (!\extension_loaded('gd')) {
            $result = $this->processShortcodes($text);
            $this->assertStringContainsString('<div class="shortcode-error"', $result);
            $this->assertStringContainsString('Lorem: GD library is not installed. Cannot generate image.', $result);
        } else {
            $result = $this->processShortcodes($text);
            $this->assertStringStartsWith('<img src="data:image/png;base64,', $result);
            $this->assertStringContainsString('width="100"', $result);
            $this->assertStringContainsString('height="50"', $result);
            $this->assertStringEndsWith('" />', $result);
        }
    }

    public function testImageGenerationWithClass(): void
    {
        $text = '{lorem img width=100 height=50 class="my-image"}';

        if (!\extension_loaded('gd')) {
            $result = $this->processShortcodes($text);
            $this->assertStringContainsString('<div class="shortcode-error"', $result);
            $this->assertStringContainsString('Lorem: GD library is not installed. Cannot generate image.', $result);
        } else {
            $result = $this->processShortcodes($text);
            $this->assertStringContainsString('class="my-image"', $result);
            $this->assertStringStartsWith('<img src="data:image/png;base64,', $result);
            $this->assertStringContainsString('width="100"', $result);
            $this->assertStringContainsString('height="50"', $result);
            $this->assertStringEndsWith('" />', $result);
        }
    }

    public function testImageGenerationWithAlt(): void
    {
        $text = '{lorem img width=100 height=50 alt="My placeholder image"}';

        if (!\extension_loaded('gd')) {
            $result = $this->processShortcodes($text);
            $this->assertStringContainsString('<div class="shortcode-error"', $result);
            $this->assertStringContainsString('Lorem: GD library is not installed. Cannot generate image.', $result);
        } else {
            $result = $this->processShortcodes($text);
            $this->assertStringContainsString('alt="My placeholder image"', $result);
            $this->assertStringStartsWith('<img src="data:image/png;base64,', $result);
            $this->assertStringContainsString('width="100"', $result);
            $this->assertStringContainsString('height="50"', $result);
            $this->assertStringEndsWith('" />', $result);
        }
    }

    public function testLoremIpsumWithZeroWords(): void
    {
        $text = '{lorem p words="0"}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('<div class="shortcode-error"', $result);
        $this->assertStringContainsString('<b>Lorem</b>: Word count must be a positive integer.', $result);
    }

    public function testLoremIpsumWithNegativeWords(): void
    {
        $text = '{lorem p words="-5"}';
        $result = $this->processShortcodes($text);
        $this->assertStringContainsString('<div class="shortcode-error"', $result);
        $this->assertStringContainsString('<b>Lorem</b>: Word count must be a positive integer.', $result);
    }

    public function testParagraphWithClassAndNoExplicitTag(): void
    {
        $text = '{lorem class="my-class"}';
        $result = $this->processShortcodes($text);

        $this->assertStringStartsWith('<p class="my-class">', $result);
        $this->assertStringEndsWith('</p>', $result);
        $this->assertEquals(1, substr_count($result, '<p class="my-class">'));
        $this->assertEquals(84, str_word_count(strip_tags($result)));
    }
}
