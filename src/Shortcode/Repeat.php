<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Shortcode;

\defined('_JEXEC') or die;

class Repeat
{
    public function __invoke(array $attributes, string $content): string
    {
        $repeatAttr = $attributes[0] ?? '1';

        $minRepeats = 1;
        $maxRepeats = null;

        if (\is_string($repeatAttr) && \strpos($repeatAttr, ',') !== false) {
            [$min, $max] = explode(',', $repeatAttr);
            $minRepeats = (int) $min;
            $maxRepeats = (int) $max;

            if ($maxRepeats < $minRepeats) {
                $maxRepeats = $minRepeats;
            }
        } else {
            $minRepeats = (int) $repeatAttr;
            $maxRepeats = $minRepeats;
        }

        $numberOfRepeats = $minRepeats;
        if ($maxRepeats !== null && $maxRepeats > $minRepeats) {
            $numberOfRepeats = rand($minRepeats, $maxRepeats);
        }

        if ($numberOfRepeats <= 0) {
            return '';
        }

        return \str_repeat($content, $numberOfRepeats);
    }
}
