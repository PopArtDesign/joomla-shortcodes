<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HtmlHelper;

\defined('_JEXEC') or die;

/**
 * Abstract base class for all embed shortcodes.
 *
 * Provides common functionality for extracting URLs, checking supported hosts,
 * and generating the wrapper HTML for embedded content.
 * Concrete shortcode classes should extend this class and implement the abstract methods
 * to define their specific embed logic.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
abstract class AbstractEmbedHandler
{
    /**
     * The main shortcode invokation method.
     *
     * @param array  $attributes The shortcode attributes.
     * @param string $content    The content between shortcode tags.
     *
     * @return string The full HTML output for the embed.
     */
    public function __invoke(array $attributes, string $content): string
    {
        $url = $this->extractUrl($attributes, $content);

        $wrapperCustomId = $attributes['id'] ?? null;
        $wrapperCustomClass = $attributes['class'] ?? null;
        $wrapperCustomStyle = $attributes['style'] ?? null;
        unset($attributes['id'], $attributes['class'], $attributes['style']);

        $output = $this->processEmbed($url, $attributes);

        $wrapperAttributes = $this->getWrapperAttributes($attributes);

        $wrapperAttributes['class'] = trim('embed-container ' . ($wrapperAttributes['class'] ?? ''));

        // Handle shortcode attributes for the wrapper
        if ($wrapperCustomId !== null) {
            $wrapperAttributes['id'] = $wrapperCustomId;
        }

        if ($wrapperCustomClass !== null) {
            $wrapperAttributes['class'] = trim(($wrapperAttributes['class'] ?? '') . ' ' . $wrapperCustomClass);
        }

        if ($wrapperCustomStyle !== null) {
            $wrapperAttributes['style'] = trim(($wrapperAttributes['style'] ?? '') . '; ' . $wrapperCustomStyle, '; ');
        }

        return HtmlHelper::div($wrapperAttributes, $output);
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
     * Processes the embed URL and attributes to return the raw embed HTML (iframe, object, script).
     * Implemented by concrete shortcode classes.
     *
     * @param string $url        The embed URL.
     * @param array  $attributes The attributes for the embed element.
     *
     * @return string The raw embed HTML.
     */
    abstract protected function processEmbed(string $url, array $attributes): string;

    /**
     * Returns an array of attributes for the wrapper div.
     * Implemented by concrete shortcode classes.
     *
     * @param array $attributes The original shortcode attributes.
     *
     * @return array An associative array of wrapper attributes.
     */
    abstract protected function getWrapperAttributes(array $attributes): array;
}
