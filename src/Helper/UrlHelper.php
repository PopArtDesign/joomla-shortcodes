<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Helper;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Value\ParsedUrl;

\defined('_JEXEC') or die;

/**
 * A helper class for URLs.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class UrlHelper
{
    public const ABSOLUTE          = 'absolute';
    public const RELATIVE          = 'relative';
    public const PROTOCOL_RELATIVE = 'protocol-relative';
    public const ANY               = 'any';

    /**
     * Parses a URL and returns a ParsedUrl object.
     *
     * @param string $url The URL to parse.
     *
     * @return ParsedUrl|false An object with the URL's components, or false on failure.
     */
    public static function parseUrl(string $url)
    {
        // Empty string is not a valid URI per RFC 3986
        if ($url === '') {
            return false;
        }

        // Forbidden characters (control characters, spaces, angle brackets, backticks, etc.)
        if (\preg_match('/[\x00-\x1F\x7F<>"{}|\\^`]/', $url)) {
            return false;
        }

        $parts = \parse_url($url);

        if ($parts === false) {
            return false;
        }

        // Validate scheme syntax if present (RFC 3986: scheme = ALPHA *( ALPHA / DIGIT / "+" / "-" / "." ))
        if (isset($parts['scheme']) && !\preg_match('/^[a-zA-Z][a-zA-Z0-9+.-]*$/', $parts['scheme'])) {
            return false;
        }

        if (isset($parts['path'])) {
            $extension = \pathinfo($parts['path'], \PATHINFO_EXTENSION);
            if (!empty($extension)) {
                $parts['extension'] = $extension;
            }
        }

        $hasScheme = isset($parts['scheme']);
        $hasHost   = isset($parts['host']);

        if ($hasScheme && $hasHost) {
            $parts['type'] = self::ABSOLUTE;
        } elseif (!$hasScheme && \strpos($url, '//') === 0) {
            $parts['type'] = self::PROTOCOL_RELATIVE;
        } else {
            $parts['type'] = self::RELATIVE;
        }

        $parts['original'] = $url;

        return new ParsedUrl($parts);
    }
}
