<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * Abstract base class for embed handlers.
 *
 * Provides common functionality for processing embed URLs and rendering wrapper HTML.
 * Concrete implementations must define supported hosts and generate embed URLs.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
abstract class AbstractEmbedHandler implements EmbedInterface
{
    /**
     * Get the list of supported hostnames for this embed handler.
     *
     * @return array List of hostnames (e.g., ['youtube.com', 'www.youtube.com'])
     */
    abstract protected function getSupportedHosts(): array;

    /**
     * {@inheritdoc}
     */
    public function supports(string $url): bool
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');

        return in_array($host, $this->getSupportedHosts(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function getWrapperAttributes(array $attributes)
    {
        return [];
    }
}
