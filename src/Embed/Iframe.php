<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

class Iframe implements EmbedInterface
{
    public function supports(string $url): bool
    {
        return true;
    }

    public function process(string $url, array $attributes): string
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
