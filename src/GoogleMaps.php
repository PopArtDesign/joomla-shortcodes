<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HandlerHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\AbstractShortcodeHandler;

\defined('_JEXEC') or die;

/**
 * Handles the `googlemaps` shortcode to embed Google Maps.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class GoogleMaps extends AbstractShortcodeHandler
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
    protected function process(array $attributes, string $content): string
    {
        $q = AttributeHelper::getValue($attributes, $content, 'query');

        if (empty($q)) {
            $this->error('Query is required. It can be provided as a `query` attribute, content, or a positional argument.');
        }

        $type = \strtolower($attributes['type'] ?? 'roadmap');
        if (!\array_key_exists($type, self::MAP_TYPES)) {
            $this->error('Invalid map type specified. Available types: ' . \implode(', ', \array_keys(self::MAP_TYPES)));
        }

        $queryParams = [
            'output' => 'embed',
            'q' => $q,
            'z' => $attributes['zoom'] ?? '21',
            't' => self::MAP_TYPES[$type],
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
