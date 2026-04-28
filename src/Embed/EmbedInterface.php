<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * Interface for embed handlers.
 */
interface EmbedInterface
{
    /**
     * Check if this handler supports the given URL.
     *
     * @param string $url The URL to check.
     * @return bool True if supported, false otherwise.
     */
    public function supports(string $url): bool;

    /**
     * Process the given URL and return the embed HTML.
     *
     * @param string $url The URL to process.
     * @param array $attributes The shortcode attributes.
     * @return string The embed HTML.
     */
    public function process(string $url, array $attributes): string;
}
