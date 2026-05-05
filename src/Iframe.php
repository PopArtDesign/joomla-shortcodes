<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HandlerHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

\defined('_JEXEC') or die;

/**
 * Handles the `iframe` shortcode to embed content in an iframe.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Iframe
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
        $url = AttributeHelper::getUrl($attributes, $content);

        $baseWrapperAttributes = [
            'class' => 'embed-container embed-iframe',
        ];

        $baseIframeAttributes = [
            'title' => 'Embedded content',
            'width' => '100%',
            'height' => '100%',
            'frameborder' => '0',
            'allow' => '',
            'allowfullscreen' => true,
            'referrerpolicy' => 'strict-origin-when-cross-origin',
            'loading' => 'lazy',
        ];

        return HandlerHelper::iframe(
            $url,
            $attributes,
            $baseWrapperAttributes,
            $baseIframeAttributes
        );
    }
}
