<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Shortcode;

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
        $wordsAttr = $attributes['words'] ?? '100';

        $minWords = 1;
        $maxWords = null;

        if (\is_string($wordsAttr) && \strpos($wordsAttr, ',') !== false) {
            [$min, $max] = explode(',', $wordsAttr);
            $minWords = (int) $min;
            $maxWords = (int) $max;

            if ($maxWords < $minWords) {
                $maxWords = $minWords;
            }
        } else {
            $minWords = (int) $wordsAttr;
            $maxWords = $minWords;
        }

        $chosenWords = $minWords;
        if ($maxWords !== null && $maxWords > $minWords) {
            $chosenWords = \rand($minWords, $maxWords);
        }

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
