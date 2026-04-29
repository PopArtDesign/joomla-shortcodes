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

    protected function getEmbedUrl(string $videoId, array $attributes): string
    {
        $fragment = [];

        $start = $this->getStart($attributes, 0);
        if ($start > 0) {
            $fragment[] = 't=' . $start . 's';
        }

        $end = $this->getEnd($attributes);
        if ($end !== null) {
            $fragment[] = 'end=' . $end;
        }

        $autoplay = $this->getAutoplay($attributes);
        $loop = AttributeHelper::isEnabled('loop', $attributes);

        $src = sprintf(
            'https://player.vimeo.com/video/%s?autoplay=%d&loop=%d',
            htmlspecialchars($videoId),
            (int) $autoplay,
            (int) $loop,
        );

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

    protected function getDefaults(): array
    {
        return [
            'title' => 'Vimeo video player',
            'class' => 'embed-vimeo',
            'allow' => 'autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share',
        ];
    }
}
