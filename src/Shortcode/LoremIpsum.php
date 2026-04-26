<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Shortcode;

\defined('_JEXEC') or die;

class LoremIpsum
{
    private static ?array $words = null;

    public function __invoke(array $attributes): string
    {
        $wordsAttr = $attributes['words'] ?? '100';

        $minWordCount = 1;
        $maxWordCount = null;

        if (\is_string($wordsAttr) && \strpos($wordsAttr, ',') !== false) {
            [$min, $max] = explode(',', $wordsAttr);
            $minWordCount = (int) $min;
            $maxWordCount = (int) $max;

            if ($maxWordCount < $minWordCount) {
                $maxWordCount = $minWordCount;
            }
        } else {
            $minWordCount = (int) $wordsAttr;
            $maxWordCount = $minWordCount;
        }

        $chosenWordCount = $minWordCount;
        if ($maxWordCount !== null && $maxWordCount > $minWordCount) {
            $chosenWordCount = rand($minWordCount, $maxWordCount);
        }

        return $this->generateLoremIpsum($chosenWordCount);
    }

    /**
     * Generates a Lorem Ipsum paragraph with a specified word count.
     *
     * @param int $wordCount The exact number of words for the paragraph.
     *
     * @return string A Lorem Ipsum paragraph.
     */
    private function generateLoremIpsum(int $wordCount = 1): string
    {
        if (self::$words === null) {
            self::$words = explode(
                ' ',
                <<<LOREMIPSUM
Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy
nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut
wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis
nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor
in hendrerit in vulputate velit esse molestie consequat, vel illum dolore
eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim
qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
LOREMIPSUM
            );
        }

        $currentParagraphWords = array_slice(self::$words, 0, $wordCount);
        $text = implode(' ', $currentParagraphWords);

        // Ensure it ends with a dot
        if (substr($text, -1) !== '.') {
            if (substr($text, -1) === ',') {
                $text = substr($text, 0, -1);
            }
            $text .= '.';
        }

        return $text;
    }
}
