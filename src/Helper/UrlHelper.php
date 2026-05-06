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
    public const ABSOLUTE          = 'absolute';
    public const RELATIVE          = 'relative';
    public const PROTOCOL_RELATIVE = 'protocol-relative';
    public const ANY               = 'any';

    /**
     * Strictly validates a URL against absolute, relative, or any type.
     *
     * @param string                   $url  The URL string to validate.
     * @param string|string[]|null     $type Expected URL type(s).
     *                                       Can be a string ('absolute', 'relative', 'protocol-relative', 'any'),
     *                                       an array of types, or null/empty for 'any'.
     *
     * @return bool True if the URL matches the specified type, false otherwise.
     *
     * @throws \InvalidArgumentException If an unknown $type is provided.
     */
    public static function validateUrl(string $url, $type = self::ANY): bool
    {
        $parsedUrl = self::parseUrl($url);

        // If parseUrl returns false, it means it's fundamentally unparseable
        // e.g., malformed URL, forbidden characters, or invalid scheme
        if ($parsedUrl === false) {
            return false;
        }

        if (empty($type)) {
            $type = self::ANY;
        }

        if (\is_string($type)) {
            $type = [$type];
        }

        if (!\is_array($type)) {
            throw new \InvalidArgumentException('Type must be a string, an array of strings, or null.');
        }

        $allowedTypes = [self::ABSOLUTE, self::RELATIVE, self::PROTOCOL_RELATIVE, self::ANY];

        foreach ($type as $t) {
            if (!\in_array($t, $allowedTypes, true)) {
                throw new \InvalidArgumentException(
                    \sprintf(
                        'Unknown URL type "%s". Allowed types: "%s".',
                        $t,
                        \implode('", ', $allowedTypes),
                    )
                );
            }
        }

        if (\in_array(self::ANY, $type, true)) {
            return true;
        }

        return \in_array($parsedUrl['type'], $type, true);
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

        return $parts;
    }
}
