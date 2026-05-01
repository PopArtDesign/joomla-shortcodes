<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

\defined('_JEXEC') or die;

/**
 * Abstract class for video embed handlers, extending AbstractEmbedHandler.
 * Provides common functionality for video embeds, including iframe attribute building
 * and handling of autoplay, start, and end times.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
abstract class AbstractVideoEmbedHandler extends AbstractEmbedHandler
{
    /**
     * Returns the URL for the video embed.
     *
     * @param string $url        The original URL provided by the user.
     * @param array  $attributes The shortcode attributes.
     *
     * @return string The URL to be used for the iframe src.
     */
    abstract protected function getEmbedUrl(string $url, array $attributes): string;

    /**
     * Returns an array of default attributes for the video embed.
     *
     * @return array An associative array of default attributes.
     */
    abstract protected function getDefaults(): array;

    /**
     * Builds an array of iframe attributes based on the provided shortcode attributes and default values.
     *
     * @param array $attributes The shortcode attributes.
     *
     * @return array An associative array of iframe attributes.
     */
    protected function buildIframeAttributes(array $attributes): array
    {
        $defaults = \array_merge([
            'title' => 'Video player',
            'class' => null,
            'allow' => 'autoplay',
            'referrerpolicy' => 'strict-origin-when-cross-origin',
        ], $this->getDefaults());

        return [
            'width' => $attributes['width'] ?? '100%',
            'height' => $attributes['height'] ?? 'auto',
            'title' => $attributes['title'] ?? $defaults['title'],
            'frameborder' => $attributes['frameborder'] ?? '0',
            'allowfullscreen' => $attributes['allowfullscreen'] ?? '',
            'allow' => $attributes['allow'] ?? $defaults['allow'],
            'referrerpolicy' => $attributes['referrerpolicy'] ?? $defaults['referrerpolicy'],
        ];
    }

    /**
     * Check if autoplay is enabled for the video.
     *
     * @param array $attributes The shortcode attributes.
     *
     * @return bool True if autoplay is enabled, false otherwise.
     */
    protected function getAutoplay(array $attributes): bool
    {
        return AttributeHelper::isEnabled('autoplay', $attributes);
    }

    /**
     * Get the start time from the attributes.
     *
     * @param array   $attributes The shortcode attributes.
     * @param ?int    $default    The default time in seconds to return if parsing fails or start is not set.
     *
     * @return ?int The start time in seconds, or the default value if not set.
     */
    protected function getStart(array $attributes, ?int $default = 0): ?int
    {
        if (!isset($attributes['start'])) {
            return $default;
        }

        return AttributeHelper::parseTime($attributes['start'], $default);
    }

    /**
     * Get the end time from the attributes.
     *
     * @param array $attributes The shortcode attributes.
     *
     * @return ?int The end time in seconds, or null if not set.
     */
    protected function getEnd(array $attributes): ?int
    {
        if (!isset($attributes['end'])) {
            return null;
        }

        return AttributeHelper::parseTime($attributes['end'], null);
    }

    /**
     * {@inheritdoc}
     */
    public function process(string $url, array $attributes): string
    {
        $src = $this->getEmbedUrl($url, $attributes);

        if (!$src) {
            return '';
        }

        $iframeAttributes = $this->buildIframeAttributes($attributes);

        $wrapperStyles = [];

        if (($attributes['height'] ?? 'auto') === 'auto') {
            $aspectRatio = $attributes['aspect-ratio'] ?? '16 / 9';
            $iframeAttributes['aspect-ratio'] = 'var(--embed-aspect-ratio)';
            $wrapperStyles[] = '--embed-aspect-ratio: ' . \htmlspecialchars($aspectRatio);
        }

        $html = Iframe::render($src, $iframeAttributes);

        $baseClasses = ['embed-container', 'embed-video'];
        $defaults = $this->getDefaults();
        if (!empty($defaults['class'])) {
            $baseClasses[] = $defaults['class'];
        }

        return $this->renderWrapper($html, $baseClasses, $attributes, $wrapperStyles);
    }
}
