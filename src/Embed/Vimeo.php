<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * Embed handler for Vimeo videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Vimeo implements EmbedInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(string $url): bool
    {
        $urlParts = parse_url($url);
        $host = strtolower($urlParts['host'] ?? '');
        return in_array($host, ['vimeo.com', 'www.vimeo.com'], true);
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

        $attributes['width'] = $attributes['width'] ?? '560';
        $attributes['height'] = $attributes['height'] ?? '315';
        $attributes['title'] = $attributes['title'] ?? 'Vimeo video player';
        $attributes['class'] = $attributes['class'] ?? 'vimeo-container';
        $attributes['frameborder'] = $attributes['frameborder'] ?? 0;
        $attributes['allowfullscreen'] = $attributes['allowfullscreen'] ?? '';

        $autoplay = isset($attributes['autoplay']) ? (int) $attributes['autoplay'] : 0;
        $loop = isset($attributes['loop']) ? (int) $attributes['loop'] : 0;

        $attributes['allow'] = 'autoplay; fullscreen; picture-in-picture';

        $src = sprintf(
            'https://player.vimeo.com/video/%s?autoplay=%d&loop=%d',
            htmlspecialchars($videoId),
            $autoplay,
            $loop
        );

        return Iframe::render($src, $attributes);
    }

    /**
     * Extract the Vimeo video ID from the given URL.
     *
     * @param string $url The Vimeo URL.
     *
     * @return string|null The video ID, or null if not found.
     */
    private function getVideoId(string $url): ?string
    {
        $urlParts = parse_url($url);
        $path = $urlParts['path'] ?? '';

        if (preg_match('~/(\d+)~', $path, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
