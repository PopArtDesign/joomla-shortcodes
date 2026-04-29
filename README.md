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

Embeds remote resources (YouTube videos, GitHub Gists, Vimeo videos, Rutube videos, or any URL as iframe).

`{embed url="..." width=... height=... class=... title=... autoplay=... start=... end=... aspect-ratio=...}...{/embed}`

The URL can be provided either as a `url` attribute or as the content between the tags:

-   `url`: The URL to embed (YouTube, Gist, Vimeo, Rutube, or any URL). Required if not using nested content.
-   `width`: The width of the embed. Defaults to `100%` (iframe) or type-specific default.
-   `height`: The height of the embed. Defaults to `auto` (for aspect ratio calculation) or type-specific default.
-   `class`: A CSS class for the container `div`.
-   `title`: The `title` attribute for the iframe.
-   `autoplay`: Automatically starts playing the video. Set to `true` or `1` to enable.
-   `start`: The time in seconds (or `MM:SS` format) from which playback will begin.
-   `end`: The time in seconds (or `MM:SS` format) at which playback will end.
-   `aspect-ratio`: The aspect ratio of the embed when `height` is set to `auto` (e.g., `16 / 9`, `4 / 3`). Defaults to `16 / 9`.

The embed shortcode automatically detects the URL type and uses the appropriate handler:

- **YouTube** (`youtube.com`, `youtu.be`): Embeds as YouTube video player
- **GitHub Gist** (`gist.github.com`): Embeds as Gist script
- **Vimeo** (`vimeo.com`): Embeds as Vimeo player
- **Rutube** (`rutube.ru`): Embeds as Rutube video player
- **Other URLs**: Falls back to generic iframe

**YouTube Examples:**

```
{embed}https://www.youtube.com/watch?v=dQw4w9WgXcQ{/embed}
```

```
{embed url="https://www.youtube.com/watch?v=dQw4w9WgXcQ" width="800" height="600"}
```

```
{embed}https://youtu.be/dQw4w9WgXcQ{/embed}
```

**Vimeo Example:**

```
{embed}https://vimeo.com/123456789{/embed}
```

```
{embed url="https://vimeo.com/123456789" autoplay start="30" end="60"}
```

**Rutube Examples:**

```
{embed}https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/{/embed}
```

```
{embed url="https://rutube.ru/pl/THEBEST/a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6/" width="800" height="auto" aspect-ratio="4 / 3" autoplay="true" start="60" end="120"}
```

**Gist Example:**

```
{embed}https://gist.github.com/user/12345{/embed}
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

Generates "Lorem Ipsum" placeholder text.

`{lorem words=100 wrap="p.my-class,3"}`

-   `words`: The number of words to generate. You can specify a single number or a range (e.g., `words=50,100` to get a random number of words between 50 and 100). Defaults to `84` (full default text).
-   `wrap`: Wraps the generated Lorem Ipsum text in HTML tags.
    -   **Format:** `tag.class,count` or `tag,count` or `tag.class` or `tag`.
    -   `tag`: The HTML tag to use (e.g., `p`, `div`, `ul`, `ol`).
    -   `class`: (Optional) A CSS class to apply to the wrapper tag (e.g., `my-class`).
    -   `count`: (Optional) The number of times to repeat the wrapper tag. Defaults to `1`. If the tag is `ul` or `ol`, this count specifies the number of `<li>` items to generate within the list.

**Examples:**

```
{lorem}
```
Generates the full default Lorem Ipsum text.

```
{lorem words=50}
```
Generates 50 words of Lorem Ipsum text.

```
{lorem words=20,30}
```
Generates a random number of words (between 20 and 30) of Lorem Ipsum text.

```
{lorem wrap="p"}
```
Generates a single paragraph (`<p>`) containing the full Lorem Ipsum text.

```
{lorem wrap="p,3"}
```
Generates three paragraphs (`<p>`) each containing the full Lorem Ipsum text.

```
{lorem wrap="ul.my-list,5" words="5,10"}
```
Generates an unordered list (`<ul class="my-list">`) with five list items (`<li>`), where each list item contains 5 to 10 words of Lorem Ipsum text.


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
