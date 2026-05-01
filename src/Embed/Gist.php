<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * Embed handler for GitHub Gists.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Gist extends AbstractEmbedHandler
{
    protected function getSupportedHosts(): array
    {
        return ['gist.github.com'];
    }

    /**
     * {@inheritdoc}
     */
    public function process(string $url, array $attributes): string
    {
        $file = $attributes['file'] ?? '';

        // if (\strpos($idOrUrl, 'https://gist.github.com/') !== 0) {
        //     return '';
        // }

        $scriptUrl = \rtrim($url, '/') . '.js';

        if ($file) {
            $scriptUrl .= '?file=' . \urlencode($file);
        }

        return \sprintf('<script src="%s"></script>', $scriptUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function getWrapperAttributes(array $attributes)
    {
        return ['class' => 'embed-gist'];
    }
}
