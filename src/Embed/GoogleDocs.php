<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * Handles embedding Google Docs, Sheets, Presentations, and Drive files.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class GoogleDocs extends AbstractEmbedHandler
{
    /**
     * {@inheritdoc}
     */
    protected function getSupportedHosts(): array
    {
        return ['docs.google.com', 'drive.google.com'];
    }

    /**
     * {@inheritdoc}
     */
    public function process(string $url, array $attributes): string
    {
        if ($this->isEmbeddableUrl($url)) {
            $embedUrl = $url;
        } else {
            $embedUrl = $this->buildEmbedUrl($url);
        }

        $iframeAttributes = [
            'width' => $attributes['width'] ?? '100%',
            'height' => $attributes['height'] ?? null,
            'title' => $attributes['title'] ?? 'Google document',
            'frameborder' => $attributes['frameborder'] ?? '0',
            'allowfullscreen' => $attributes['allowfullscreen'] ?? '',
            'allow' => $attributes['allow'] ?? '', // No specific allow for docs, but it's good to have it
            'referrerpolicy' => $attributes['referrerpolicy'] ?? 'strict-origin-when-cross-origin',
            'loading' => $attributes['loading'] ?? 'lazy',
        ];

        return Iframe::render($embedUrl, $iframeAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function getWrapperAttributes(array $attributes)
    {
        return ['class' => 'embed-googledocs'];
    }

    /**
     * Extracts file ID and type from a Google Docs/Drive URL.
     *
     * @param string $url The URL to extract details from.
     *
     * @return array|null An associative array with 'fileId'
     *                    and 'type' if found, null otherwise.
     */
    private function extractFileDetails(string $url): ?array
    {
        // Patterns with service type binding
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

        return null;
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
     * @throws \InvalidArgumentException If the file type is unsupported or details cannot be extracted.
     */
    private function buildEmbedUrl(string $url): string
    {
        $fileDetails = $this->extractFileDetails($url);

        if (empty($fileDetails)) {
            throw new \InvalidArgumentException('Could not extract Google Docs/Drive file details from URL: ' . $url);
        }

        $fileId = $fileDetails['fileId'];
        $type   = $fileDetails['type'];

        // Embed URL templates
        $embedTemplates = [
            'document'     => 'https://docs.google.com/document/d/%s/preview',
            'spreadsheet'  => 'https://docs.google.com/spreadsheets/d/%s/preview',
            'presentation' => 'https://docs.google.com/presentation/d/%s/embed',
            'drive'        => 'https://drive.google.com/file/d/%s/preview',
        ];

        if (!isset($embedTemplates[$type])) {
            throw new \InvalidArgumentException('Unsupported Google Docs/Drive file type: ' . $type);
        }

        return sprintf($embedTemplates[$type], $fileId);
    }
}
