<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Helper;

\defined('_JEXEC') or die;

/**
 * A helper class for URLs.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class UrlHelper
{
    public const ABSOLUTE = 'absolute';
    public const RELATIVE = 'absolute';
    public const ANY      = 'any';

    /**
    * Strictly validates a URL against absolute, relative, or any type.
    *
    * @param string $url  The URL string to validate.
    * @param string $type Expected URL type:
    *                     - 'absolute': must have scheme and host (e.g., https://example.com).
    *                     - 'relative': must not have scheme or host, and must not start with "//".
    *                     - 'any': accepts any syntactically valid URL: absolute (with scheme),
    *                              relative, or protocol-relative (//example.com).
    *
    * @return bool True if the URL matches the specified type, false otherwise.
    *
    * @throws InvalidArgumentException If an unknown $type is provided.
    */
    public static function validateUrl(string $url, string $type = self::ANY): bool
    {
        // Empty string is not a valid URI per RFC 3986
        if ($url === '') {
            return false;
        }

        $parsedUrl = self::parseUrl($url);

        // If parseUrl returns false, it means it's fundamentally unparseable
        // e.g., malformed URL, forbidden characters, or invalid scheme
        if ($parsedUrl === false) {
            return false;
        }

        switch ($type) {
            case self::ABSOLUTE:
                return $parsedUrl['type'] === 'absolute';

            case self::RELATIVE:
                return $parsedUrl['type'] === 'relative';

            case self::ANY:
                // 'any' accepts absolute, relative, or protocol-relative
                // If we reached here, parseUrl succeeded and determined a type.
                // All types determined by parseUrl are considered valid for 'any'.
                return true;

            default:
                throw new \InvalidArgumentException(
                    \sprintf(
                        'Unknown URL type "%s". Allowed types: "absolute", "relative", "any".',
                        $type,
                    )
                );
        }
    }

    /**
     * Parses a URL and adds 'extension' and 'type' fields.
     *
     * The 'type' can be 'absolute', 'relative', or 'protocol-relative'.
     *
     * @param string $url The URL to parse.
     *
     * @return array|false An associative array of the URL's components, or false on failure.
     */
    public static function parseUrl(string $url)
    {
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
            $parts['type'] = 'absolute';
        } elseif (!$hasScheme && \strpos($url, '//') === 0) {
            $parts['type'] = 'protocol-relative';
        } else {
            $parts['type'] = 'relative';
        }

        return $parts;
    }
}

