<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HandlerHelper;

\defined('_JEXEC') or die;

/**
 * Handles the `googlemaps` shortcode to embed Google Maps.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class GoogleMaps
{
    public const MAP_TYPES = [
        'roadmap' => 'm',
        'satellite' => 'k',
        'hybrid' => 'h',
        'terrain' => 'p',
    ];

    /**
     * Invoke the shortcode.
     *
     * @param array  $attributes The attributes of the shortcode.
     * @param string $content    The content of the shortcode.
     *
     * @return string The full HTML output for the embed.
     */
    public function __invoke(array $attributes, string $content): string
    {
        $q = '';
        if (!empty($attributes['address'])) {
            $q = $attributes['address'];
        } elseif (!empty($attributes['coordinates'])) {
            $q = $attributes['coordinates'];
        }

        if (empty($q)) {
            return HandlerHelper::error('GoogleMaps: Address or coordinates attribute required.');
        }

        $queryParams = [
            'output' => 'embed',
            'q' => $q,
            'z' => $attributes['zoom'] ?? '21',
            't' => self::MAP_TYPES[\strtolower($attributes['type'] ?? 'roadmap')],
        ];

        $src = 'https://maps.google.com/maps?' . \http_build_query($queryParams);

        if (!isset($attributes['height'])) {
            $attributes['height'] = 'var(--embed-map-height, 50vh)';
        }

        $baseWrapperAttributes = [
            'class' => 'embed-container embed-map embed-googlemaps',
        ];

        $baseIframeAttributes = [
            'title' => 'Google map',
            'width' => '100%',
            'height' => '100%',
            'frameborder' => '0',
            'allow' => '',
            'allowfullscreen' => '',
            'referrerpolicy' => 'strict-origin-when-cross-origin',
            'loading' => 'lazy',
        ];

        return HandlerHelper::iframe($src, $attributes, $baseWrapperAttributes, $baseIframeAttributes);
    }
}
