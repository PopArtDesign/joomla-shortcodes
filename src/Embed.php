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
        $url = $this->extractUrl($attributes, $content);

        // Find first handler that supports this URL
        $handler = $this->findHandler($url);
        if ($handler === null) {
            return '';
        }

        // Create a copy of attributes for the handler, removing 'id' and 'class'
        $handlerAttributes = $attributes;
        unset($handlerAttributes['id'], $handlerAttributes['class']);

        $content = $handler->process($url, $handlerAttributes);

        $wrapperAttributes = $handler->getWrapperAttributes($attributes);

        if ($wrapperAttributes === false) {
            return $content;
        }

        $wrapperAttributes['class'] = trim('embed-container ' . ($wrapperAttributes['class'] ?? ''));

        // Handle shortcode attributes for the wrapper
        if (isset($attributes['id'])) {
            $wrapperAttributes['id'] = $attributes['id'];
        }

        if (isset($attributes['class'])) {
            $wrapperAttributes['class'] = trim(($wrapperAttributes['class'] ?? '') . ' ' . $attributes['class']);
        }

        if (isset($attributes['style'])) {
            $wrapperAttributes['style'] = trim(($wrapperAttributes['style'] ?? '') . '; ' . $attributes['style'], '; ');
        }

        $attrString = $this->buildAttributes($wrapperAttributes);

        return sprintf('<div %s>%s</div>', $attrString, $content);
    }

    /**
     * Extracts the URL from the attributes array or content, and removes it from the attributes array.
     *
     * @param array  $attributes The attributes array, passed by reference, from which the URL will be removed.
     * @param string $content    The content string, used as a fallback for the URL.
     *
     * @return string The extracted and validated URL.
     *
     * @throws \InvalidArgumentException If the URL is missing or invalid.
     */
    private function extractUrl(array &$attributes, string $content): string
    {
        $url = null;
        if (isset($attributes['url'])) {
            $url = $attributes['url'];
            unset($attributes['url']);
        } elseif (isset($attributes[0])) {
            $url = $attributes[0];
            unset($attributes[0]);
        } else {
            $url = trim($content);
        }

        if (empty($url)) {
            throw new \InvalidArgumentException('Embed URL not found.');
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('Invalid URL provided for embedding.');
        }

        return $url;
    }

    private function findHandler(string $url): ?EmbedInterface
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($url)) {
                return $handler;
            }
        }

        // Should never reach here since Iframe always supports
        return null;
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * @param array $attributes The attributes to build.
     *
     * @return string The HTML attribute string.
     */
    private function buildAttributes(array $attributes): string
    {
        $html = [];

        foreach ($attributes as $key => $value) {
            if ($value === null) {
                continue;
            }

            if (is_bool($value)) {
                if ($value) {
                    $html[] = $key;
                }
            } else {
                $html[] = sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
            }
        }

        return implode(' ', $html);
    }
}
