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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function process(string $url, array $attributes): string
    {
        $videoId = $this->getVideoId($url);

        if (!$videoId) {
            return '';
        }

        $start = $attributes['start'] ?? '0';
        $startSeconds = AttributeHelper::parseTime($start, 0);

        $autoplay = AttributeHelper::isEnabled('autoplay', $attributes);

        $queryParams = [
            'start' => $startSeconds,
        ];

        if ($autoplay) {
            $queryParams['autoplay'] = 1;
            // Most browsers require videos to be muted for autoplay to work.
            $queryParams['mute'] = 1;
        }

        $src = sprintf('https://www.youtube.com/embed/%s?%s', htmlspecialchars($videoId), http_build_query($queryParams));

        $iframeAttributes = [
            'width' => $attributes['width'] ?? '100%',
            'height' => $attributes['height'] ?? 'auto',
            'title' => $attributes['title'] ?? 'YouTube video player',
            'frameborder' => $attributes['frameborder'] ?? '0',
            'allowfullscreen' => $attributes['allowfullscreen'] ?? '',
            'allow' => $attributes['allow'] ?? 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share',
            'referrerpolicy' => 'strict-origin-when-cross-origin',
        ];

        if (($attributes['height'] ?? 'auto') === 'auto') {
            $iframeAttributes['aspect-ratio'] = $attributes['aspect-ratio'] ?? '16 / 9';
        }

        $html = Iframe::render($src, $iframeAttributes);
        $class = htmlspecialchars($attributes['class'] ?? 'youtube-container', ENT_QUOTES, 'UTF-8');

        return sprintf('<div class="%s">%s</div>', $class, $html);
    }

    /**
     * Extract YouTube video ID from a URL or a string.
     *
     * @param string $url
     *
     * @return ?string
     */
    private function getVideoId(string $url): ?string
    {
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
