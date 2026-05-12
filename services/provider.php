<?php

\defined('_JEXEC') or die;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Extension\Shortcodes;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Gist;
use JoomlaShortcoder\Plugin\Content\Shortcodes\GoogleDocs;
use JoomlaShortcoder\Plugin\Content\Shortcodes\GoogleMaps;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Lorem;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Pdf;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Repeat;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Rutube;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Tweet;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Vimeo;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Youtube;
use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

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

        $container->share(Gist::class, fn() => new Gist(), true);
        $container->share(GoogleDocs::class, fn() => new GoogleDocs(), true);
        $container->share(GoogleMaps::class, fn() => new GoogleMaps(), true);
        $container->share(Lorem::class, fn() => new Lorem(), true);
        $container->share(Pdf::class, fn() => new Pdf(), true);
        $container->share(Repeat::class, fn() => new Repeat(), true);
        $container->share(Tweet::class, fn() => new Tweet(), true);
        $container->share(Rutube::class, fn() => new Rutube(), true);
        $container->share(Vimeo::class, fn() => new Vimeo(), true);
        $container->share(Youtube::class, fn() => new Youtube(), true);
    }
};
