<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Extension;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use JoomlaShortcoder\Plugin\Content\Shortcoder\Event\LoadShortcodesEvent;

\defined('_JEXEC') or die;

/**
 * The main Joomla plugin class for the Shortcodes extension.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class Shortcodes extends CMSPlugin implements SubscriberInterface
{
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
