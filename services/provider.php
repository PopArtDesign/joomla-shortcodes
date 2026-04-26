<?php

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Extension\Shortcodes;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Shortcode\Gist;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Shortcode\LoremIpsum;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Shortcode\Repeat;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Shortcode\Youtube;

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
        $container->share(LoremIpsum::class, fn() => new LoremIpsum(), true);
        $container->share(Repeat::class, fn() => new Repeat(), true);
        $container->share(Youtube::class, fn() => new Youtube(), true);
    }
};
