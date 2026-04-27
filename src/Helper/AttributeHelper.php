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
}
