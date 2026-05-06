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

        // Forbidden characters (control characters, spaces, angle brackets, backticks, etc.)
        if (\preg_match('/[\x00-\x1F\x7F<>"{}|\\^`]/', $url)) {
            return false;
        }

        $parts = \parse_url($url);
        if ($parts === false) {
            return false;
        }

        $hasScheme = isset($parts['scheme']);
        $hasHost   = isset($parts['host']);
        $scheme    = $parts['scheme'] ?? '';

        // Validate scheme syntax if present (RFC 3986: scheme = ALPHA *( ALPHA / DIGIT / "+" / "-" / "." ))
        if ($hasScheme && !\preg_match('/^[a-zA-Z][a-zA-Z0-9+.-]*$/', $scheme)) {
            return false;
        }

        // Detect protocol-relative URLs (start with "//" and no scheme)
        $isProtocolRelative = (\strpos($url, '//') === 0) && !$hasScheme;

        switch ($type) {
            case 'absolute':
                // Absolute URL must have both scheme and host
                if (!$hasScheme || !$hasHost) {
                    return false;
                }

                // Protocol-relative is not considered absolute
                if ($isProtocolRelative) {
                    return false;
                }

                return true;

            case 'relative':
                // Relative URL: no scheme, no host, and must not start with "//"
                if ($hasScheme || $hasHost || $isProtocolRelative) {
                    return false;
                }

                return true;

            case 'any':
                // Accepts:
                // - absolute (scheme + host)
                // - relative (no scheme, no host, not starting with //)
                // - protocol-relative (starts with //, no scheme, but has host)
                if ($isProtocolRelative) {
                    // Must have a host, e.g., "//example.com" is valid, "//" is not
                    return $hasHost;
                }

                // If scheme is present, host must also exist (e.g., "http:" or "http://" are invalid)
                if ($hasScheme && !$hasHost) {
                    return false;
                }

                // If no scheme, host must also be absent (it would be a relative URL)
                if (!$hasScheme && $hasHost) {
                    return false;
                }

                // Remaining cases: absolute with scheme+host, or relative without scheme/host
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
}
