<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HtmlHelper;

\defined('_JEXEC') or die;

/**
 * Abstract base class for shortcodes that embed content using iframes.
 *
 * Provides common functionality for generating iframe embeds.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
abstract class AbstractIframeHandler extends AbstractEmbedHandler
{
    /**
     * Returns the URL for the iframe embed.
     * Implemented by concrete shortcode classes.
     *
     * @param string $url        The original URL provided by the user.
     * @param array  $attributes The shortcode attributes.
     *
     * @return string The URL to be used for the iframe src.
     */
    abstract protected function getEmbedUrl(string $url, array $attributes): string;

    /**
     * Returns an array of default attributes for the iframe.
     *
     * @param array  $attributes The shortcode attributes.
     *
     * @return array An associative array of default attributes.
     */
    abstract protected function getIframeAttributes(array $attributes): array;

    /**
     * @inheritdoc
     */
    protected function getWrapperAttributes(array $attributes): array
    {
        return [
            'class' => 'embed-iframe',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function processEmbed(string $url, array $attributes): string
    {
        $src = $this->getEmbedUrl($url, $attributes);

        if (!$src) {
            return '';
        }

        $iframeAttributes = $this->buildIframeAttributes($attributes);

        return HtmlHelper::iframe($src, $iframeAttributes);
    }

    /**
     * Builds an array of iframe attributes based on the provided shortcode attributes and default values.
     *
     * @param array $attributes The shortcode attributes.
     *
     * @return array An associative array of iframe attributes.
     */
    private function buildIframeAttributes(array $attributes): array
    {
        $userAttributes = $attributes;
        unset($userAttributes['width'], $userAttributes['height']);

        $iframeAttributes = \array_merge([
            'title' => 'Embedded content',
            'width' => '100%',
            'height' => '100%',
            'frameborder' => '0',
            'allow' => '',
            'allowfullscreen' => '',
            'referrerpolicy' => 'strict-origin-when-cross-origin',
            'loading' => 'lazy',
            'style' => '',
        ], $this->getIframeAttributes($attributes), $userAttributes);

        return [
            'title' => $iframeAttributes['title'],
            'width' => $iframeAttributes['width'],
            'height' => $iframeAttributes['height'],
            'frameborder' => $iframeAttributes['frameborder'],
            'allowfullscreen' => $iframeAttributes['allowfullscreen'],
            'allow' => $iframeAttributes['allow'],
            'referrerpolicy' => $iframeAttributes['referrerpolicy'],
            'loading' => $iframeAttributes['loading'],
            'style' => $iframeAttributes['style'],
        ];
    }
}
