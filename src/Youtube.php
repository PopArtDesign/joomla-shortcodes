<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

\defined('_JEXEC') or die;

/**
 * A shortcode for embedding YouTube videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Youtube
{
    /**
     * Invoke the shortcode.
     *
     * @param array $attributes The attributes of the shortcode.
     *
     * @return string
     */
    public function __invoke(array $attributes): string
    {
        $videoId = $attributes[0] ?? '';

        // Check if the input is a URL and parse it
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

        // Clean up video ID from any potential leftover query parameters from short URLs
        $videoId = strtok($videoId, '?');

        $width   = $attributes['width'] ?? '560';
        $height  = $attributes['height'] ?? '315';
        $start   = $attributes['start'] ?? '0';
        $allow   = $attributes['allow'] ?? 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
        $title   = $attributes['title'] ?? 'YouTube video player';
        $class   = $attributes['class'] ?? 'youtube-container';

        $startParts = explode(':', $start);
        if (count($startParts) == 2) {
            $start = (int) $startParts[0] * 60 + (int) $startParts[1];
        }

        $src = sprintf('https://www.youtube.com/embed/%s?start=%d', htmlspecialchars($videoId), (int) $start);

        return <<<HTML
<div class="{$class}">
    <iframe
        src="{$src}"
        width="{$width}"
        height="{$height}"
        allow="{$allow}"
        title="{$title}"
        referrerpolicy="strict-origin-when-cross-origin"
        frameborder="0"
        allowfullscreen>
    </iframe>
</div>
HTML;
    }
}
