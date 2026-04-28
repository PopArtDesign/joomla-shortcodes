<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * A shortcode for embedding Vimeo videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Vimeo
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
        $videoId = $this->extractVimeoId($attributes[0] ?? '');

        if (!$videoId) {
            return '';
        }

        $width = $attributes['width'] ?? '560';
        $height = $attributes['height'] ?? '315';
        $class = $attributes['class'] ?? 'vimeo-container';
        $title = $attributes['title'] ?? 'Vimeo video player';
        $autoplay = isset($attributes['autoplay']) ? (int) $attributes['autoplay'] : 0;
        $loop = isset($attributes['loop']) ? (int) $attributes['loop'] : 0;

        $src = sprintf(
            'https://player.vimeo.com/video/%s?autoplay=%d&loop=%d',
            htmlspecialchars($videoId),
            $autoplay,
            $loop
        );

        return <<<HTML
<div class="{$class}-wrapper">
    <div class="{$class}">
        <iframe
            src="{$src}"
            width="{$width}"
            height="{$height}"
            title="{$title}"
            frameborder="0"
            allow="autoplay; fullscreen; picture-in-picture"
            allowfullscreen>
        </iframe>
    </div>
</div>
HTML;
    }

    /**
     * Extract the Vimeo video ID from a URL.
     *
     * @param string $url The Vimeo URL.
     *
     * @return string|null The video ID or null if not found.
     */
    private function extractVimeoId(string $url): ?string
    {
        $urlParts = parse_url($url);
        $path = $urlParts['path'] ?? '';

        if (preg_match('~/(\d+)~', $path, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
