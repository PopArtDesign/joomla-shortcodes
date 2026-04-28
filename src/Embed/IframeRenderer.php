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
     * @param array $attributes The iframe attributes (width, height, title, class, allow, referrerpolicy, frameborder, allowfullscreen).
     * @return string The rendered iframe HTML.
     */
    public function render(string $url, array $attributes): string
    {
        $width = $attributes['width'] ?? '100%';
        $height = $attributes['height'] ?? '500';
        $title = $attributes['title'] ?? 'Embedded content';
        $class = $attributes['class'] ?? 'embed-container';

        $lines = [
            "        src=\"{$url}\"",
            "        width=\"{$width}\"",
            "        height=\"{$height}\"",
        ];

        $afterTitle = [];
        $frameborder = "        frameborder=\"0\"";
        $allowfullscreen = "        allowfullscreen>";

        foreach ($attributes as $name => $value) {
            if ($name === 'referrerpolicy') {
                $afterTitle[] = "        {$name}=\"{$value}\"";
                continue;
            }
            if ($name === 'frameborder') {
                $frameborder = "        frameborder=\"{$value}\"";
                continue;
            }
            if ($name === 'allowfullscreen') {
                $allowfullscreen = $value ? "        allowfullscreen>" : "";
                continue;
            }
            if (in_array($name, ['width', 'height', 'title', 'class', 'start'])) {
                continue;
            }
            $lines[] = "        {$name}=\"{$value}\"";
        }

        $lines[] = "        title=\"{$title}\"";

        foreach ($afterTitle as $line) {
            $lines[] = $line;
        }

        $lines[] = $frameborder;
        if ($allowfullscreen) {
            $lines[] = $allowfullscreen;
        }

        $attrs = implode("\n", $lines);

        return <<<HTML
<div class="{$class}">
    <iframe
{$attrs}
    </iframe>
</div>
HTML;
    }
}
