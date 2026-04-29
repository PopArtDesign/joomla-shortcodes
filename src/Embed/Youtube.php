<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * Embed handler for YouTube videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Youtube extends AbstractVideoEmbedHandler
{
    protected function getSupportedHosts(): array
    {
        return ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be'];
    }

    protected function getEmbedSpecificClass(): string
    {
        return 'embed-youtube';
    }

    protected function getEmbedUrl(string $videoId, array $attributes): string
    {
        $start = $this->getStart($attributes, 0);

        $autoplay = $this->getAutoplay($attributes);

        $queryParams = [
            'start' => $start,
        ];

        if ($autoplay) {
            $queryParams['autoplay'] = 1;
            // Most browsers require videos to be muted for autoplay to work.
            $queryParams['mute'] = 1;
        }

        return sprintf('https://www.youtube.com/embed/%s?%s', htmlspecialchars($videoId), http_build_query($queryParams));
    }

    protected function getVideoId(string $url): ?string
    {
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function getDefaults(): array
    {
        return array_merge(parent::getDefaults(), [
            'title' => 'YouTube video player',
            'class' => 'youtube-container',
            'allow' => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share',
        ]);
    }
}
