<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

abstract class AbstractVideoEmbedHandler extends AbstractEmbedHandler
{
    abstract protected function getVideoId(string $url): ?string;

    abstract protected function getEmbedUrl(string $videoId, array $attributes): string;

    abstract protected function getEmbedSpecificClass(): string;

    protected function getDefaultTitle(): string
    {
        return 'Video player';
    }

    protected function getDefaultClass(): string
    {
        return 'video-container';
    }

    protected function getDefaultAllow(): string
    {
        return 'autoplay';
    }

    protected function getDefaultReferrerPolicy(): string
    {
        return 'strict-origin-when-cross-origin';
    }

    protected function buildIframeAttributes(array $attributes): array
    {
        return [
            'width' => $attributes['width'] ?? '100%',
            'height' => $attributes['height'] ?? 'auto',
            'title' => $attributes['title'] ?? $this->getDefaultTitle(),
            'frameborder' => $attributes['frameborder'] ?? '0',
            'allowfullscreen' => $attributes['allowfullscreen'] ?? '',
            'allow' => $attributes['allow'] ?? $this->getDefaultAllow(),
            'referrerpolicy' => $attributes['referrerpolicy'] ?? $this->getDefaultReferrerPolicy(),
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

    public function process(string $url, array $attributes): string
    {
        $videoId = $this->getVideoId($url);

        if (!$videoId) {
            return '';
        }

        $src = $this->getEmbedUrl($videoId, $attributes);
        $iframeAttributes = $this->buildIframeAttributes($attributes);

        $wrapperStyles = [];

        if (($attributes['height'] ?? 'auto') === 'auto') {
            $aspectRatio = $attributes['aspect-ratio'] ?? '16 / 9';
            $iframeAttributes['aspect-ratio'] = 'var(--embed-aspect-ratio)';
            $wrapperStyles[] = '--embed-aspect-ratio: ' . \htmlspecialchars($aspectRatio);
        }

        $html = Iframe::render($src, $iframeAttributes);

        $baseClasses = ['embed-container', 'embed-video'];
        $specificClass = $this->getEmbedSpecificClass();
        if ($specificClass) {
            $baseClasses[] = $specificClass;
        }

        return $this->renderWrapper($html, $baseClasses, $attributes, $wrapperStyles);
    }
}
