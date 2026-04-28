<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * A shortcode for embedding generic URLs as iframes.
 * Used as a fallback when no specific handler is available.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Iframe
{
    /**
     * Invoke the shortcode.
     *
     * @param array  $attributes The attributes of the shortcode.
     * @param string $url        The URL to embed.
     *
     * @return string
     */
    public function __invoke(array $attributes, string $url = ''): string
    {
        $width = $attributes['width'] ?? '100%';
        $height = $attributes['height'] ?? '500';
        $class = $attributes['class'] ?? 'embed-container';
        $title = $attributes['title'] ?? 'Embedded content';

        return <<<HTML
<div class="{$class}">
    <iframe
        src="{$url}"
        width="{$width}"
        height="{$height}"
        title="{$title}"
        frameborder="0"
        allowfullscreen>
    </iframe>
</div>
HTML;
    }
}
