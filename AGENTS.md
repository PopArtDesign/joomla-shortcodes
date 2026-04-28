# Joomla Shortcodes

## Project Overview

This project is a Joomla plugin that provides a pack of shortcodes for the `joomla-shortcoder` extension. It allows users to easily embed content like YouTube videos into their Joomla articles.

## Shortcode Implementation

Shortcodes are implemented as invokable PHP classes.

### Architecture

- **Shortcodes**: `src/Lorem.php`, `src/Repeat.php` - Standalone shortcode classes
- **Embed Classes**: `src/Embed/` - Individual embed handlers (Youtube, Gist, Vimeo, Iframe) that implement `EmbedInterface`
- **Embed Handler Interface**: `src/Embed/EmbedInterface.php` - Defines `supports()` and `process()` methods used by individual embed handlers
- **Main Embed Dispatcher**: `src/Embed.php` - Delegates to appropriate handler based on URL
- **Shortcode Registration**: `src/Extension/Shortcodes.php` - Registers all shortcodes

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

### Adding a New Non-Embed Shortcode

1. Create a new class in `src/` (e.g., `MyShortcode.php`)
2. Implement an `__invoke` method with appropriate signature (see `Lorem.php` or `Repeat.php` for examples)
3. Register it in `services/provider.php` to add it to the DI container
4. Register it in `src/Extension/Shortcodes.php`

### Available Shortcodes

- **`embed`** - Universal embed shortcode that delegates to appropriate handler
  - Supports: YouTube, GitHub Gist, Vimeo, generic URLs (iframe fallback)
- **`lorem`** - Generates Lorem Ipsum placeholder text
- **`repeat`** - Repeats enclosed content
