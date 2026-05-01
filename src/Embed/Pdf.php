<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;

\defined('_JEXEC') or die;

/**
 * Handles embedding of PDF files.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Pdf implements EmbedInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(string $url): bool
    {
        $path = \parse_url($url, \PHP_URL_PATH);
        if ($path === false || $path === null) {
            return false;
        }

        return \strtolower(\pathinfo($path, \PATHINFO_EXTENSION)) === 'pdf';
    }

    /**
     * {@inheritdoc}
     */
    public function process(string $url, array $attributes): string
    {
        $objectAttributes = [
            'data' => $url,
            'type' => 'application/pdf',
            'width' => $attributes['width'] ?? '100%',
            'height' => $attributes['height'] ?? '500px',
            'title' => $attributes['title'] ?? 'PDF document',
        ];

        $fallbackMessage = \sprintf(
            '<p>It appears you don\'t have a PDF viewer for this browser. You can <a href="%s">click here to download the PDF file.</a></p>',
            $url,
        );

        $html = \sprintf('<object %s>%s</object>', AttributeHelper::toHtmlString($objectAttributes), $fallbackMessage);

        $wrapperAttributes = [];
        $wrapperAttributes['class'] = 'embed-container embed-pdf';
        if (!empty($attributes['class'])) {
            $wrapperAttributes['class'] .= ' ' . $attributes['class'];
        }

        return '<div ' . AttributeHelper::toHtmlString($wrapperAttributes) . '>' . $html . '</div>';
    }
}
