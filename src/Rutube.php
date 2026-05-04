<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

\defined('_JEXEC') or die;

/**
 * Handles the `rutube` shortcode to embed Rutube videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Rutube extends AbstractVideohostingHandler
{
    /**
     * @inheritdoc
     */
    protected function getEmbedUrl(string $url, array $attributes): string
    {
        $videoId = $this->getVideoId($url);

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

    /**
     * Extracts the Rutube video ID from a given URL.
     *
     * @param string $url The Rutube video URL.
     *
     * @return string The extracted video ID.
     *
     * @throws \InvalidArgumentException If the video ID cannot be extracted.
     */
    protected function getVideoId(string $url): string
    {
        $pattern = '/rutube\.ru\/(?:video|pl(?:\/[a-zA-Z0-9_-]+)?)\/([a-zA-Z0-9_-]+)/';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        throw new \InvalidArgumentException('Could not extract Rutube video ID from URL: ' . $url);
    }

    /**
     * @inheritdoc
     */
    protected function getIframeAttributes(array $attributes): array
    {
        return \array_merge(parent::getIframeAttributes($attributes), [
            'title' => 'Rutube video player',
            'allow' => 'clipboard-write; autoplay',
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function getWrapperAttributes(array $attributes): array
    {
        $wrapperAttributes = parent::getWrapperAttributes($attributes);
        $wrapperAttributes['class'] = \trim(($wrapperAttributes['class'] ?? '') . ' embed-rutube');

        return $wrapperAttributes;
    }
}
