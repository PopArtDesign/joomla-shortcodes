<?php

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\EmbedInterface;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Gist;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Iframe;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Vimeo;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Extension\Shortcodes;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed\Youtube;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Lorem;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Repeat;

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            PluginInterface::class,
            fn () => new Shortcodes(
                $container,
                (array) PluginHelper::getPlugin('content', 'shortcodes'),
            )
        );

        $container->share(Embed::class, fn() => new Embed([
            $container->get(Youtube::class),
            $container->get(Gist::class),
            $container->get(Vimeo::class),
            $container->get(Iframe::class),
        ]), true);

        $container->share(Youtube::class, fn() => new Youtube(), true);
        $container->share(Gist::class, fn() => new Gist(), true);
        $container->share(Vimeo::class, fn() => new Vimeo(), true);
        $container->share(Iframe::class, fn() => new Iframe(), true);

        $container->share(Lorem::class, fn() => new Lorem(), true);
        $container->share(Repeat::class, fn() => new Repeat(), true);
    }
};