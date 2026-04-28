<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Youtube;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Gist;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Vimeo;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Iframe;

\defined('_JEXEC') or die;

/**
 * A shortcode for embedding remote resources.
 * Detects URL type and delegates to specific handlers (YouTube, Gist, Vimeo, etc.).
 * Falls back to iframe for generic URLs.
 *
 * Usage:
 *   {embed}https://youtube.com/watch?v=xxx{/embed}
 *   {embed url="https://gist.github.com/user/12345"}
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Embed
{
    /**
     * Invoke the shortcode.
     *
     * @param array  $attributes The attributes of the shortcode.
     * @param string $content    The content/nested URL of the shortcode.
     *
     * @return string
     */
    public function __invoke(array $attributes, string $content): string
    {
        // Try to get URL from attributes first (named "url"), then from first positional attribute, then from content
        $rawUrl = $attributes['url'] ?? $attributes[0] ?? trim($content);

        if (empty($rawUrl)) {
            return '';
        }

        // Content may contain URL followed by attribute-like tokens (e.g. "width=\"800\"").
        // Only use the first token as the URL.
        $token = strtok($rawUrl, ' ');

        if (empty($token)) {
            return '';
        }

        // YouTube detection - check if it's a YouTube URL (with or without scheme)
        if ($this->isYoutubeUrl($token)) {
            $handler = new Youtube();
            $youtubeAttrs = $attributes;
            $youtubeAttrs[0] = $token;
            return $handler($youtubeAttrs);
        }

        // GitHub Gist detection - full URL or short syntax
        if ($this->isGistUrl($token)) {
            $handler = new Gist();
            $gistAttrs = $attributes;
            $gistAttrs[0] = $token;
            return $handler($gistAttrs);
        }

        // Vimeo detection
        if ($this->isVimeoUrl($token)) {
            $handler = new Vimeo();
            $vimeoAttrs = $attributes;
            $vimeoAttrs[0] = $token;
            return $handler($vimeoAttrs);
        }

        // Ensure URL has a scheme for iframe
        if (!preg_match('~^(?:f|ht)tps?://~i', $token)) {
            $token = 'https://' . $token;
        }

        // Default: render as iframe
        return (new Iframe())($attributes, $token);
    }

    /**
     * Check if the given token is a YouTube URL.
     *
     * @param string $token
     * @return bool
     */
    private function isYoutubeUrl(string $token): bool
    {
        // Parse URL (might not have scheme)
        $urlParts = parse_url($token);
        $host = $urlParts['host'] ?? '';

        // If host is set, check it directly
        if ($host !== '') {
            $host = strtolower($host);
            return in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be'], true);
        }

        // No host means no scheme - check first segment of path
        $path = $urlParts['path'] ?? $token;
        $firstSegment = strtok($path, '/');
        return in_array($firstSegment, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be'], true);
    }

    /**
     * Check if the given token is a GitHub Gist URL or short syntax.
     *
     * @param string $token
     * @return bool
     */
    private function isGistUrl(string $token): bool
    {
        // Full gist URL
        if (strpos($token, '://') !== false) {
            $urlParts = parse_url($token);
            $host = $urlParts['host'] ?? '';
            return strtolower($host) === 'gist.github.com';
        }

        // Short syntax: owner/hash where hash is hex-like (typical Gist ID)
        // Gist IDs are typically 20+ character hex strings, but can be shorter
        // The owner cannot contain / and the ID must not be empty
        if (strpos($token, '/') !== false && strpos($token, '://') === false && substr_count($token, '/') === 1) {
            [$owner, $id] = explode('/', $token, 2);
            // Owner: alphanumeric + hyphens/underscores, no dots (not a domain)
            // ID: any non-empty string
            if ($owner !== '' && $id !== '' && strpos($owner, '.') === false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the given token is a Vimeo URL.
     *
     * @param string $token
     * @return bool
     */
    private function isVimeoUrl(string $token): bool
    {
        $urlParts = parse_url($token);
        $host = strtolower($urlParts['host'] ?? '');
        return in_array($host, ['vimeo.com', 'www.vimeo.com'], true);
    }
}
