<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

class Gist implements EmbedInterface
{
    public function supports(string $url): bool
    {
        if (strpos($url, '://') === false) {
            return false;
        }

        $urlParts = parse_url($url);
        $host = $urlParts['host'] ?? '';

        return strtolower($host) === 'gist.github.com';
    }

    public function process(string $url, array $attributes): string
    {
        $idOrUrl = $url;
        $file = $attributes['file'] ?? '';

        if (!$idOrUrl) {
            return '';
        }

        if (\strpos($idOrUrl, 'https://gist.github.com/') !== 0) {
            return '';
        }

        $scriptUrl = \rtrim($idOrUrl, '/') . '.js';

        if ($file) {
            $scriptUrl .= '?file=' . \urlencode($file);
        }

        return \sprintf('<script src="%s"></script>', $scriptUrl);
    }
}
