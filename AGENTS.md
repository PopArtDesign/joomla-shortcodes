# Joomla Shortcodes

## Project Overview

This project is a Joomla plugin that provides a pack of shortcodes for the [joomla-shortcoder](https://github.com/PopArtDesign/joomla-shortcoder) extension. It allows users to easily embed content like YouTube videos into their Joomla articles.

## Shortcode Implementation

Shortcodes are implemented as invokable PHP classes.

### Architecture

- **Shortcodes**: `src/Lorem.php`, `src/Repeat.php` - Standalone shortcode classes
- **Embed Shortcodes**: `src/AbstractEmbedHandler.php` - Base class for shortcodes that embed content (e.g., videos, documents). It handles common logic like URL extraction and wrapper generation.
- **Shortcode Registration**: `src/Extension/Shortcodes.php` - Registers all shortcodes

### Adding a New Shortcode

1. Create a new class in `src/` (e.g., `MyShortcode.php`)
2. Implement an `__invoke` method with appropriate signature (see `Lorem.php` or `Repeat.php` for examples)
3. Register it in `services/provider.php` to add it to the DI container
4. Register it in `src/Extension/Shortcodes.php`

### Adding a New Embed Shortcode

For shortcodes that embed external content (like YouTube videos), you can use the `AbstractEmbedHandler` base class.

1.  Create a new class in `src/` that extends `AbstractEmbedHandler` (e.g., `MyEmbedShortcode.php`).
2.  Implement the `processEmbed(string $url, array $attributes): string` method to return the raw embed HTML (e.g., an `<iframe>`).
3.  Implement the `getWrapperAttributes(array $attributes): array` method to provide attributes for the wrapper `div`.
4.  Register the new shortcode class in `services/provider.php`.
5.  Register the shortcode in `src/Extension/Shortcodes.php`.

### Available Shortcodes

- **`lorem`** - Generates Lorem Ipsum placeholder text
- **`repeat`** - Repeats enclosed content
