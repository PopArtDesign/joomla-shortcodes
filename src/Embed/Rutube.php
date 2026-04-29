<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

/**
 * Embed handler for Rutube videos.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Rutube extends AbstractEmbedHandler
{
    protected function getSupportedHosts(): array
    {
        return ['rutube.ru', 'www.rutube.ru'];
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

        $iframeAttributes = [
            'width' => $attributes['width'] ?? '100%',
            'height' => $attributes['height'] ?? 'auto',
            'frameborder' => $attributes['frameborder'] ?? '0',
            'allow' => $attributes['allow'] ?? 'clipboard-write; autoplay',
            'allowfullscreen' => $attributes['allowfullscreen'] ?? '',
        ];

        if (($iframeAttributes['height']) === 'auto') {
            $iframeAttributes['aspect-ratio'] = $attributes['aspect-ratio'] ?? '16 / 9';
        }

        $html = Iframe::render($src, $iframeAttributes);
        $class = htmlspecialchars($attributes['class'] ?? 'rutube-container', ENT_QUOTES, 'UTF-8');

        return sprintf('<div class="%s">%s</div>', $class, $html);
    }

    /**
     * Extract Rutube video ID from a URL or a string.
     *
     * @param string $url
     *
     * @return ?string
     */
    private function getVideoId(string $url): ?string
    {
        $pattern = '/rutube\.ru\/(?:video|pl(?:\/[a-zA-Z0-9_-]+)?)\/([a-zA-Z0-9_-]+)/';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
