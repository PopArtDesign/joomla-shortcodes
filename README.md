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

Embeds remote resources (YouTube videos, GitHub Gists, Vimeo videos, or any URL as iframe).

`{embed url="..." width=... height=... class=... title=...}...{/embed}`

The URL can be provided either as a `url` attribute or as the content between the tags:

-   `url`: The URL to embed (YouTube, Gist, Vimeo, or any URL). Required if not using nested content.
-   `width`: The width of the embed. Defaults to `100%` (iframe) or type-specific default.
-   `height`: The height of the embed. Defaults to `500` (iframe) or type-specific default.
-   `class`: A CSS class for the container `div`.
-   `title`: The `title` attribute for the iframe.

The embed shortcode automatically detects the URL type and uses the appropriate handler:

- **YouTube** (`youtube.com`, `youtu.be`): Embeds as YouTube video player
- **GitHub Gist** (`gist.github.com`): Embeds as Gist script
- **Vimeo** (`vimeo.com`): Embeds as Vimeo player
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

**Gist Example:**

```
{embed}https://gist.github.com/user/12345{/embed}
```

```
{embed url="https://gist.github.com/user/12345" file="example.php"}
```

**Vimeo Example:**

```
{embed}https://vimeo.com/123456789{/embed}
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
