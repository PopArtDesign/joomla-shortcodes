<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * Embed handler for YouTube videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Youtube implements EmbedInterface
{
    /**
     * Check if this handler supports the given URL.
     *
     * @param string $url The URL to check.
     *
     * @return bool True if the URL is a YouTube URL, false otherwise.
     */
    public function supports(string $url): bool
    {
        $urlParts = parse_url($url);
        $host = $urlParts['host'] ?? '';

        if ($host !== '') {
            $host = strtolower($host);
            return in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be'], true);
        }

        $path = $urlParts['path'] ?? $url;
        $firstSegment = strtok($path, '/');
        return in_array($firstSegment, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be'], true);
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
        $videoId = $url;

        if (strpos($videoId, 'youtu') !== false) {
            $url = $videoId;
            if (strpos($url, 'http') !== 0) {
                $url = 'https://' . $url;
            }

            $urlParts = parse_url($url);

            if ($urlParts && isset($urlParts['host'])) {
                $host = strtolower($urlParts['host']);
                $path = $urlParts['path'] ?? '';

                if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
                    if (isset($urlParts['query'])) {
                        parse_str($urlParts['query'], $query);
                        if (isset($query['v'])) {
                            $videoId = $query['v'];
                        }
                    } elseif (strpos($path, '/embed/') === 0) {
                        $videoId = substr($path, strlen('/embed/'));
                    }
                } elseif ($host === 'youtu.be') {
                    $videoId = ltrim($path, '/');
                }
            }
        }

        if (!$videoId) {
            return '';
        }

        $videoId = strtok($videoId, '?');

        $start = $attributes['start'] ?? '0';
        $startSeconds = $start;
        $startParts = explode(':', $startSeconds);
        if (count($startParts) == 2) {
            $startSeconds = (int) $startParts[0] * 60 + (int) $startParts[1];
        }

        // Save original values before rebuild
        $originalWidth = $attributes['width'] ?? null;
        $originalHeight = $attributes['height'] ?? null;
        $originalTitle = $attributes['title'] ?? null;
        $originalClass = $attributes['class'] ?? null;
        $originalAllow = $attributes['allow'] ?? null;
        $originalFrameborder = $attributes['frameborder'] ?? null;
        $originalAllowfullscreen = $attributes['allowfullscreen'] ?? null;
        $startValue = $attributes['start'] ?? '0';

        // Rebuild attributes array in correct order
        $attributes = [
            'width' => $originalWidth ?? '560',
            'height' => $originalHeight ?? '315',
            'title' => $originalTitle ?? 'YouTube video player',
            'frameborder' => $originalFrameborder ?? 0,
            'allowfullscreen' => $originalAllowfullscreen ?? '',
        ];

        // Only include start if it's non-default (in the correct position)
        if ($startValue !== '0') {
            $attributes['start'] = $startValue;
        }

        $attributes['class'] = $originalClass ?? 'youtube-container';
        $attributes['allow'] = $originalAllow ?? 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
        $attributes['referrerpolicy'] = 'strict-origin-when-cross-origin';

        $src = sprintf('https://www.youtube.com/embed/%s?start=%d', htmlspecialchars($videoId), (int) $startSeconds);

        return Iframe::render($src, $attributes);
    }
}
