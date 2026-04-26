<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Shortcode;

\defined('_JEXEC') or die;

/**
 * A shortcode for repeating content.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Repeat
{
    /**
     * Invoke the shortcode.
     *
     * @param array  $attributes The attributes of the shortcode.
     * @param string $content    The content to repeat.
     *
     * @return string
     */
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
