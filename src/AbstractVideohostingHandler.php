<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

\defined('_JEXEC') or die;

/**
 * Abstract base class for shortcodes that embed video content from video hosting services.
 *
 * Provides common functionality for video-specific attributes
 * like autoplay, start/end times, and aspect ratio.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
abstract class AbstractVideohostingHandler extends AbstractIframeHandler
{
    /**
     * @inheritdoc
     */
    protected function getWrapperAttributes(array $attributes): array
    {
        $wrapperAttributes = parent::getWrapperAttributes($attributes);

        $wrapperAttributes['class'] = \trim(($wrapperAttributes['class'] ?? '') . ' embed-video');

        $styles = [];
        if ($wrapperAttributes['style'] ?? '') {
            $styles[] = $wrapperAttributes['style'];
        }

        $aspectRatio = $attributes['aspect-ratio'] ?? '16 / 9';
        $styles[] = 'aspect-ratio: var(--embed-video-aspect-ratio, ' . \htmlspecialchars($aspectRatio) . ');';

        if ($attributes['width'] ?? '') {
            $styles[] = 'width: ' . $attributes['width'];
        }
        if ($attributes['height'] ?? '') {
            $styles[] = 'height: ' . $attributes['height'];
        }

        $wrapperAttributes['style'] = \implode(';', $styles);

        return $wrapperAttributes;
    }

    /**
     * @inheritdoc
     */
    protected function getIframeAttributes(array $attributes): array
    {
        $iframeAttributes = [
            'title' => 'Video player',
            'allow' => 'autoplay',
            'referrerpolicy' => 'strict-origin-when-cross-origin',
        ];

        return $iframeAttributes;
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
}
