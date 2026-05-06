<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HandlerHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HtmlHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\UrlHelper;

\defined('_JEXEC') or die;

/**
 * Handles the `pdf` shortcode to embed PDF files.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Pdf
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
        $urlParts = AttributeHelper::getUrl($attributes, $content, UrlHelper::ANY);
        $url = $urlParts['original'];

        $path = \parse_url($url, \PHP_URL_PATH);
        if (!$path || \strtolower(\pathinfo($path, \PATHINFO_EXTENSION)) !== 'pdf') {
            throw new \InvalidArgumentException('The provided URL is not a PDF file.');
        }

        $objectAttributes = [
            'width' => '100%',
            'height' => '100%',
            'title' => $attributes['title'] ?? 'PDF document',
        ];

        $fallbackMessage = \sprintf(
            '<p>It appears you don\'t have a PDF viewer for this browser. You can <a href="%s">click here to download the PDF file.</a></p>',
            $url,
        );

        $output = HtmlHelper::object($url, 'application/pdf', $objectAttributes, $fallbackMessage);

        if (!isset($attributes['height'])) {
            $attributes['height'] = 'var(--embed-pdf-height, 75vh)';
        }

        $baseWrapperAttributes = [
            'class' => 'embed-container embed-pdf',
        ];

        return HandlerHelper::wrapper($output, $attributes, $baseWrapperAttributes);
    }
}
