<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HtmlHelper;

\defined('_JEXEC') or die;

/**
 * Handles the `pdf` shortcode to embed PDF files.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Pdf extends AbstractEmbedHandler
{
    /**
     * @inheritdoc
     */
    protected function processEmbed(string $url, array $attributes): string
    {
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

        return HtmlHelper::object($url, 'application/pdf', $objectAttributes, $fallbackMessage);
    }

    /**
     * @inheritdoc
     */
    protected function getWrapperAttributes(array $attributes): array
    {
        $styles = [];
        if ($attributes['width'] ?? '') {
            $styles[] = 'width: ' . $attributes['width'];
        }

        $styles[] = 'height: ' . ($attributes['height'] ?? 'var(--embed-pdf-height, 75vh)');

        return [
            'class' => 'embed-pdf',
            'style' => \implode(';', $styles),
        ];
    }
}
