<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Helper;

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
     * @param array $attributes     The original shortcode attributes.
     * @param array $baseAttributes An initial set of iframe attributes.
     *
     * @return array The final array of attributes for the iframe element.
     */
    public static function buildIframeAttributes(array $attributes, array $baseAttributes): array
    {
        // Ignore width and height because they are used by wrapper
        unset($attributes['width'], $attributes['height']);

        // Merge base and user-provided attributes
        $iframeAttributes = \array_merge($baseAttributes, $attributes);

        // Filter and return only the recognized iframe attributes
        return [
            'title' => $iframeAttributes['title'] ?? '',
            'width' => $iframeAttributes['width'] ?? '',
            'height' => $iframeAttributes['height'] ?? '',
            'frameborder' => $iframeAttributes['frameborder'] ?? '',
            'allowfullscreen' => $iframeAttributes['allowfullscreen'] ?? '',
            'allow' => $iframeAttributes['allow'] ?? '',
            'referrerpolicy' => $iframeAttributes['referrerpolicy'] ?? '',
            'loading' => $iframeAttributes['loading'] ?? '',
            'style' => $iframeAttributes['style'] ?? '',
        ];
    }
}
