<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

\defined('_JEXEC') or die;

/**
 * A shortcode for embedding GitHub Gists.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Gist
{
    /**
     * Invoke the shortcode.
     *
     * @param array $attributes The attributes of the shortcode.
     *
     * @return string
     */
    public function __invoke(array $attributes): string
    {
        $idOrUrl = $attributes[0] ?? '';
        $file = $attributes['file'] ?? '';

        if (!$idOrUrl) {
            return '';
        }

        if (\strpos($idOrUrl, 'https://gist.github.com/') === 0) {
            $scriptUrl = \rtrim($idOrUrl, '/') . '.js';
        } elseif (\strpos($idOrUrl, '://') !== false) {
            return '';
        } else {
            $scriptUrl = 'https://gist.github.com/' . $idOrUrl . '.js';
        }

        if ($file) {
            $scriptUrl .= '?file=' . \urlencode($file);
        }

        return \sprintf('<script src="%s"></script>', $scriptUrl);
    }
}
