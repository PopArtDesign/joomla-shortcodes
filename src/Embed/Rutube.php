<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * Embed handler for Rutube videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Rutube extends AbstractVideoEmbedHandler
{
    protected function getSupportedHosts(): array
    {
        return ['rutube.ru', 'www.rutube.ru'];
    }

    protected function getEmbedUrl(string $videoId, array $attributes): string
    {
        $queryParams = [];

        $autoplay = $this->getAutoplay($attributes);
        if ($autoplay) {
            $queryParams['autoplay'] = 'true';
            $queryParams['autostartmute'] = 'true';
        }

        $start = $this->getStart($attributes, 0);
        if ($start > 0) {
            $queryParams['t'] = $start;
        }

        $end = $this->getEnd($attributes);
        if ($end > 0) {
            $queryParams['stopTime'] = $end;
        }

        $src = sprintf('https://rutube.ru/play/embed/%s', htmlspecialchars($videoId));

        if (!empty($queryParams)) {
            $src .= '?' . http_build_query($queryParams);
        }

        return $src;
    }

    protected function getVideoId(string $url): ?string
    {
        $pattern = '/rutube\.ru\/(?:video|pl(?:\/[a-zA-Z0-9_-]+)?)\/([a-zA-Z0-9_-]+)/';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function getDefaults(): array
    {
        return [
            'title' => 'Rutube video player',
            'class' => 'embed-rutube',
            'allow' => 'clipboard-write; autoplay',
        ];
    }
}
