<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

\defined('_JEXEC') or die;

/**
 * Embed handler for Vimeo videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Vimeo extends AbstractVideoEmbedHandler
{
    protected function getSupportedHosts(): array
    {
        return ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'];
    }

    protected function getEmbedSpecificClass(): string
    {
        return 'embed-vimeo';
    }

    protected function getEmbedUrl(string $videoId, array $attributes): string
    {
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

        return $src;
    }

    protected function getVideoId(string $url): ?string
    {
        $pattern = '/(?:vimeo\.com\/|player\.vimeo\.com\/video\/)(\d+)/';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function getDefaultTitle(): string
    {
        return 'Vimeo video player';
    }

    protected function getDefaultClass(): string
    {
        return 'vimeo-container';
    }

    protected function getDefaultAllow(): string
    {
        return 'autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share';
    }
}
