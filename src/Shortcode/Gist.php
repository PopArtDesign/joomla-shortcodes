<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Shortcode;

\defined('_JEXEC') or die;

class Gist
{
    public function __invoke(array $attributes): string
    {
        $idOrUrl = $attributes[0] ?? '';
        $file = $attributes['file'] ?? '';

        if (!$idOrUrl) {
            return '';
        }

        if (strpos($idOrUrl, 'https://gist.github.com/') === 0) {
            $scriptUrl = rtrim($idOrUrl, '/') . '.js';
        } elseif (strpos($idOrUrl, '://') !== false) {
            return '';
        } else {
            $scriptUrl = 'https://gist.github.com/' . $idOrUrl . '.js';
        }

        if ($file) {
            $scriptUrl .= '?file=' . urlencode($file);
        }

        return <<<HTML
<script src="{$scriptUrl}"></script>
HTML;
    }
}
