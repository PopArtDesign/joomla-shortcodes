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
    public const ABSOLUTE          = 'absolute';
    public const RELATIVE          = 'relative';
    public const PROTOCOL_RELATIVE = 'protocol-relative';

    private ?string $scheme = null;
    private ?string $host = null;
    private ?int $port = null;
    private ?string $user = null;
    private ?string $pass = null;
    private ?string $path = null;
    private ?string $query = null;
    private ?string $fragment = null;
    private ?string $extension = null;
    private string $type;
    private string $original;

    public function __construct(array $parts)
    {
        foreach ($parts as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function getFragment(): ?string
    {
        return $this->fragment;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function getType(): string
    {
        return $this->type;
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
    public function hasDomain($domains): bool
    {
        if ($this->getHost() === null) {
            return false;
        }

        if (\is_string($domains)) {
            $domains = [$domains];
        }

        return \in_array($this->getHost(), $domains, true);
    }

    /**
     * Checks if the URL's extension matches one of the given extensions.
     * Comparison is case-insensitive.
     *
     * @param string|string[] $extensions The extension(s) to check against (without the dot).
     *
     * @return bool True if the extension matches, false otherwise.
     */
    public function hasExtension($extensions): bool
    {
        if ($this->getExtension() === null) {
            return false;
        }

        if (\is_string($extensions)) {
            $extensions = [$extensions];
        }

        return \in_array(\strtolower($this->getExtension()), $extensions, true);
    }

    /**
     * Checks if the URL's type matches one of the given types.
     *
     * @param string|string[] $types The type(s) to check against.
     *
     * @return bool True if the type matches, false otherwise.
     */
    public function hasType($types): bool
    {
        if (empty($types)) {
            return true;
        }

        if (\is_string($types)) {
            $types = [$types];
        }

        return \in_array($this->getType(), $types, true);
    }
}
