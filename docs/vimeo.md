# vimeo

Embeds [Vimeo](https://vimeo.com) videos.

`{vimeo url [attr="value"] ...}`

-   `url`: The Vimeo video URL (e.g., `https://vimeo.com/123456789`). Required if not using nested content.
-   `autoplay`: Automatically starts playing the video. Set to `true` or `1` to enable.
-   `mute`: Mutes the video. Set to `true` or `1` to enable. Autoplayed videos are always muted by default.
-   `start`: The time in seconds (or `MM:SS` format) from which playback will begin.
-   `end`: The time in seconds (or `MM:SS` format) at which playback will end.
-   `loop`: Continuously replay the video. Set to `true` or `1` to enable.
-   `controls`: Show or hide player controls. Set to `false` or `0` to hide. Defaults to `true`.
-   `aspect-ratio`: The aspect ratio of the embed when `height` is set to `auto` (e.g., `16 / 9`, `4 / 3`). Defaults to `16 / 9`.
-   `width`: The width of the embed. Defaults to `100%`.
-   `height`: The height of the embed. Defaults to `auto` (for aspect ratio calculation).
-   `id`: A CSS id for the container `div`.
-   `class`: A CSS class for the container `div`.

In addition to the attributes listed, any other standard `<iframe>` attributes (e.g., `title`, `loading`, `sandbox`, `referrerpolicy`) can be provided and will be passed directly to the generated `<iframe>` element.

Note that `width`, `height`, `id`, and `class` are applied to the wrapper `div` and not the `iframe` itself.

## Examples

```
{vimeo autoplay start=1:00 end=3:00}
https://vimeo.com/123456789
{/vimeo}
```

```
{vimeo https://vimeo.com/123456789 width=800 height=600}
```

```
{vimeo https://vimeo.com/123456789 aspect-ratio=4/3}
```
