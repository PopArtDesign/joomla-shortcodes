<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * Embed handler for generic iframe content.
 *
 * Acts as the fallback handler for URLs that don't match any other embed provider.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Iframe implements EmbedInterface
{
    /**
     * Check if this handler supports the given URL.
     *
     * Always returns true as this is the fallback handler.
     *
     * @param string $url The URL to check.
     *
     * @return bool True (always, since this is the fallback).
     */
    public function supports(string $url): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function process(string $url, array $attributes): string
    {
        $attributes['width'] = $attributes['width'] ?? '100%';
        $attributes['height'] = $attributes['height'] ?? '500';
        $attributes['title'] = $attributes['title'] ?? 'Embedded content';
        $attributes['class'] = $attributes['class'] ?? 'embed-container';
        $attributes['frameborder'] = $attributes['frameborder'] ?? 0;
        $attributes['allowfullscreen'] = $attributes['allowfullscreen'] ?? '';

        return self::render($url, $attributes);
    }

    /**
     * Render an iframe with the given attributes.
     *
     * @param string $url        The iframe source URL.
     * @param array  $attributes The iframe attributes: width, height, title, allow,
     *                           referrerpolicy, frameborder, allowfullscreen.
     *
     * @return string The rendered iframe HTML.
     */
    public static function render(string $url, array $attributes = []): string
    {
        $attributes = \array_merge(['src' => $url], $attributes);

        $attrs = [];
        foreach ($attributes as $name => $value) {
            if (!\is_scalar($value)) {
                continue;
            }

            if ($value !== '') {
                $attrs[] = $name . '="' . $value . '"';
            } else {
                $attrs[] = $name;
            }
        }

        return sprintf('<iframe %s></iframe>', implode(' ', $attrs));
    }
}
