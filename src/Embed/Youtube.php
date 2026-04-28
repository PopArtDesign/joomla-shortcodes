<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

class Youtube implements EmbedInterface
{
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
