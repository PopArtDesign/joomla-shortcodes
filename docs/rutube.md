### `rutube`

Embeds Rutube videos.

`{rutube url [attr="value"] ...}`

-   `url`: The Rutube video URL (e.g., `https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/`). Required if not using nested content.
-   `width`: The width of the embed. Defaults to `100%`.
-   `height`: The height of the embed. Defaults to `auto` (for aspect ratio calculation).
-   `id`: A CSS id for the container `div`.
-   `class`: A CSS class for the container `div`.
-   `autoplay`: Automatically starts playing the video. Set to `true` or `1` to enable.
-   `mute`: Mutes the video. Set to `true` or `1` to enable. Autoplayed videos are always muted by default.
-   `start`: The time in seconds (or `MM:SS` format) from which playback will begin.
-   `end`: The time in seconds (or `MM:SS` format) at which playback will end.
-   `loop`: Continuously replay the video. Set to `true` or `1` to enable. (Note: Rutube's API documentation for this parameter is not explicit, functionality is based on common player behavior.)
-   `controls`: Show or hide player controls. Set to `false` or `0` to hide. Defaults to `true`. (Note: Rutube's API documentation for this parameter is not explicit, functionality is based on common player behavior.)
-   `aspect-ratio`: The aspect ratio of the embed when `height` is set to `auto` (e.g., `16 / 9`, `4 / 3`). Defaults to `16 / 9`.

In addition to the attributes listed, any other standard `<iframe>` attributes (e.g., `title`, `loading`, `sandbox`, `referrerpolicy`) can be provided and will be passed directly to the generated `<iframe>` element.

Note that `width`, `height`, `id`, and `class` are applied to the wrapper `div` and not the iframe itself.

**Example:**

```
{rutube https://rutube.ru/video/0a7e6d2a7c2b5f6a5b1c3d0b1e0a7b1c/ autoplay}
```
