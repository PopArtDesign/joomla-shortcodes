<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HtmlHelper;

\defined('_JEXEC') or die;

/**
 * Handles the `gist` shortcode to embed Gist snippets from GitHub.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Gist extends AbstractEmbedHandler
{
    /**
     * @inheritdoc
     */
    protected function processEmbed(string $url, array $attributes): string
    {
        if (\parse_url($url, PHP_URL_HOST) !== 'gist.github.com') {
            throw new \InvalidArgumentException('The provided URL is not a valid Gist URL.');
        }

        $scriptUrl = \trim($url, '/') . '.js';

        if ($file = ($attributes['file'] ?? '')) {
            $scriptUrl .= '?file=' . \urlencode($file);
        }

        return HtmlHelper::script($scriptUrl);
    }

    /**
     * @inheritdoc
     */
    protected function getWrapperAttributes(array $attributes): array
    {
        return ['class' => 'embed-gist'];
    }
}
