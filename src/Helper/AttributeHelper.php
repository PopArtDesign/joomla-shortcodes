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
     * Parse start time string (e.g., "1:23", "01:02:03") into seconds.
     *
     * @param string $time
     * @param int|null $default The default time in seconds to return if parsing fails.
     *
     * @return int|null The time in seconds, or null if the value cannot be
     *                  properly parsed and no default is provided.
     */
    public static function parseTime(string $time, ?int $default = null): ?int
    {
        $time = trim($time);
        if ($time === '') {
            return $default;
        }

        $parts = explode(':', $time);
        $numParts = count($parts);

        if ($numParts === 3) { // hh:mm:ss
            return (int) $parts[0] * 3600 + (int) $parts[1] * 60 + (int) $parts[2];
        } elseif ($numParts === 2) { // mm:ss
            return (int) $parts[0] * 60 + (int) $parts[1];
        } elseif (is_numeric($time)) { // seconds
            return (int) $time;
        }

        return $default; // If format is not recognized
    }

    /**
     * Parses a boolean value from an attribute.
     * Considers "true", "1", "yes" as true.
     * Considers "false", "0", "no" as false.
     *
     * @param string $value The attribute value to parse.
     * @param bool|null $default The default boolean to return if parsing fails or value is empty.
     *
     * @return bool|null The parsed boolean, or null if parsing fails and no default is provided.
     */
    public static function parseBoolean(string $value, ?bool $default = null): ?bool
    {
        $value = strtolower(trim($value));

        if ($value === '') {
            return $default;
        }

        if (in_array($value, ['true', '1', 'yes'], true)) {
            return true;
        }

        if (in_array($value, ['false', '0', 'no'], true)) {
            return false;
        }

        return $default; // If format is not recognized
    }

    /**
     * Checks if a flag-like attribute is enabled.
     *
     * This method checks for the presence of a key in the attributes array,
     * and also handles valueless attributes (flags).
     * e.g. {shortcode autoplay} or {shortcode autoplay="true"}
     *
     * @param string $key        The attribute key to check (e.g., 'autoplay').
     * @param array  $attributes The attributes array from the shortcode.
     *
     * @return bool True if the attribute is considered enabled, false otherwise.
     */
    public static function isEnabled(string $key, array $attributes): bool
    {
        if (array_key_exists($key, $attributes)) {
            return (bool) self::parseBoolean($attributes[$key], false);
        }

        if (isset($attributes['_']) && is_array($attributes['_']) && in_array($key, $attributes['_'], true)) {
            return true;
        }

        return false;
    }
}
