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
     * Render a wrapper div with the given HTML content.
     *
     * @param string $content       The HTML content to wrap
     * @param array  $baseClasses   Base CSS classes for the wrapper
     * @param array  $attributes    Shortcode attributes (may contain 'class' and 'style')
     * @param array  $wrapperStyles Additional styles for the wrapper
     *
     * @return string The rendered wrapper div with content
     */
    protected function renderWrapper(string $content, array $baseClasses, array $attributes, array $wrapperStyles = []): string
    {
        $wrapper = $this->buildWrapperAttributes($baseClasses, $attributes, $wrapperStyles);

        return sprintf('<div class="%s"%s>%s</div>', $wrapper['class'], $wrapper['style'], $content);
    }

    /**
     * Build wrapper div attributes from base classes, user classes, and styles.
     *
     * @param array $baseClasses   Base CSS classes for the wrapper
     * @param array $attributes    Shortcode attributes (may contain 'class' and 'style')
     * @param array $wrapperStyles Additional styles for the wrapper
     *
     * @return array Array with 'class' and 'style' strings for the wrapper
     */
    protected function buildWrapperAttributes(array $baseClasses, array $attributes, array $wrapperStyles = []): array
    {
        $styleAttr = '';
        if (!empty($wrapperStyles)) {
            $styleAttr = ' style="' . implode('; ', $wrapperStyles) . '"';
        }

        return ['class' => $this->buildWrapperClass($baseClasses, $attributes), 'style' => $styleAttr];
    }

    /**
     * Build wrapper div class from base classes and user classes.
     *
     * @param array $baseClasses Base CSS classes for the wrapper
     * @param array $attributes  Shortcode attributes (may contain 'class')
     *
     * @return string
     */
    protected function buildWrapperClass(array $baseClasses, array $attributes): string
    {
        $userClasses = array_filter(array_map('trim', explode(' ', $attributes['class'] ?? '')));

        $allClasses = array_unique(array_merge($baseClasses, $userClasses));

        return htmlspecialchars(implode(' ', $allClasses), ENT_QUOTES, 'UTF-8');
    }
}
