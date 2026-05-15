<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\AbstractShortcodeHandler;

\defined('_JEXEC') or die;

/**
 * A shortcode for repeating content.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class Repeat extends AbstractShortcodeHandler
{
    /**
     * Invoke the shortcode.
     *
     * @param array  $attributes The attributes of the shortcode.
     * @param string $content    The content to repeat.
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function process(array $attributes, string $content): string
    {
        $repeatAttr = $attributes[0] ?? '1';

        [$minRepeats, $maxRepeats] = AttributeHelper::parseRange($repeatAttr, [0, 0]);

        $numberOfRepeats = \rand($minRepeats, $maxRepeats);

        if ($numberOfRepeats <= 0) {
            $this->error('Number of repeats must be a positive integer.');
        }

        return \str_repeat($content, $numberOfRepeats);
    }
}
