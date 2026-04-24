<?php

namespace PopArtDesign\JoomlaShortcodes\Plugin\Content\Shortcodes\Extension;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use PopArtDesign\JoomlaShortcoder\Plugin\Content\Shortcoder\Event\ShortcoderPathsEvent;

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
            'onShortcoderRegisterPaths' => 'onShortcoderRegisterPaths',
        ];
    }

    public function onShortcoderRegisterPaths(ShortcoderPathsEvent $event): void
    {
        $event->addPath(\dirname(__DIR__, 2) . '/shortcodes');
    }
}
