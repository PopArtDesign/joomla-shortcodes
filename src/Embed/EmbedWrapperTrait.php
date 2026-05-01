<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * Trait for rendering a wrapper div for embeds.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
trait EmbedWrapperTrait
{
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
