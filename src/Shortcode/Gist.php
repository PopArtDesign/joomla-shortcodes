<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Shortcode;

\defined('_JEXEC') or die;

class Gist
{
    public function __invoke(array $attributes): string
    {
        $url = $attributes[0] ?? '';
        $file = $attributes['file'] ?? '';

        if (!$url || strpos($url, 'https://gist.github.com') !== 0) {
            return '';
        }

        $scriptUrl = rtrim($url, '/') . '.js';

        if ($file) {
            $scriptUrl .= '?file=' . urlencode($file);
        }

        return <<<HTML
<script src="{$scriptUrl}"></script>
HTML;
    }
}
