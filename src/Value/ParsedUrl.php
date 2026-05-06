<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Value;

\defined('_JEXEC') or die;

/**
 * A value object representing a parsed URL.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class ParsedUrl
{
    public ?string $scheme = null;
    public ?string $host = null;
    public ?int $port = null;
    public ?string $user = null;
    public ?string $pass = null;
    public ?string $path = null;
    public ?string $query = null;
    public ?string $fragment = null;
    public ?string $extension = null;
    public string $type;
    public string $original;

    public function __construct(array $parts)
    {
        foreach ($parts as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function __toString(): string
    {
        return $this->original;
    }

    /**
     * Checks if the URL's host matches one of the given domains.
     *
     * @param string|string[] $domains The domain(s) to check against.
     *
     * @return bool True if the host matches, false otherwise.
     */
    public function hasDomain(string|array $domains): bool
    {
        if ($this->host === null) {
            return false;
        }

        if (\is_string($domains)) {
            $domains = [$domains];
        }

        return \in_array($this->host, $domains, true);
    }

    /**
     * Checks if the URL's extension matches one of the given extensions.
     * Comparison is case-insensitive.
     *
     * @param string|string[] $extensions The extension(s) to check against (without the dot).
     *
     * @return bool True if the extension matches, false otherwise.
     */
    public function hasExtension(string|array $extensions): bool
    {
        if ($this->extension === null) {
            return false;
        }

        if (\is_string($extensions)) {
            $extensions = [$extensions];
        }

        return \in_array(\strtolower($this->extension), $extensions, true);
    }
}
