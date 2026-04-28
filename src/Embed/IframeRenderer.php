<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

/**
 * Helper class for generating iframe HTML.
 */
class IframeRenderer
{
    /**
     * Render an iframe with the given attributes.
     *
     * @param string $url The iframe source URL.
     * @param array $attributes The iframe attributes (width, height, title, class, and any extra).
     * @return string The rendered iframe HTML.
     */
    public function render(string $url, array $attributes): string
    {
        $width  = $attributes['width'] ?? '100%';
        $height = $attributes['height'] ?? '500';
        $title  = $attributes['title'] ?? 'Embedded content';
        $class  = $attributes['class'] ?? 'embed-container';

        $allowLine = isset($attributes['allow']) ? "        allow=\"{$attributes['allow']}\"\n" : '';
        $referrerLine = isset($attributes['referrerpolicy']) ? "        referrerpolicy=\"{$attributes['referrerpolicy']}\"\n" : '';

        return <<<HTML
<div class="{$class}">
    <iframe
        src="{$url}"
        width="{$width}"
        height="{$height}"
{$allowLine}        title="{$title}"
{$referrerLine}        frameborder="0"
        allowfullscreen>
    </iframe>
</div>
HTML;
    }
}
