<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

class Iframe implements EmbedInterface
{
    private IframeRenderer $iframeRenderer;

    public function __construct()
    {
        $this->iframeRenderer = new IframeRenderer();
    }

    public function supports(string $url): bool
    {
        return true;
    }

    public function process(string $url, array $attributes): string
    {
        $attributes['width'] = $attributes['width'] ?? '100%';
        $attributes['height'] = $attributes['height'] ?? '500';
        $attributes['title'] = $attributes['title'] ?? 'Embedded content';
        $attributes['class'] = $attributes['class'] ?? 'embed-container';

        return $this->iframeRenderer->render($url, $attributes);
    }
}
