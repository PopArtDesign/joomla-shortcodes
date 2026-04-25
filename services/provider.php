<?php

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Extension\Shortcodes;

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                return new Shortcodes(
                    $container,
                    (array) PluginHelper::getPlugin('content', 'shortcodes'),
                );
            }
        );
    }
};
