<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

\defined('_JEXEC') or die;

/**
 * Handles the `vimeo` shortcode to embed Vimeo videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Vimeo extends AbstractVideohostingHandler
{
    /**
     * @inheritdoc
     */
    protected function getEmbedUrl(string $url, array $attributes): string
    {
        $videoId = $this->getVideoId($url);

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

    /**
     * Extracts the Vimeo video ID from a given URL.
     *
     * @param string $url The Vimeo video URL.
     *
     * @return string The extracted video ID.
     *
     * @throws \InvalidArgumentException If the video ID cannot be extracted.
     */
    protected function getVideoId(string $url): string
    {
        $pattern = '/(?:vimeo\.com\/|player\.vimeo\.com\/video\/)(\d+)/';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        throw new \InvalidArgumentException('Could not extract Vimeo video ID from URL: ' . $url);
    }

    /**
     * @inheritdoc
     */
    protected function getIframeAttributes(array $attributes): array
    {
        return \array_merge(parent::getIframeAttributes($attributes), [
            'title' => 'Vimeo video player',
            'allow' => 'autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share',
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function getWrapperAttributes(array $attributes): array
    {
        $wrapperAttributes = parent::getWrapperAttributes($attributes);
        $wrapperAttributes['class'] = \trim(($wrapperAttributes['class'] ?? '') . ' embed-vimeo');

        return $wrapperAttributes;
    }
}
