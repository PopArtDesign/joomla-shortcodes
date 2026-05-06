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

    public function hasDomain(string|array $domains): bool
    {
        if ($this->host === null) {
            return false;
        }

        if (\is_string($domains)) {
            $domains = [$domains];
        }

        $host = \str_starts_with($this->host, 'www.') ? \substr($this->host, 4) : $this->host;

        foreach ($domains as $domain) {
            $domain = \str_starts_with($domain, 'www.') ? \substr($domain, 4) : $domain;
            if ($host === $domain) {
                return true;
            }
        }

        return false;
    }

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
