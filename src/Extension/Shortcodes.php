<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Extension;

use JoomlaShortcoder\Plugin\Content\Shortcoder\Event\LoadShortcodesEvent;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Gist;
use JoomlaShortcoder\Plugin\Content\Shortcodes\GoogleDocs;
use JoomlaShortcoder\Plugin\Content\Shortcodes\GoogleMaps;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Lorem;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Pdf;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Repeat;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Rutube;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Telegram;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Tweet;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Vimeo;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Youtube;
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
final class Shortcodes extends CMSPlugin implements SubscriberInterface
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
            'gist',
            fn (...$args) =>
            $this->container->get(Gist::class)(...$args)
        );
        $event->addShortcode(
            'googledocs',
            fn (...$args) =>
            $this->container->get(GoogleDocs::class)(...$args)
        );
        $event->addShortcode(
            'googlemaps',
            fn (...$args) =>
            $this->container->get(GoogleMaps::class)(...$args)
        );
        $event->addShortcode(
            'lorem',
            fn (...$args) =>
            $this->container->get(Lorem::class)(...$args)
        );
        $event->addShortcode(
            'pdf',
            fn (...$args) =>
            $this->container->get(Pdf::class)(...$args)
        );
        $event->addShortcode(
            'repeat',
            fn (...$args) =>
            $this->container->get(Repeat::class)(...$args)
        );
        $event->addShortcode(
            'telegram',
            fn (...$args) =>
            $this->container->get(Telegram::class)(...$args)
        );
        $event->addShortcode(
            'tweet',
            fn (...$args) =>
            $this->container->get(Tweet::class)(...$args)
        );
        $event->addShortcode(
            'rutube',
            fn (...$args) =>
            $this->container->get(Rutube::class)(...$args)
        );
        $event->addShortcode(
            'vimeo',
            fn (...$args) =>
            $this->container->get(Vimeo::class)(...$args)
        );
        $event->addShortcode(
            'youtube',
            fn (...$args) =>
            $this->container->get(Youtube::class)(...$args)
        );
    }
}
