# Joomla Shortcodes

## Project Overview

This project is a Joomla plugin that provides a pack of shortcodes for the `joomla-shortcoder` extension. It allows users to easily embed content like YouTube videos into their Joomla articles.

## Shortcode Implementation

Shortcodes are implemented as PHP files in the `shortcodes` directory. Each file defines a shortcode and its attributes. For example, the `youtube.php` file implements the `{youtube}` shortcode, which allows users to embed YouTube videos into their articles.

The main plugin class, `JoomlaShortcoder\Plugin\Content\Shortcodes\Extension\Shortcodes`, registers the `shortcodes` directory with the `joomla-shortcoder` extension. This makes the shortcodes available to Joomla.

## Building and Running

### Dependencies

The project uses `composer` to manage its dependencies. The main dependencies are:

-   `php: >=7.4`
-   `phpunit/phpunit: ^10.0`
-   `friendsofphp/php-cs-fixer: ^3.95`
-   `popartdesign/joomla-shortcoder: ^0.0.2`

### Running Tests

The project uses PHPUnit for testing. The tests can be run using the following command:

```bash
vendor/bin/phpunit
```

## Development Conventions

### Code Style

The project uses `php-cs-fixer` to enforce a consistent code style. The code style can be checked using the following command:

```bash
composer cs
```

The code style can be fixed using the following command:

```bash
composer cs-fix
```

