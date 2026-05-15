<?php

namespace JoomlaShortcoder\Plugin\Content\Shortcodes;

use JoomlaShortcoder\Plugin\Content\Shortcodes\Exception\UserException;
use JoomlaShortcoder\Plugin\Content\Shortcodes\Helper\HandlerHelper;

\defined('_JEXEC') or die;

/**
 * Abstract base class for all shortcode handlers.
 *
 * Provides a common structure for processing shortcodes, including error handling.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
abstract class AbstractShortcodeHandler
{
    /**
     * Processes the shortcode attributes and content.
     *
     * @param array  $attributes An associative array of attributes passed to the shortcode.
     * @param string $content    The content enclosed by the shortcode tags (if any).
     *
     * @return string The processed output of the shortcode.
     */
    abstract protected function process(array $attributes, string $content): string;

    /**
     * Invokes the shortcode handler.
     *
     * This method serves as the entry point for shortcode processing,
     * wrapping the `process` method with error handling for user-specific exceptions.
     *
     * @param array  $attributes An associative array of attributes passed to the shortcode.
     * @param string $content    The content enclosed by the shortcode tags (if any).
     *
     * @return string The processed output of the shortcode or an error message.
     */
    public function __invoke(array $attributes, string $content): string
    {
        try {
            return $this->process($attributes, $content);
        } catch (UserException $e) {
            return $this->showError($e->getMessage());
        }
    }

    /**
     * Throws a UserException with the given message.
     *
     * This method is a convenient way for concrete shortcode handlers to signal
     * user-facing errors that should be displayed gracefully.
     *
     * @param string $message The error message to be displayed to the user.
     *
     * @return void
     * @throws UserException
     */
    protected function error(string $message): void
    {
        throw new UserException($message);
    }

    /**
     * Displays a user-friendly error message for the current shortcode.
     *
     * @param string $message The error message to display.
     *
     * @return string The formatted error message.
     */
    private function showError(string $message): string
    {
        $shortcodeName = (new \ReflectionClass($this))->getShortName();

        return HandlerHelper::error($shortcodeName, $message);
    }
}
