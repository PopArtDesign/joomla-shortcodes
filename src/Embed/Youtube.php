<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

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

    protected function getEmbedUrl(string $videoId, array $attributes): string
    {
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

    protected function getDefaultTitle(): string
    {
        return 'YouTube video player';
    }

    protected function getDefaultClass(): string
    {
        return 'youtube-container';
    }

    protected function getDefaultAllow(): string
    {
        return 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
    }
}
