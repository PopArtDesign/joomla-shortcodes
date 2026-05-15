<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HandlerHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HtmlHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\AbstractShortcodeHandler;

\defined('_JEXEC') or die;

/**
 * Handles the `gist` shortcode to embed Gist snippets from GitHub.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class Gist extends AbstractShortcodeHandler
{
    /**
     * The main shortcode invokation method.
     *
     * @param array  $attributes The shortcode attributes.
     * @param string $content    The content between shortcode tags.
     *
     * @return string The full HTML output for the embed.
     */
    protected function process(array $attributes, string $content): string
    {
        $parsedUrl = AttributeHelper::getAbsoluteUrl($attributes, $content);
        if ($parsedUrl === null) {
            $this->error('A valid URL was not found.');
        }

        if (!$parsedUrl->hasDomain('gist.github.com')) {
            $this->error('The provided URL is not a valid Gist URL.');
        }

        if (!\preg_match('/^\/([a-zA-Z0-9_-]+)\/([a-f0-9]+)$/', $parsedUrl->getPath(), $matches)) {
            $this->error('The provided Gist URL path is invalid. Expected format: username/gistid.');
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
