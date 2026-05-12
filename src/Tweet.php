<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HtmlHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HandlerHelper;

\defined('_JEXEC') or die;

/**
 * Handles the `tweet` shortcode to embed posts from X (formerly Twitter).
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class Tweet
{
    /**
     * The main shortcode invokation method.
     *
     * @param array  $attributes The shortcode attributes.
     * @param string $content    The content between shortcode tags, used as a fallback for the URL.
     *
     * @return string The full HTML output for the Twitter embed.
     *
     * @throws \InvalidArgumentException If a valid URL is not provided.
     */
    public function __invoke(array $attributes, string $content): string
    {
        $url = (string) AttributeHelper::getAbsoluteUrl($attributes, $content);

        if (!$this->isTwitterUrl($url)) {
            throw new \InvalidArgumentException('The provided URL is not a valid Twitter/X post URL.');
        }

        $anchor = HtmlHelper::tag('a', ['href' => $url], '');
        $blockquote = HtmlHelper::tag('blockquote', ['class' => 'twitter-tweet'], $anchor);
        $script = HtmlHelper::script('https://platform.twitter.com/widgets.js', ['async' => true, 'charset' => 'utf-8']);

        $output = $blockquote . $script;

        $baseWrapperAttributes = [
            'class' => 'embed-container embed-tweet',
        ];

        return HandlerHelper::wrapper($output, $attributes, $baseWrapperAttributes);
    }

    /**
     * Checks if a URL is a valid Twitter/X status URL.
     *
     * @param string $url The URL to check.
     *
     * @return bool True if the URL is a valid Twitter/X URL, false otherwise.
     */
    private function isTwitterUrl(string $url): bool
    {
        return (bool) preg_match('/^(https?:\/\/)?(www\.)?(twitter|x)\.com\/.+\/status\/\d+$/', $url);
    }
}
