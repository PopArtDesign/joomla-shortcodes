# Joomla Shortcodes

## Project Overview

This project is a Joomla plugin that provides a pack of shortcodes for the [joomla-shortcoder](https://github.com/PopArtDesign/joomla-shortcoder) extension. It allows users to easily embed content like YouTube videos into their Joomla articles.

## Shortcode Implementation

Shortcodes are implemented as invokable PHP classes.

### Architecture

- **Shortcodes**: `src/Lorem.php`, `src/Repeat.php` etc. - Standalone shortcode classes
- **Shortcode Registration**: `src/Extension/Shortcodes.php` - Registers all shortcodes
- `src/AbstractVideohostingHandler.php` - Provides common functionality for video-specific attributes like autoplay, start/end times, and aspect ratio.

### Adding a New Shortcode

1. Create a new class in `src/` (e.g., `MyShortcode.php`)
2. Implement an `__invoke` method with appropriate signature (see `Lorem.php` or `Repeat.php` for examples)
3. Register it in `services/provider.php` to add it to the DI container
4. Register it in `src/Extension/Shortcodes.php`

### Available Shortcodes

-   **`gist`** - Embeds GitHub Gists
-   **`googledocs`** - Embeds Google Docs
-   **`iframe`** - Embeds iframes
-   **`lorem`** - Generates Lorem Ipsum placeholder text
-   **`pdf`** - Embeds PDF documents
-   **`repeat`** - Repeats enclosed content
-   **`rutube`** - Embeds Rutube videos
-   **`vimeo`** - Embeds Vimeo videos
-   **`youtube`** - Embeds YouTube videos
