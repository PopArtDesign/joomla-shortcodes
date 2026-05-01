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

        $handler = $this->getHandler($url);

        $wrapperCustomId = $attributes['id'] ?? null;
        $wrapperCustomClass = $attributes['class'] ?? null;
        unset($attributes['id'], $attributes['class']);

        $output = $handler->process($url, $attributes);

        $wrapperAttributes = $handler->getWrapperAttributes($attributes);

        if ($wrapperAttributes === false) {
            return $output;
        }

        $wrapperAttributes['class'] = trim('embed-container ' . ($wrapperAttributes['class'] ?? ''));

        // Handle shortcode attributes for the wrapper
        if ($wrapperCustomId !== null) {
            $wrapperAttributes['id'] = $wrapperCustomId;
        }

        if ($wrapperCustomClass !== null) {
            $wrapperAttributes['class'] = trim(($wrapperAttributes['class'] ?? '') . ' ' . $wrapperCustomClass);
        }

        if (isset($attributes['style'])) {
            $wrapperAttributes['style'] = trim(($wrapperAttributes['style'] ?? '') . '; ' . $attributes['style'], '; ');
        }

        $attrString = $this->buildAttributes($wrapperAttributes);

        return sprintf('<div %s>%s</div>', $attrString, $output);
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
        // Attempt 1: Explicit `url` attribute
        if (isset($attributes['url'])) {
            $url = $attributes['url'];
            if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
                unset($attributes['url']);
                return $url;
            } else {
                throw new \InvalidArgumentException('Invalid URL provided in "url" attribute: ' . $url);
            }
        }

        // Attempt 2: Content
        $trimmedContent = trim($content);
        if ($trimmedContent !== '') {
            if (filter_var($trimmedContent, FILTER_VALIDATE_URL) !== false) {
                return $trimmedContent;
            } else {
                throw new \InvalidArgumentException('Invalid URL provided in content: ' . $trimmedContent);
            }
        }

        // Attempt 3: Positional attribute at index 0
        if (isset($attributes[0])) {
            $url = $attributes[0];
            if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
                unset($attributes[0]);
                return $url;
            } else {
                throw new \InvalidArgumentException('Invalid URL provided as positional attribute: ' . $url);
            }
        }

        // If no valid URL was found after checking all candidates
        throw new \InvalidArgumentException('A valid embed URL was not found.');
    }

    /**
     * Gets a suitable embed handler for the given URL.
     *
     * @param string $url The URL to find a handler for.
     *
     * @return EmbedInterface The suitable embed handler.
     *
     * @throws \RuntimeException If no suitable handler can be found.
     */
    private function getHandler(string $url): EmbedInterface
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($url)) {
                return $handler;
            }
        }

        throw new \RuntimeException('No embed handler found for the given URL.');
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
