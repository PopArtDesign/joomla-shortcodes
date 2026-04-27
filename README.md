# Joomla Shortcodes

[![CI](https://github.com/PopArtDesign/joomla-shortcodes/actions/workflows/ci.yml/badge.svg)](https://github.com/PopArtDesign/joomla-shortcodes/actions/workflows/ci.yml)

A shortcodes pack for [Joomla Shortcoder](https://github.com/PopArtDesign/joomla-shortcoder).

## Installation

1.  Download the [latest release package](https://github.com/PopArtDesign/joomla-shortcodes/releases/latest) (a `.zip` file).
2.  In your Joomla administrator panel, go to `System` -> `Install` -> `Extensions`.
3.  Upload the downloaded `.zip` file.
4.  Enable the "Content - Shortcodes" plugin by going to `System` -> `Manage` -> `Plugins`.

## Usage

### `youtube`

Embeds a YouTube video.

`{youtube videoId|url width=560 height=315 start=0 ...}`

-   `videoId|url`: The YouTube video ID or full YouTube URL. This is the first parameter and is required.
-   `width`: The width of the video player. Defaults to `560`.
-   `height`: The height of the video player. Defaults to `315`.
-   `start`: The time in seconds or in `MM:SS` format from which to start the video. Defaults to `0`.
-   `allow`: The `allow` attribute for the iframe.
-   `title`: The `title` attribute for the iframe.
-   `class`: A CSS class for the container `div`. Defaults to `youtube-container`.

**Example:**

```
{youtube oHg5SJYRHA0}
```

```
{youtube https://www.youtube.com/watch?v=oHg5SJYRHA0 width=800 height=600}
```

### `gist`

Embeds a GitHub Gist.

`{gist id|url file=...}`

-   `id|url`: The Gist ID or the full URL of the Gist.
-   `file`: (Optional) The specific file within the Gist to display.

**Example:**

```
{gist 1234567890abcdef}
```

```
{gist https://gist.github.com/user/1234567890abcdef file=my-file.js}
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
