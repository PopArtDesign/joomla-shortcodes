<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

/**
 * Embed handler for YouTube videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Youtube extends AbstractEmbedHandler
{
    protected function getSupportedHosts(): array
    {
        return ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be'];
    }

    /**
     * {@inheritdoc}
     */
    public function process(string $url, array $attributes): string
    {
        $videoId = $this->getVideoId($url);

        if (!$videoId) {
            return '';
        }

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

        $src = sprintf('https://www.youtube.com/embed/%s?%s', htmlspecialchars($videoId), http_build_query($queryParams));

        $iframeAttributes = [
            'width' => $attributes['width'] ?? '100%',
            'height' => $attributes['height'] ?? 'auto',
            'title' => $attributes['title'] ?? 'YouTube video player',
            'frameborder' => $attributes['frameborder'] ?? '0',
            'allowfullscreen' => $attributes['allowfullscreen'] ?? '',
            'allow' => $attributes['allow'] ?? 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share',
            'referrerpolicy' => 'strict-origin-when-cross-origin',
        ];

        $wrapperStyles = [];

        if (($attributes['height'] ?? 'auto') === 'auto') {
            $aspectRatio = $attributes['aspect-ratio'] ?? '16 / 9';
            $iframeAttributes['aspect-ratio'] = 'var(--embed-aspect-ratio)';
            $wrapperStyles[] = '--embed-aspect-ratio: ' . htmlspecialchars($aspectRatio);
        }

        $html = Iframe::render($src, $iframeAttributes);
        $class = htmlspecialchars($attributes['class'] ?? 'youtube-container', ENT_QUOTES, 'UTF-8');

        $styleAttr = '';
        if (!empty($wrapperStyles)) {
            $styleAttr = ' style="' . implode('; ', $wrapperStyles) . '"';
        }

        return sprintf('<div class="%s"%s>%s</div>', $class, $styleAttr, $html);
    }

    /**
     * Extract YouTube video ID from a URL or a string.
     *
     * @param string $url
     *
     * @return ?string
     */
    private function getVideoId(string $url): ?string
    {
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
