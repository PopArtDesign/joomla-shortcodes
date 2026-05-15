<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\AttributeHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HandlerHelper;
use JoomlaShortcoder\Plugin\Content\Shortcodes\AbstractShortcodeHandler;

\defined('_JEXEC') or die;

/**
 * Handles the `googledocs` shortcode to embed Google Docs documents.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
final class GoogleDocs extends AbstractShortcodeHandler
{
    /**
     * The main shortcode invokation method.
     *
     * @param array  $attributes The shortcode attributes. In addition to Google Docs-specific attributes,
     *                           all standard iframe attributes (e.g., `title`, `loading`, `referrerpolicy`,
     *                           `sandbox`, etc.) are supported and passed through to the generated iframe.
     * @param string $content    The content between shortcode tags.
     *
     * @return string The full HTML output for the embed.
     */
    protected function process(array $attributes, string $content): string
    {
        $parsedUrl = AttributeHelper::getAbsoluteUrl($attributes, $content);
        if ($parsedUrl === null) {
            $this->error('A valid URL was not found.');
        }

        $embedUrl = $this->getEmbedUrl((string) $parsedUrl);

        $baseWrapperAttributes = [
            'class' => 'embed-container embed-googledocs',
        ];

        $baseIframeAttributes = [
            'title' => 'Google document',
            'width' => '100%',
            'height' => '100%',
            'frameborder' => '0',
            'allow' => '',
            'allowfullscreen' => '',
            'referrerpolicy' => 'strict-origin-when-cross-origin',
            'loading' => 'lazy',
        ];

        return HandlerHelper::iframe(
            $embedUrl,
            $attributes,
            $baseWrapperAttributes,
            $baseIframeAttributes,
        );
    }

    protected function getEmbedUrl(string $url): string
    {
        if ($this->isEmbeddableUrl($url)) {
            $embedUrl = $url;
        } else {
            $embedUrl = $this->buildEmbedUrl($url);
        }

        return $embedUrl;
    }

    /**
     * Checks if the given URL is already in an embeddable format.
     *
     * @param string $url The URL to check.
     *
     * @return bool True if the URL contains an embed path, false otherwise.
     */
    private function isEmbeddableUrl(string $url): bool
    {
        $embedPaths = ['/pubhtml', '/embed', '/preview'];

        foreach ($embedPaths as $path) {
            if (strpos($url, $path) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Builds the embed URL for Google Docs/Drive files from the original URL.
     *
     * @param string $url The original Google Docs/Drive URL.
     *
     * @return string The constructed embed URL.
     */
    private function buildEmbedUrl(string $url): string
    {
        $fileDetails = $this->extractFileDetails($url);
        $fileId = $fileDetails['fileId'];
        $type   = $fileDetails['type'];

        $embedTemplates = [
            'document'     => 'https://docs.google.com/document/d/%s/preview',
            'spreadsheet'  => 'https://docs.google.com/spreadsheets/d/%s/preview',
            'presentation' => 'https://docs.google.com/presentation/d/%s/embed',
            'drive'        => 'https://drive.google.com/file/d/%s/preview',
        ];

        if (!isset($embedTemplates[$type])) {
            $this->error(sprintf("Unsupported Google Docs file type: '%s'.", $type));
        }

        return sprintf($embedTemplates[$type], $fileId);
    }

    /**
     * Extracts file ID and type from a Google Docs/Drive URL.
     *
     * @param string $url The URL to extract details from.
     *
     * @return array An associative array with 'fileId'
     *               and 'type'.
     */
    private function extractFileDetails(string $url): array
    {
        $patterns = [
            'document'     => '~docs\.google\.com/document/d/([a-zA-Z0-9_-]+)~i',
            'spreadsheet'  => '~docs\.google\.com/spreadsheets/d/([a-zA-Z0-9_-]+)~i',
            'presentation' => '~docs\.google\.com/presentation/d/([a-zA-Z0-9_-]+)~i',
            'drive'        => '~drive\.google\.com/file/d/([a-zA-Z0-9_-]+)~i',
        ];

        foreach ($patterns as $key => $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return ['fileId' => $matches[1], 'type' => $key];
            }
        }

        $this->error('Could not extract Google Docs file ID from URL.');
    }
}
