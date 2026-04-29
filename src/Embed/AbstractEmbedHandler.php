<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;

\defined('_JEXEC') or die;

/**
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
abstract class AbstractEmbedHandler implements EmbedInterface
{
    abstract protected function getSupportedHosts(): array;

    public function supports(string $url): bool
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');

        return in_array($host, $this->getSupportedHosts(), true);
    }
}
