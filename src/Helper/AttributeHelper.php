<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Helper;

\defined('_JEXEC') or die;

/**
 * A helper class for shortcode attributes.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class AttributeHelper
{
    /**
     * Parses a range from a shortcode attribute.
     * The format can be 'min,max' or a single number.
     *
     * @param string|int|null $value   The attribute value.
     * @param array|null      $default The default range to return if parsing fails.
     *
     * @return null|array{int, int} An array containing [min, max] or null if the value
     *                              cannot be properly parsed and no default is provided.
     */
    public static function parseRange(string $value, ?array $default = null): ?array
    {
        $value = \trim($value);
        if (($value === '')) {
            return $default;
        }

        if (\is_numeric($value)) {
            $val = (int) $value;
            return [$val, $val];
        }

        $parts = \explode(',', $value);
        if (\count($parts) !== 2 || !\is_numeric($parts[0]) || !\is_numeric($parts[1])) {
            return $default;
        }

        [$min, $max] = $parts;
        $min = (int) $min;
        $max = (int) $max;

        if ($max < $min) {
            $max = $min;
        }

        return [$min, $max];
    }

    /**
     * Parses a tag and an optional repeat count from a string.
     * The format can be 'tag,count' or 'tag'.
     *
     * @param string     $value   The attribute value (e.g., "p,10" or "ul").
     * @param array|null $default The default tag and count to return if parsing fails.
     *
     * @return null|array{string, int} An array containing [tag, count] or null if the value
     *                                 cannot be properly parsed and no default is provided.
     */
    public static function parseTag(string $value, ?array $default = null): ?array
    {
        if ($value === null || \trim($value) === '') {
            return $default;
        }

        $parts = \explode(',', $value);
        $tagAndClass = \trim($parts[0]);
        $count = 1;
        $class = '';

        // Extract class if present (e.g., "ul.todos")
        if (\strpos($tagAndClass, '.') !== false) {
            [$tag, $class] = \explode('.', $tagAndClass, 2);
            $tag = \trim($tag);
            $class = \trim($class);
        } else {
            $tag = $tagAndClass;
        }

        if (isset($parts[1]) && \is_numeric($parts[1])) {
            $count = (int) $parts[1];
        }

        if ($count < 1) {
            $count = 1;
        }

        return [$tag, $class, $count];
    }

    /**
     * Parse start time string (e.g., "1:23") into seconds.
     *
     * @param string $time
     *
     * @return int
     */
    public static function parseTime(string $time): int
    {
        $parts = explode(':', $time);
        $numParts = count($parts);

        if ($numParts === 3) { // hh:mm:ss
            return (int) $parts[0] * 3600 + (int) $parts[1] * 60 + (int) $parts[2];
        } elseif ($numParts === 2) { // mm:ss
            return (int) $parts[0] * 60 + (int) $parts[1];
        }

        return (int) $time; // seconds
    }
}
