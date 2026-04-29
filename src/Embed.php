<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\EmbedInterface;

\defined('_JEXEC') or die;

/**
 * A shortcode for embedding remote resources.
 * Detects URL type and delegates to specific handlers (YouTube, Gist, Vimeo, etc.).
 * Falls back to iframe for generic URLs.
 *
 * Usage:
 *   {embed}https://youtube.com/watch?v=xxx{/embed}
 *   {embed url="https://gist.github.com/user/12345"}
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Embed
{
    /** @var EmbedInterface[] */
    private array $handlers;

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    public function __invoke(array $attributes, string $content): string
    {
        // Try to get URL from attributes first (named "url"), then from first positional attribute, then from content
        $rawUrl = $attributes['url'] ?? $attributes[0] ?? trim($content);

        if (empty($rawUrl)) {
            return '';
        }

        // Content may contain URL followed by attribute-like tokens.
        // Only use the first token as the URL.
        $url = strtok($rawUrl, ' ');

        if (empty($url)) {
            return '';
        }

        // Ensure URL has a scheme
        if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
            $url = 'https://' . $url;
        }

        // Find first handler that supports this URL
        foreach ($this->handlers as $handler) {
            if ($handler->supports($url)) {
                return $handler->process($url, $attributes);
            }
        }

        // Should never reach here since Iframe always supports
        return '';
    }
}
