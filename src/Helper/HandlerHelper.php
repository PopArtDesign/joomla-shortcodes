<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Helper;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HtmlHelper;

\defined('_JEXEC') or die;

/**
 * A helper class for shortcode handlers.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class HandlerHelper
{
    /**
     * Renders a responsive iframe embed, wrapped in a container.
     *
     * @param string $url                   The iframe source URL.
     * @param array  $attributes            The original shortcode attributes.
     * @param array  $baseWrapperAttributes An initial set of wrapper attributes.
     * @param array  $baseIframeAttributes  An initial set of iframe attributes.
     *
     * @return string The rendered HTML for the iframe embed.
     */
    public static function iframe(string $url, array $attributes, array $baseWrapperAttributes, array $baseIframeAttributes): string
    {
        $iframeAttributes = self::buildIframeAttributes($attributes, $baseIframeAttributes);
        $iframe = HtmlHelper::iframe($url, $iframeAttributes);

        return self::wrapper($iframe, $attributes, $baseWrapperAttributes);
    }

    /**
     * Renders a content block inside a wrapper div.
     *
     * @param string $content        The content to be wrapped.
     * @param array  $attributes     The original shortcode attributes.
     * @param array  $baseAttributes An initial set of wrapper attributes.
     *
     * @return string The rendered HTML for the wrapped content.
     */
    public static function wrapper(string $content, array $attributes, array $baseAttributes): string
    {
        $wrapperAttributes = self::buildWrapperAttributes($attributes, $baseAttributes);

        return HtmlHelper::div($wrapperAttributes, $content);
    }

    /**
     * Builds and processes wrapper attributes for shortcode embeds.
     *
     * @param array $attributes     The original shortcode attributes.
     * @param array $baseAttributes An initial set of wrapper attributes.
     *
     * @return array The final array of attributes for the wrapper element.
     */
    public static function buildWrapperAttributes(array $attributes, array $baseAttributes): array
    {
        $wrapperAttributes = $baseAttributes;

        // Handle custom wrapper attributes from the shortcode
        if ($attributes['id'] ?? '') {
            $wrapperAttributes['id'] = $attributes['id'];
        }

        if ($attributes['class'] ?? '') {
            $wrapperAttributes['class'] = \trim(($wrapperAttributes['class'] ?? '') . ' ' . $attributes['class']);
        }

        $newStyles = [];
        if (!empty($attributes['width'])) {
            $width = $attributes['width'];
            $newStyles[] = 'width: ' . (\is_numeric($width) ? $width . 'px' : $width);
        }
        if (!empty($attributes['height'])) {
            $height = $attributes['height'];
            $newStyles[] = 'height: ' . (\is_numeric($height) ? $height . 'px' : $height);
        }

        $style = \implode('; ', $newStyles);

        if (!empty($attributes['style'])) {
            $style = $style ? $style . '; ' . $attributes['style'] : $attributes['style'];
        }

        if ($style) {
            $wrapperAttributes['style'] = \trim(($wrapperAttributes['style'] ?? '') . '; ' . $style, '; ');
        }

        return $wrapperAttributes;
    }

    /**
     * Builds and processes iframe attributes for shortcode embeds.
     *
     * Attributes like `id`, `class`, `whdth` and `height` are handled by
     * the wrapper element, not the iframe tag directly.
     *
     * @param array $attributes     The original shortcode attributes.
     * @param array $baseAttributes An initial set of iframe attributes.
     *
     * @return array The final array of attributes for the iframe element.
     */
    public static function buildIframeAttributes(array $attributes, array $baseAttributes): array
    {
        // Ignore width, height, id, and class because they are used by wrapper
        unset($attributes['width'], $attributes['height'], $attributes['id'], $attributes['class']);

        // Merge base and user-provided attributes. User attributes will override base attributes.
        return \array_merge($baseAttributes, $attributes);
    }

    /**
     * Renders a styled div containing an error message.
     *
     * @param string $message The error message to display.
     *
     * @return string The rendered HTML for the error message.
     */
    public static function error(string $message): string
    {
        $styles = [
            'color: red',
            'font-weight: bold',
            'border: 1px solid red',
            'padding: 10px',
            'margin: 1em 0',
            'background-color: #ffecec',
        ];

        return HtmlHelper::div(['class' => 'shortcode-error', 'style' => \implode(';', $styles)], $message);
    }
}
