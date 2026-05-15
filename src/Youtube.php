<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

\defined('_JEXEC') or die;

/**
 * Handles the `youtube` shortcode to embed YouTube videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class Youtube extends AbstractVideohostingHandler
{
    /**
     * @inheritdoc
     */
    protected function getEmbedUrl(string $url, array $attributes): ?string
    {
        $videoId = $this->getVideoId($url);

        $queryParams = [];

        $start = $this->getStart($attributes, 0);
        if ($start > 0) {
            $queryParams['start'] = $start;
        }

        $end = $this->getEnd($attributes);
        if ($end > 0) {
            $queryParams['end'] = $end;
        }

        $autoplay = $this->getAutoplay($attributes);
        if ($autoplay) {
            $queryParams['autoplay'] = 1;
        }

        $loop = $this->getLoop($attributes);
        if ($loop) {
            $queryParams['loop'] = 1;
            $queryParams['playlist'] = $videoId;
        }

        // Most browsers require videos to be muted for autoplay to work.
        if ($autoplay || $this->getMute($attributes)) {
            $queryParams['mute'] = 1;
        }

        $controls = $this->getControls($attributes);
        if (!$controls) { // YouTube default is to show controls, so only add if hiding them
            $queryParams['controls'] = 0;
        }

        $src = sprintf('https://www.youtube.com/embed/%s', htmlspecialchars($videoId));

        if (!empty($queryParams)) {
            $src .= '?' . http_build_query($queryParams);
        }

        return $src;
    }

    /**
     * Extracts the YouTube video ID from a given URL.
     *
     * @param string $url The YouTube video URL.
     *
     * @return string|null The extracted video ID.
     */
    protected function getVideoId(string $url): string
    {
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';

        if (\preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        $this->error('Could not extract video ID from URL.');
    }

    /**
     * @inheritdoc
     */
    protected function getIframeAttributes(array $attributes): array
    {
        return [
            'title' => 'YouTube video player',
            'allow' => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share',
        ];
    }
}
