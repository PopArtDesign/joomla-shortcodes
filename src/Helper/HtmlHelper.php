<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Helper;

\defined('_JEXEC') or die;

/**
 * A helper class for generating HTML tags.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class HtmlHelper
{
    /**
     * Render a div tag with the given attributes and content.
     *
     * @param array  $attributes The div attributes.
     * @param string $content    The content inside the div.
     *
     * @return string The rendered div HTML.
     */
    public static function div(array $attributes = [], string $content = ''): string
    {
        return self::tag('div', $attributes, $content, false);
    }

    /**
     * Render an iframe with the given attributes.
     *
     * @param string $url        The iframe source URL.
     * @param array  $attributes The iframe attributes: width, height, title, allow,
     *                           referrerpolicy, frameborder, allowfullscreen.
     *
     * @return string The rendered iframe HTML.
     */
    public static function iframe(string $url, array $attributes = []): string
    {
        $attributes = \array_merge(['src' => $url], $attributes);

        return self::tag('iframe', $attributes, '', false, ['allowfullscreen']);
    }

    /**
     * Render an object tag for embeds like PDF.
     *
     * @param string $url        The object data URL.
     * @param string $type       The MIME type of the object (e.g., 'application/pdf').
     * @param array  $attributes The object attributes.
     * @param string $content    The fallback content inside the object tag.
     *
     * @return string The rendered object HTML.
     */
    public static function object(string $url, string $type, array $attributes = [], string $content = ''): string
    {
        $attributes = \array_merge(['data' => $url, 'type' => $type], $attributes);

        return self::tag('object', $attributes, $content, false);
    }

    /**
     * Render a script tag for embeds like Gist.
     *
     * @param string $src        The script source URL.
     * @param array  $attributes The script attributes.
     *
     * @return string The rendered script HTML.
     */
    public static function script(string $src, array $attributes = []): string
    {
        $attributes = \array_merge(['src' => $src], $attributes);

        return self::tag('script', $attributes, '', false);
    }

    /**
     * Renders an HTML tag with the given attributes and content.
     *
     * @param string $tag          The HTML tag name (e.g., 'div', 'iframe', 'script', 'object').
     * @param array  $attributes   An associative array of attribute names and values.
     * @param string $content      The content to be placed inside the tag.
     * @param bool   $selfClosing  Whether the tag is self-closing (e.g., <img />).
     * @param array  $booleanAttrs An array of attribute names that should be treated as boolean.
     *                             If a boolean attribute's value is truthy, it will be added without a value (e.g., 'autoplay').
     *
     * @return string The rendered HTML tag.
     */
    public static function tag(string $tag, array $attributes = [], string $content = '', bool $selfClosing = false, array $booleanAttrs = []): string
    {
        $attributeString = self::attributes($attributes, $booleanAttrs);
        $attrPart = $attributeString ? ' ' . $attributeString : '';

        if ($selfClosing) {
            return \sprintf('<%s%s />', $tag, $attrPart);
        }

        return \sprintf('<%s%s>%s</%s>', $tag, $attrPart, $content, $tag);
    }


    /**
     * Converts an associative array of attributes into an HTML attribute string.
     *
     * @param array $attributes   An associative array of attribute names and values.
     * @param array $booleanAttrs An array of attribute names that should be treated as boolean.
     *                            If a boolean attribute's value is truthy, it will be added without a value (e.g., 'autoplay').
     *
     * @return  string The HTML attribute string.
     */
    public static function attributes(array $attributes, array $booleanAttrs = []): string
    {
        $attrs = [];
        foreach ($attributes as $name => $value) {
            if (!\is_scalar($value) || \is_int($name)) {
                continue;
            }

            if (\is_bool($value)) {
                if ($value === true) {
                    $attrs[] = $name;
                }

                continue;
            }

            if (\in_array($name, $booleanAttrs, true)) {
                // For boolean attributes, add only the name if the value is truthy.
                // An empty string value also implies the attribute is present/enabled.
                if (\in_array($value, ['true', 'yes', '1'], true)) {
                    $attrs[] = $name;
                }

                continue;
            }

            if ($value !== '') {
                $attrs[] = $name . '="' . \htmlspecialchars($value, \ENT_QUOTES | \ENT_HTML5) . '"';
            }
        }

        return \implode(' ', $attrs);
    }
}
