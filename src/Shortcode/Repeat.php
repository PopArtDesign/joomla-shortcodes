<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Shortcode;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

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

        [$minRepeats, $maxRepeats] = AttributeHelper::parseRange($repeatAttr, [0, 0]);

        $numberOfRepeats = \rand($minRepeats, $maxRepeats);

        if ($numberOfRepeats <= 0) {
            return '';
        }

        return \str_repeat($content, $numberOfRepeats);
    }
}
