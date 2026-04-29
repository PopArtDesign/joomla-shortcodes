<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

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

    protected function getEmbedSpecificClass(): string
    {
        return 'embed-rutube';
    }

    protected function getEmbedUrl(string $videoId, array $attributes): string
    {
        $autoplay = AttributeHelper::isEnabled('autoplay', $attributes);
        $queryParams = [];
        if ($autoplay) {
            $queryParams['autoplay'] = 'true';
            $queryParams['autostartmute'] = 'true';
        }

        $start = $attributes['start'] ?? '0';
        $startSeconds = AttributeHelper::parseTime($start, 0);
        if ($startSeconds > 0) {
            $queryParams['t'] = $startSeconds;
        }

        if (isset($attributes['end'])) {
            $endSeconds = AttributeHelper::parseTime($attributes['end'], 0);
            if ($endSeconds > 0) {
                $queryParams['stopTime'] = $endSeconds;
            }
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

    protected function getDefaultTitle(): string
    {
        return 'Rutube video player';
    }

    protected function getDefaultClass(): string
    {
        return 'rutube-container';
    }

    protected function getDefaultAllow(): string
    {
        return 'clipboard-write; autoplay';
    }
}
