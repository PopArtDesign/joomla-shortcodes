# Joomla Shortcodes

## Project Overview

This project is a Joomla plugin that provides a pack of shortcodes for the `joomla-shortcoder` extension. It allows users to easily embed content like YouTube videos into their Joomla articles.

## Shortcode Implementation

Shortcodes are implemented as PHP classes in the `src/` directory, each following the `EmbedInterface` contract. For example, the `Embed\Youtube` class implements the YouTube embed handler.

### Architecture

- **Handler Interface**: `src/Embed/EmbedInterface.php` - Defines `supports()` and `process()` methods
- **Embed Classes**: `src/Embed/` - Individual handlers (Youtube, Gist, Vimeo, Iframe)
- **Main Dispatcher**: `src/Embed.php` - Delegates to appropriate handler based on URL
- **Shortcode Registration**: `src/Extension/Shortcodes.php` - Registers `embed` shortcode

### Adding a New Embed Handler

1. Create a new class in `src/Embed/` (e.g., `MyEmbed.php`)
2. Implement `EmbedInterface`:
   ```php
   class MyEmbed implements EmbedInterface
   {
       public function supports(string $url): bool
       {
           // Return true if this handler supports the URL
       }
       
       public function process(string $url, array $attributes): string
       {
           // Return HTML for the embed
       }
   }
   ```
3. Add the handler to `src/Embed.php` constructor
4. The container in `src/Extension/Shortcodes.php` will auto-wire the dependency

### Available Shortcodes

- **`embed`** - Universal embed shortcode that delegates to appropriate handler
  - Supports: YouTube, GitHub Gist, Vimeo, generic URLs (iframe fallback)
- **`lorem`** - Generates Lorem Ipsum placeholder text
- **`repeat`** - Repeats enclosed content

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
