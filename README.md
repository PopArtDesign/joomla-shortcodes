# Joomla Shortcodes

[![CI](https://github.com/PopArtDesign/joomla-shortcodes/actions/workflows/ci.yml/badge.svg)](https://github.com/PopArtDesign/joomla-shortcodes/actions/workflows/ci.yml)

A shortcodes pack for [Joomla Shortcoder](https://github.com/PopArtDesign/joomla-shortcoder).

## Installation

1.  Download the [latest release package](https://github.com/PopArtDesign/joomla-shortcodes/releases/latest) (a `.zip` file).
2.  In your Joomla administrator panel, go to `System` -> `Install` -> `Extensions`.
3.  Upload the downloaded `.zip` file.
4.  Enable the "Content - Shortcodes" plugin by going to `System` -> `Manage` -> `Plugins`.

## Usage

### `embed`

Embeds remote resources (YouTube videos, GitHub Gists, Vimeo videos, Rutube videos, Google Docs, or any URL as iframe).

`{embed url [attr="value"] ...}`

The URL can be provided either as a `url` attribute or as the content between the tags:

-   `url`: The URL to embed (YouTube, Gist, Vimeo, Rutube, Google Docs, or any URL). Required if not using nested content.
-   `width`: The width of the embed. Defaults to `100%` (iframe) or type-specific default.
-   `height`: The height of the embed. Defaults to `auto` (for aspect ratio calculation) or type-specific default.
-   `class`: A CSS class for the container `div`.
-   `title`: The `title` attribute for the iframe.

**Video-specific Attributes**

These attributes are available for video embeds (YouTube, Vimeo, Rutube).

-   `autoplay`: Automatically starts playing the video. Set to `true` or `1` to enable.
-   `start`: The time in seconds (or `MM:SS` format) from which playback will begin.
-   `end`: The time in seconds (or `MM:SS` format) at which playback will end.
-   `aspect-ratio`: The aspect ratio of the embed when `height` is set to `auto` (e.g., `16 / 9`, `4 / 3`). Defaults to `16 / 9`.

**Gist-specific Attributes**

-   `file`: Specifies a particular file from the Gist to embed.

The embed shortcode automatically detects the URL type and uses the appropriate handler:

- **YouTube** (`youtube.com`, `youtu.be`): Embeds as YouTube video player
- **Vimeo** (`vimeo.com`): Embeds as Vimeo player
- **Rutube** (`rutube.ru`): Embeds as Rutube video player
- **Google Docs** (`docs.google.com`, `drive.google.com`): Embeds Google Docs, Sheets, Slides, and other files from Google Drive.
- **GitHub Gist** (`gist.github.com`): Embeds as Gist script
- **Other URLs**: Falls back to generic iframe

**YouTube Examples:**

```
{embed autoplay start=1:00 end=3:00}
https://www.youtube.com/watch?v=dQw4w9WgXcQ
{/embed}
```

```
{embed url="https://www.youtube.com/watch?v=dQw4w9WgXcQ" width="800" height="600"}
```

```
{embed https://youtu.be/dQw4w9WgXcQ aspect-ratio=4/3}
```

**Vimeo Example:**

```
{embed}https://vimeo.com/123456789{/embed}
```

```
{embed https://vimeo.com/123456789 autoplay start="30" end="60"}
```

**Rutube Examples:**

```
{embed https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/}
```
```
```

**Google Docs Examples:**

Supports embedding Google Docs, Sheets, Slides, and files from Google Drive.

```
{embed}https://docs.google.com/document/d/a-valid-id/edit{/embed}
```

```
{embed url="https://docs.google.com/spreadsheets/d/a-valid-id/edit" width="800" height="600"}
```

**Gist Example:**

```
{embed https://gist.github.com/user/12345}
```

```
{embed url="https://gist.github.com/user/12345" file="example.php"}
```

**Generic URL (iframe fallback):**

```
{embed}https://example.com/article{/embed}
```

```
{embed url="https://example.com" width="800" height="600" class="my-embed"}
```

### `lorem`

Generates "Lorem Ipsum" placeholder content.

`{lorem [tag] [count] [attr="value"] ...}`

-   `tag`: (Optional) The HTML tag to generate (e.g., `p`, `div`, `ul`, `ol`, `img`).
    -   If not provided, generates plain Lorem Ipsum text.
    -   If a `class` attribute is provided without a `tag`, it defaults to `p`.
-   `count`: (Optional) The number of times to repeat the `tag`. Can be a single number or a range (e.g., `3,5` for a random number between 3 and 5). Defaults to `1`.
    -   If `tag` is `ul` or `ol`, this count specifies the number of `<li>` items to generate within the list.
-   `words`: (Optional) The number of words to generate per item/tag. You can specify a single number or a range (e.g., `words=50,100`). Defaults to `84` (full default text).
-   `class`: (Optional) A CSS class to apply to the generated tag (e.g., `my-class`).
-   `alt`: (Optional, for `img` tag) The `alt` text for the `<img>` tag.

**Specific to `img` tag:**

When `tag` is `img`, the shortcode generates an inline Base64-encoded placeholder image using the GD library.

-   `width`: The width of the image in pixels. Defaults to `150`.
-   `height`: The height of the image in pixels. Defaults to `150`.
-   `class`: (Optional) A CSS class to apply to the `<img>` tag.
-   `alt`: (Optional) The `alt` text for the `<img>` tag.

**Examples:**

Generate the full default Lorem Ipsum text without any wrapping tags:
```
{lorem}
```

Generate 50 words of Lorem Ipsum text without any wrapping tags:
```
{lorem words=50}
```

Generate a single paragraph (`<p>`) containing a random number of words (between 20 and 30) of Lorem Ipsum text:
```
{lorem p words=20,30}
```

Generate three paragraphs (`<p>`) each containing the full Lorem Ipsum text:
```
{lorem p 3}
```

Generate an unordered list (`<ul class="my-list">`) with 3 to 5 list items (`<li>`), where each list item contains 5 to 10 words of Lorem Ipsum text:
```
{lorem ul 3,5 words=5,10 class="my-list"}
```

Generate an inline placeholder image with dimensions 300x200 pixels, a class `img-fluid`, and alt text "Placeholder image":
```
{lorem img width=300 height=200 class="img-fluid" alt="Placeholder image"}
```

Generate the full default Lorem Ipsum text wrapped in a `<p class="text-center">` tag:
```
{lorem class="text-center"}
```

### `repeat`

Repeats the enclosed content a specified number of times.

`{repeat N|min,max} ... {/repeat}`

-   `N|min,max`: The number of times to repeat the content. You can specify a single number or a range for a random number of repetitions (e.g., `3,5`). Defaults to `1`.

**Example:**

```
{repeat 3}Hello, World! {/repeat}
```

This will output: `Hello, World! Hello, World! Hello, World!`

```
{repeat 1,3}Beetlejuice! {/repeat}
```

This will output "Beetlejuice! " one, two, or three times randomly.

## License

This project is licensed under the [MIT License](LICENSE).
