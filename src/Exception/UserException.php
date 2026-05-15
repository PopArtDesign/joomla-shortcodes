<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes\Exception;

\defined('_JEXEC') or die;

/**
 * Exception for user-facing errors.
 *
 * This exception should be used for errors that are intended to be displayed to the end-user.
 * It allows the application to catch specific issues and present a graceful, informative message
 * without exposing internal system details or causing the application to crash.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class UserException extends \Exception
{
}
