<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HandlerHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HtmlHelper;

\defined('_JEXEC') or die;

/**
 * Handles the `gist` shortcode to embed Gist snippets from GitHub.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Gist
{
    /**
     * The main shortcode invokation method.
     *
     * @param array  $attributes The shortcode attributes.
     * @param string $content    The content between shortcode tags.
     *
     * @return string The full HTML output for the embed.
     */
    public function __invoke(array $attributes, string $content): string
    {
        $url = AttributeHelper::getUrl($attributes, $content);

        if (\parse_url($url, PHP_URL_HOST) !== 'gist.github.com') {
            throw new \InvalidArgumentException('The provided URL is not a valid Gist URL.');
        }

        $scriptUrl = \trim($url, '/') . '.js';

        if ($file = ($attributes['file'] ?? '')) {
            $scriptUrl .= '?file=' . \urlencode($file);
        }

        $output = HtmlHelper::script($scriptUrl);

        return HandlerHelper::wrapper($output, $attributes, [
            'class' => 'embed-gist'
        ]);
    }
}
