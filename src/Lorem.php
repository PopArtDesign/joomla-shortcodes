<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

\defined('_JEXEC') or die;

/**
 * A shortcode for generating Lorem Ipsum text.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Lorem
{
    public const LOREMIPSUM = <<<LOREMIPSUM
Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy
nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut
wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis
nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor
in hendrerit in vulputate velit esse molestie consequat, vel illum dolore
eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim
qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
LOREMIPSUM;

    /**
     * @var array|null
     */
    private static ?array $words = null;

    /**
     * Invoke the shortcode.
     *
     * @param array $attributes The attributes of the shortcode.
     *
     * @return string
     */
    public function __invoke(array $attributes): string
    {
        $minWords = 0;
        $maxWords = 0;

        if (isset($attributes['words'])) {
            [$minWords, $maxWords] = AttributeHelper::parseRange($attributes['words'], [0, 0]);
        }

        if (isset($attributes['wrap'])) {
            return $this->processWrappedContent($attributes, $minWords, $maxWords);
        }

        return $this->generateLoremText($minWords, $maxWords);
    }

    /**
     * Processes content to be wrapped in HTML tags.
     *
     * @param array $attributes The attributes of the shortcode.
     * @param int   $minWords   The minimum number of words for each wrapped item.
     * @param int   $maxWords   The maximum number of words for each wrapped item.
     *
     * @return string The generated HTML with wrapped Lorem Ipsum text.
     */
    private function processWrappedContent(array $attributes, int $minWords, int $maxWords): string
    {
        [$tag, $class, $count] = AttributeHelper::parseTag($attributes['wrap'], ['p', '', 1]);
        $output = [];

        $classAttr = $class ? " class=\"{$class}\"" : '';

        if ($tag === 'ul' || $tag === 'ol') {
            $output[] = "<{$tag}{$classAttr}>";
            for ($i = 0; $i < $count; $i++) {
                $loremText = $this->generateLoremText($minWords, $maxWords);
                $output[] = "<li>{$loremText}</li>";
            }
            $output[] = "</{$tag}>";
        } else {
            for ($i = 0; $i < $count; $i++) {
                $loremText = $this->generateLoremText($minWords, $maxWords);
                $output[] = "<{$tag}{$classAttr}>{$loremText}</{$tag}>";
            }
        }

        return \implode('', $output);
    }

    /**
     * Generates Lorem Ipsum text based on a word count range.
     *
     * @param int $minWords The minimum number of words. If 0, full LOREMIPSUM is used.
     * @param int $maxWords The maximum number of words. Only used if minWords > 0.
     *
     * @return string The generated Lorem Ipsum text.
     */
    private function generateLoremText(int $minWords, int $maxWords): string
    {
        if ($minWords === 0 && $maxWords === 0) {
            return $this->ensureEndsWithDot(self::LOREMIPSUM);
        }

        $chosenWords = \rand($minWords, $maxWords);

        return $this->words($chosenWords);
    }

    /**
     * Generates a Lorem Ipsum paragraph with a specified word count.
     *
     * @param int $words The exact number of words for the paragraph.
     *
     * @return string A Lorem Ipsum paragraph.
     */
    private function words(int $words = 1): string
    {
        if ($words <= 0) {
            return '';
        }

        $this->extractWords();

        $wordCount = \count(self::$words);
        if ($wordCount === 0) {
            return '';
        }

        $textWords = [];
        for ($i = 0; $i < $words; $i++) {
            $textWords[] = self::$words[$i % $wordCount];
        }

        $text = \implode(' ', $textWords);

        return $this->ensureEndsWithDot($text);
    }

    /**
     * Extract words from the Lorem Ipsum text.
     */
    private function extractWords(): void
    {
        if (self::$words === null) {
            self::$words = \preg_split('/\s+/', self::LOREMIPSUM, -1, PREG_SPLIT_NO_EMPTY);
        }
    }

    /**
     * Ensure the text ends with a dot.
     *
     * @param string $text The text to check.
     *
     * @return string
     */
    private function ensureEndsWithDot(string $text): string
    {
        $last = \substr($text, -1, 1);

        if ($last === '.') {
            return $text;
        }

        if ($last === ',') {
            return \substr_replace($text, '.', -1);
        }

        return $text . '.';
    }
}
