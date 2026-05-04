<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

\defined('_JEXEC') or die;

/**
 * Handles the `iframe` shortcode to embed content in an iframe.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Iframe extends AbstractIframeHandler
{
    protected function getEmbedUrl(string $url, array $attributes): string
    {
        return $url;
    }

    protected function getIframeAttributes(array $attributes): array
    {
        return [
            'height' => '500',
            'title' => 'Embedded content',
            'allow' => '',
            'referrerpolicy' => 'strict-origin-when-cross-origin',
        ];
    }
}
