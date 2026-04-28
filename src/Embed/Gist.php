<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * Embed handler for GitHub Gists.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Gist implements EmbedInterface
{
    /**
     * Check if this handler supports the given URL.
     *
     * @param string $url The URL to check.
     *
     * @return bool True if the URL is a GitHub Gist URL, false otherwise.
     */
    public function supports(string $url): bool
    {
        if (strpos($url, '://') === false) {
            return false;
        }

        $urlParts = parse_url($url);
        $host = $urlParts['host'] ?? '';

        return strtolower($host) === 'gist.github.com';
    }

    /**
     * Process the given URL and return the embed HTML.
     *
     * @param string $url        The URL to process.
     * @param array  $attributes The shortcode attributes.
     *
     * @return string The embed HTML.
     */
    public function process(string $url, array $attributes): string
    {
        $idOrUrl = $url;
        $file = $attributes['file'] ?? '';

        if (!$idOrUrl) {
            return '';
        }

        if (\strpos($idOrUrl, 'https://gist.github.com/') !== 0) {
            return '';
        }

        $scriptUrl = \rtrim($idOrUrl, '/') . '.js';

        if ($file) {
            $scriptUrl .= '?file=' . \urlencode($file);
        }

        return \sprintf('<script src="%s"></script>', $scriptUrl);
    }
}
