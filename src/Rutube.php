<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

\defined('_JEXEC') or die;

/**
 * Handles the `rutube` shortcode to embed Rutube videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class Rutube extends AbstractVideohostingHandler
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
        }

        if ($this->getMute($attributes) || $autoplay) {
            $queryParams['mute'] = 'true';
        }

        $start = $this->getStart($attributes, 0);
        if ($start > 0) {
            $queryParams['t'] = $start;
        }

        $end = $this->getEnd($attributes);
        if ($end > 0) {
            $queryParams['stopTime'] = $end;
        }

        $loop = $this->getLoop($attributes);
        if ($loop) {
            // Note: Rutube's embed API documentation for a 'loop' parameter is not explicit.
            // We are adding 'loop=true' for API consistency, assuming the player might
            // respond to it or for future compatibility.
            $queryParams['loop'] = 'true';
        }

        $controls = $this->getControls($attributes);
        if (!$controls) { // Rutube player default is assumed to be show controls, only add if hiding them
            // Note: Rutube's embed API documentation for a 'controls' parameter is not explicit.
            // We are adding 'controls=false' for API consistency, assuming the player might
            // respond to it or for future compatibility.
            $queryParams['controls'] = 'false';
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
        return [
            'title' => 'Rutube video player',
            'allow' => 'clipboard-write; autoplay',
        ];
    }
}
