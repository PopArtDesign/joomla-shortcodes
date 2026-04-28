<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Extension;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Embed;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Lorem;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Repeat;
use JoomlaShortcoder\Plugin\Content\Shortcoder\Event\LoadShortcodesEvent;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\DI\Container;
use Joomla\Event\DispatcherInterface;
use Joomla\Event\SubscriberInterface;

\defined('_JEXEC') or die;

/**
 * The main Joomla plugin class for the Shortcodes extension.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Shortcodes extends CMSPlugin implements SubscriberInterface
{
    private Container $container;

    /**
     * Shortcodes constructor.
     *
     * @param Container $container The DI container.
     * @param array     $config    The plugin configuration.
     */
    public function __construct(Container $container, array $config = [])
    {
        $this->container = $container;

        // Joomla 4.x BC
        if (\version_compare(\JVERSION, '5', '<')) {
            $dispatcher = $container->get(DispatcherInterface::class);
            parent::__construct($dispatcher, $config);
            return;
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onShortcoderLoadShortcodes' => 'loadShortcodes',
        ];
    }

    /**
     * Load the shortcodes.
     *
     * @param LoadShortcodesEvent $event The event.
     */
    public function loadShortcodes(LoadShortcodesEvent $event): void
    {
        $event->addShortcode(
            'embed',
            fn (...$args) =>
            $this->container->get(Embed::class)(...$args)
        );
        $event->addShortcode(
            'lorem',
            fn (...$args) =>
            $this->container->get(Lorem::class)(...$args)
        );
        $event->addShortcode(
            'repeat',
            fn (...$args) =>
            $this->container->get(Repeat::class)(...$args)
        );
    }
}
