<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HtmlHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HandlerHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\UrlHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Value\ParsedUrl;

\defined('_JEXEC') or die;

/**
 * Handles the `telegram` shortcode to embed posts from Telegram.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class Telegram
{
    /**
     * The main shortcode invokation method.
     *
     * @param array  $attributes The shortcode attributes.
     * @param string $content    The content between shortcode tags, used as a fallback for the URL.
     *
     * @return string The full HTML output for the Twitter embed.
     */
    public function __invoke(array $attributes, string $content): string
    {
        $parsedUrl = AttributeHelper::getAbsoluteUrl($attributes, $content);
        if ($parsedUrl === null) {
            return HandlerHelper::error('Telegram: A valid URL was not found.');
        }

        $url = (string) $parsedUrl;
        if (!($this->isTelegramUrl($url) && $telegramPost = $this->getTelegramPost($parsedUrl))) {
            return HandlerHelper::error('Telegram: The provided URL is not a valid Telegram post URL.');
        }

        $scriptAttributes = [
            'async' => true,
            'src' => 'https://telegram.org/js/telegram-widget.js?23',
            'data-telegram-post' => $telegramPost,
            'data-width' => '100%',
        ];

        $output = HtmlHelper::script('', $scriptAttributes);

        return HandlerHelper::wrapper($output, $attributes, [
            'class' => 'embed-container embed-telegram'
        ]);
    }

    private function getTelegramPost(ParsedUrl $parsedUrl): ?string
    {
        $path = $parsedUrl->getPath();
        $path = trim($path, '/');
        $parts = explode('/', $path);

        if (count($parts) !== 2) {
            return null;
        }

        return $parts[0] . '/' . $parts[1];
    }

    private function isTelegramUrl(string $url): bool
    {
        return (bool) preg_match('/^(https?:\/\/)?(www\.)?t\.me\/.+\/\d+$/', $url);
    }
}
