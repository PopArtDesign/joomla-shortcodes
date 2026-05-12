<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HandlerHelper;

\defined('_JEXEC') or die;

/**
 * Abstract base class for shortcodes that embed video content from video hosting services.
 *
 * Provides common functionality for video-specific attributes
 * like autoplay, start/end times, and aspect ratio.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
abstract class AbstractVideohostingHandler
{
    /**
     * The main shortcode invokation method.
     *
     * @param array  $attributes The shortcode attributes. In addition to video-specific attributes,
     *                           all standard iframe attributes (e.g., `title`, `loading`, `referrerpolicy`,
     *                           `sandbox`, etc.) are supported and passed through to the generated iframe.
     * @param string $content    The content between shortcode tags.
     *
     * @return string The full HTML output for the embed.
     */
    public function __invoke(array $attributes, string $content): string
    {
        $url = AttributeHelper::getAbsoluteUrl($attributes, $content);
        if ($url === null) {
            $shortcodeName = (new \ReflectionClass($this))->getShortName();

            return HandlerHelper::error(\sprintf('%s: A valid video URL was not found.', $shortcodeName));
        }

        $src = $this->getEmbedUrl((string) $url, $attributes);
        if ($src === null) {
            $shortcodeName = (new \ReflectionClass($this))->getShortName();

            return HandlerHelper::error(\sprintf('%s: Could not generate embed URL.', $shortcodeName));
        }

        $class = \strtolower((new \ReflectionClass($this))->getShortName());
        $baseWrapperAttributes = ['class' => 'embed-container embed-video embed-' . $class ];

        $styles = [];

        $aspectRatio = $attributes['aspect-ratio'] ?? '16 / 9';
        $styles[] = 'aspect-ratio: var(--embed-video-aspect-ratio, ' . \htmlspecialchars($aspectRatio) . ')';

        $baseWrapperAttributes['style'] = \implode(';', $styles);

        $baseIframeAttributes = \array_merge([
            'title' => 'Video player',
            'width' => '100%',
            'height' => '100%',
            'frameborder' => '0',
            'allow' => 'autoplay',
            'allowfullscreen' => true,
            'referrerpolicy' => 'strict-origin-when-cross-origin',
            'loading' => 'lazy',
        ], $this->getIframeAttributes($attributes));

        return HandlerHelper::iframe(
            $src,
            $attributes,
            $baseWrapperAttributes,
            $baseIframeAttributes
        );
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
     * Check if mute is enabled for the video.
     *
     * @param array $attributes The shortcode attributes.
     *
     * @return bool True if mute is enabled, false otherwise.
     */
    protected function getMute(array $attributes): bool
    {
        return AttributeHelper::isEnabled('mute', $attributes);
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
     * Returns the URL for the iframe embed.
     * Implemented by concrete shortcode classes.
     *
     * @param string $url        The original URL provided by the user.
     * @param array  $attributes The shortcode attributes.
     *
     * @return string|null The URL to be used for the iframe src, or null if it cannot be constructed.
     */
    abstract protected function getEmbedUrl(string $url, array $attributes): ?string;

    /**
     * Returns iframe attributes specific to the video service.
     * Implemented by concrete shortcode classes to customize iframe behavior.
     *
     * @param array $attributes The shortcode attributes.
     *
     * @return array An associative array of iframe attributes.
     */
    abstract protected function getIframeAttributes(array $attributes): array;

    /**
     * Check if loop is enabled for the video.
     *
     * @param array $attributes The shortcode attributes.
     *
     * @return bool True if loop is enabled, false otherwise.
     */
    protected function getLoop(array $attributes): bool
    {
        return AttributeHelper::isEnabled('loop', $attributes);
    }

    /**
     * Check if controls are enabled for the video.
     *
     * @param array $attributes The shortcode attributes.
     *
     * @return bool True if controls are enabled, false otherwise.
     */
    protected function getControls(array $attributes): bool
    {
        return AttributeHelper::isEnabled('controls', $attributes, true);
    }
}
