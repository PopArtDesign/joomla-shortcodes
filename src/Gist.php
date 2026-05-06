<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HandlerHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HtmlHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Value\ParsedUrl;

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
        $parsedUrl = AttributeHelper::getAbsoluteUrl($attributes, $content);

        if (!$parsedUrl->hasDomain('gist.github.com')) {
            throw new \InvalidArgumentException('The provided URL is not a valid Gist URL.');
        }

        if (!\preg_match('/^\/([a-zA-Z0-9_-]+)\/([a-f0-9]+)$/', $parsedUrl->path, $matches)) {
            throw new \InvalidArgumentException('The provided Gist URL path is invalid. Expected format: /username/gist_id.');
        }

        $username = $matches[1];
        $gistId = $matches[2];

        $scriptUrl = \sprintf(
            'https://gist.github.com/%s/%s.js',
            $username,
            $gistId,
        );

        if ($file = ($attributes['file'] ?? '')) {
            $scriptUrl .= '?file=' . \urlencode($file);
        }

        $output = HtmlHelper::script($scriptUrl);

        return HandlerHelper::wrapper($output, $attributes, [
            'class' => 'embed-container embed-gist'
        ]);
    }
}
