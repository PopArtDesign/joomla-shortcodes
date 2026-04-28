<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

\defined('_JEXEC') or die;

/**
 * Embed handler for Vimeo videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Vimeo implements EmbedInterface
{
    private const SUPPORTED_HOSTS = ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'];

    /**
     * {@inheritdoc}
     */
    public function supports(string $url): bool
    {
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

        $start = null;
        if (isset($attributes['start'])) {
            $start = AttributeHelper::parseTime($attributes['start']);
        }

        $end = null;
        if (isset($attributes['end'])) {
            $end = AttributeHelper::parseTime($attributes['end']);
        }

        $autoplay = AttributeHelper::isEnabled('autoplay', $attributes);
        $loop = AttributeHelper::isEnabled('loop', $attributes);

        $src = sprintf(
            'https://player.vimeo.com/video/%s?autoplay=%d&loop=%d',
            htmlspecialchars($videoId),
            (int) $autoplay,
            (int) $loop,
        );

        $fragment = [];
        if ($start !== null) {
            $fragment[] = 't=' . $start . 's';
        }

        if ($end !== null) {
            $fragment[] = 'end=' . $end;
        }

        if (!empty($fragment)) {
            $src .= '#' . implode('&', $fragment);
        }

        $iframeAttributes = [
            'width' => $attributes['width'] ?? '100%',
            'height' => $attributes['height'] ?? 'auto',
            'title' => $attributes['title'] ?? 'Vimeo video player',
            'frameborder' => $attributes['frameborder'] ?? '0',
            'allowfullscreen' => $attributes['allowfullscreen'] ?? '',
            'class' => $attributes['class'] ?? 'vimeo-container',
            'allow' => $attributes['allow'] ?? 'autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share',
            'referrerpolicy' => $attributes['referrerpolicy'] ?? 'strict-origin-when-cross-origin',
        ];

        if (($attributes['height'] ?? 'auto') === 'auto') {
            $iframeAttributes['aspect-ratio'] = $attributes['aspect-ratio'] ?? '16 / 9';
        }

        return Iframe::render($src, $iframeAttributes);
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
        $pattern = '/(?:vimeo\.com\/|player\.vimeo\.com\/video\/)(\d+)/';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
