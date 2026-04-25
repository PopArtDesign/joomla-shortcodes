<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Extension;

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

    public function loadShortcodes(LoadShortcodesEvent $event): void
    {
        $event->addPath(\dirname(__DIR__, 2) . '/shortcodes');
    }
}
