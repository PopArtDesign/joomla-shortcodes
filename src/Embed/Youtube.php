<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

/**
 * Embed handler for YouTube videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Youtube implements EmbedInterface
{
    private const SUPPORTED_HOSTS = ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be'];

    /**
     * Check if this handler supports the given URL.
     *
     * @param string $url The URL to check.
     *
     * @return bool True if the URL is a YouTube URL, false otherwise.
     */
    public function supports(string $url): bool
    {
        // Add a scheme if missing to help parse_url.
        // This is to correctly extract the host from URLs that might be protocol-relative or missing protocol.
        if (!preg_match('~^https?://~', $url)) {
            $url = 'https://' . $url;
        }

        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');

        return in_array($host, self::SUPPORTED_HOSTS, true);
    }

    /**
     * Process the given URL and return the embed HTML.
     *
     * @param string $url        The URL to process.
     * @param array  $attributes The shortcode attributes.
     *
     * @return string The embed HTML.
     */
    public function process(string $url, array $attributes): string
    {
        $videoId = $this->getVideoId($url);

        if (!$videoId) {
            return '';
        }

        $start = $attributes['start'] ?? '0';
        $startSeconds = AttributeHelper::parseTime($start);

        $src = sprintf('https://www.youtube.com/embed/%s?start=%d', htmlspecialchars($videoId), $startSeconds);

        $iframeAttributes = [
            'width' => $attributes['width'] ?? '560',
            'height' => $attributes['height'] ?? '315',
            'title' => $attributes['title'] ?? 'YouTube video player',
            'frameborder' => $attributes['frameborder'] ?? '0',
            'allowfullscreen' => $attributes['allowfullscreen'] ?? '',
        ];

        // The original code passed 'start' as an iframe attribute if not '0'.
        // Keeping this behavior for backward compatibility, although it's not a standard iframe attribute.
        if ($start !== '0' && isset($attributes['start'])) {
            $iframeAttributes['start'] = $attributes['start'];
        }

        $iframeAttributes['class'] = $attributes['class'] ?? 'youtube-container';
        $iframeAttributes['allow'] = $attributes['allow'] ?? 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
        $iframeAttributes['referrerpolicy'] = 'strict-origin-when-cross-origin';

        return Iframe::render($src, $iframeAttributes);
    }

    /**
     * Extract YouTube video ID from a URL or a string.
     *
     * @param string $url
     *
     * @return string
     */
    private function getVideoId(string $url): string
    {
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        // Fallback for cases where just the video ID is provided.
        // It's assumed to be the video ID if no YouTube URL pattern matches.
        return strtok($url, '?');
    }
}
