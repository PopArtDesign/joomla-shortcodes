# Joomla Shortcodes

## Project Overview

This project is a Joomla plugin that provides a pack of shortcodes for the [joomla-shortcoder](https://github.com/PopArtDesign/joomla-shortcoder) extension. It allows users to easily embed content like YouTube videos into their Joomla articles.

## Shortcode Implementation

Shortcodes are implemented as invokable PHP classes.

### Architecture

- **Shortcodes**: `src/Lorem.php`, `src/Repeat.php` etc. - Standalone shortcode classes
- **Shortcode Registration**: `src/Extension/Shortcodes.php` - Registers all shortcodes
- `src/AbstractVideohostingHandler.php` - Provides common functionality for video-specific attributes like autoplay, start/end times, and aspect ratio.
- **Helpers**:
  - `src/Helper/AttributeHelper.php` - Provides utility methods for parsing and handling shortcode attributes, such as converting string values to specific data types (e.g., boolean, integer ranges, time formats) and extracting URLs.
  - `src/Helper/HandlerHelper.php` - A helper for shortcode handlers, used for rendering responsive iframes and content wrappers.
  - `src/Helper/HtmlHelper.php` - A helper for generating HTML tags like `div`, `iframe`, `object`, and `script`.
  - `src/Helper/UrlHelper.php` - A helper for parsing URLs.

### Adding a New Shortcode

1. Create a new class in `src/` (e.g., `MyShortcode.php`)
2. Implement an `__invoke` method with appropriate signature (see `Lorem.php` or `Repeat.php` for examples)
3. Register it in `services/provider.php` to add it to the DI container
4. Register it in `src/Extension/Shortcodes.php`

### Adding a New Video Shortcode

To add a new video shortcode (like YouTube, Vimeo, Rutube):

1.  Create a new class in `src/` (e.g., `MyVideoShortcode.php`) that **extends `AbstractVideohostingHandler`**.
2.  Implement the `protected function getEmbedUrl(string $url, array $attributes): string` method to construct the embed URL.
3.  Implement the `protected function getVideoId(string $url): string` method to extract the video ID from the URL.
4.  Optionally, implement `protected function getIframeAttributes(array $attributes): array` to provide additional iframe attributes (see `Youtube.php` for an example).
5.  Register it in `services/provider.php` to add it to the DI container.
6.  Register it in `src/Extension/Shortcodes.php`.

### Available Shortcodes

-   **`gist`** - Embeds GitHub Gists
-   **`googledocs`** - Embeds Google Docs
-   **`googlemaps`** - Embeds Google Maps
-   **`lorem`** - Generates Lorem Ipsum placeholder text
-   **`pdf`** - Embeds PDF documents
-   **`repeat`** - Repeats enclosed content
-   **`rutube`** - Embeds Rutube videos
-   **`vimeo`** - Embeds Vimeo videos
-   **`youtube`** - Embeds YouTube videos
