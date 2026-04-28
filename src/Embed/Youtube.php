<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

class Youtube implements EmbedInterface
{
    private IframeRenderer $iframeRenderer;

    public function __construct()
    {
        $this->iframeRenderer = new IframeRenderer();
    }

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

        $start = $attributes['start'] ?? '0';
        $startParts = explode(':', $start);
        if (count($startParts) == 2) {
            $start = (int) $startParts[0] * 60 + (int) $startParts[1];
        }

        $attributes['width'] = $attributes['width'] ?? '560';
        $attributes['height'] = $attributes['height'] ?? '315';
        $attributes['title'] = $attributes['title'] ?? 'YouTube video player';
        $attributes['class'] = $attributes['class'] ?? 'youtube-container';
        $attributes['allow'] = $attributes['allow'] ?? 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
        $attributes['referrerpolicy'] = 'strict-origin-when-cross-origin';

        $src = sprintf('https://www.youtube.com/embed/%s?start=%d', htmlspecialchars($videoId), (int) $start);

        return $this->iframeRenderer->render($src, $attributes);
    }
}
